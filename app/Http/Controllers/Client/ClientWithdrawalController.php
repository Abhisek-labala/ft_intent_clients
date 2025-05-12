<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ClientWithdraw;
use App\Models\ClientAmountDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClientWithdrawalController extends Controller
{
    public function getData(Request $request)
    {
        $username = auth()->user()->username;
        $query = ClientWithdraw::query();
        $query->where('merchant_name', $username);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Apply date filtering only if both dates are provided
        if ($startDate && $endDate) {
            try {
                $startDate = Carbon::parse($startDate)->startOfDay();
                $endDate = Carbon::parse($endDate)->endOfDay();

                $query->whereBetween('withdraw_date', [$startDate, $endDate]);
            } catch (\Exception $e) {
                // Log the error or handle it as needed
                return response()->json([
                    'error' => 'Invalid date format.',
                ], 400);
            }
        }

        return datatables()->of($query)
            ->addColumn('withdrawn_amount', function (ClientWithdraw $withdraw) {
                return $withdraw->withdraw_amount;
            })
            ->addColumn('created_at', function (ClientWithdraw $withdraw) {
                // Check if created_at is a Carbon instance or a string
                $createdAt = $withdraw->withdraw_date;
                if ($createdAt instanceof Carbon) {
                    return $createdAt->format('Y-m-d');
                } elseif (is_string($createdAt)) {
                    // Convert string to Carbon instance if needed
                    return Carbon::parse($createdAt)->format('Y-m-d');
                }
                return null; // Return null if created_at is not set
            })
            ->make(true);
    }

    public function submitwithdraw(Request $request)
    {
        $username = auth()->user()->username;

        // Validate the request data
        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        if($validatedData['amount']<10000)
        {
            return response()->json(['success'=>false,'message'=>"minimum withdrawal amount is 10000"],500);
        }
        $clientAccount = ClientAmountDetail::where('merchant_name', $username)->first();
        $percentage=User::where('username',$username)->value('client_withdraw_percentage');

        if ($clientAccount && $clientAccount->total_outstanding_amount >= $validatedData['amount']) {
            $withdrawnumber = 'FTWD-' . mt_rand(1000, 9999) . time();
            DB::beginTransaction();
            try {

                // Create a new settlement request
                $withdraw = ClientWithdraw::create([
                    'withdraw_no' => $withdrawnumber,
                    'withdraw_amount' => $validatedData['amount'],
                    'withdraw_date' => Carbon::now('Asia/Kolkata'),
                    'status' => 'Success',
                    'merchant_name' => $username,
                ]);

                // Deduct the settlement amount from the totaldeposite
                $amount=$validatedData['amount'];
                $transactionfees=($amount*$percentage)/100;
                $withdrawAMount =$amount -$transactionfees;
                $clientAccount->transaction_fee_withdraw +=$transactionfees;
                $clientAccount->total_outstanding_amount -= $validatedData['amount'];
                $clientAccount->total_withdraw_amount += $withdrawAMount;
                $clientAccount->tota_amt_to_withdraw += $withdrawAMount;
                $clientAccount->total_available_amount += $withdrawAMount;
                $clientAccount->save();

                // Commit the transaction
                DB::commit();
                // Return a success response
                return response()->json([
                    'message' => 'Withdraw request submitted successfully.',
                    'settlement_no' => $withdrawnumber,
                ], 200);

            } catch (\Exception $e) {
                // Rollback the transaction in case of any errors
                DB::rollBack();

                // Return an error response
                return response()->json([
                    'message' => 'Failed to submit the settlement request. Please try again.',
                    'error' => $e->getMessage(),
                ], 500);
            }

        } else {
            // Return an error if the balance is insufficient
            return response()->json([
                'message' => 'Insufficient balance to process the settlement.',
            ], 400);

        }
    }

    public function getTotalAmount()
    {
        $username = auth()->user()->username;
        $deposite_percentage = auth()->user()->client_deposite_percentage;
        $clientAmounts = ClientAmountDetail::where('merchant_name', $username)->get();
        foreach ($clientAmounts as $clientAmount) {
        $totalAmount = $clientAmount->total_outstanding_amount;
        }

        return response()->json([
            'total_amount' => $totalAmount
        ], 200);
    }
}
