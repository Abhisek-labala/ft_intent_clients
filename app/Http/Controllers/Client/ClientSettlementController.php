<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ClientSettlement;
use App\Models\ClientAmountDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Log;
use App\Models\User;
class ClientSettlementController extends Controller
{
    
    public function getData(Request $request)
    {
        $username = auth()->user()->username;
        $query = ClientSettlement::query();
        $query->where('merchant_name', $username);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        if ($startDate && $endDate) {
            try {
                $startDate = Carbon::parse($startDate)->startOfDay();
                $endDate = Carbon::parse($endDate)->endOfDay();

                $query->whereBetween('settlement_date', [$startDate, $endDate]);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Invalid date format.',
                ], 400);
            }
        }

        return datatables()->of($query)
            ->addColumn('converted_amount', function (ClientSettlement $settlement) {
                return $settlement->settlement_amount;
            })
            ->addColumn('created_at', function (ClientSettlement $settlement) {
                $createdAt = $settlement->settlement_date;
                if ($createdAt instanceof Carbon) {
                    return $createdAt->format('Y-m-d');
                } elseif (is_string($createdAt)) {
                    return Carbon::parse($createdAt)->format('Y-m-d');
                }
                return null;
            })
            ->make(true);
    }

    public function getTotalAmount()
    {
        $username = auth()->user()->username;
        $deposite_percentage = auth()->user()->client_deposite_percentage;
        // $withdraw_percentage = auth()->user()->client_withdraw_percentage;

        // Fetch all records for the current user
        $clientAmounts = ClientAmountDetail::where('merchant_name', $username)->get();

        // Initialize total amounts
        // $totalDepositAmount = 0;
        // $totalTransactionFee = 0;
        // $totalWithdrawAmount = 0;
        // $totalTransactionWithdrawFee = 0;

        // // Iterate over the collection, calculate transaction fees, and sum the amounts
        foreach ($clientAmounts as $clientAmount) {
        //     $totalDepositAmount += $clientAmount->total_deposit_amount;
        //     $totalWithdrawAmount = $clientAmount->total_withdraw_amount;

        //     // Calculate transaction fee as 8% of total deposit amount
        //     $transactionFee = ($clientAmount->total_deposit_amount * $deposite_percentage) / 100;
        //     $totalTransactionFee += $transactionFee;

        //     //calculate transaction fee of withdraw
        //     $remainingAfterDepositFee = $totalDepositAmount - $totalTransactionFee;
        //     // $transactionWithdrawFee = ($remainingAfterDepositFee * $withdraw_percentage) / 100;
        //     // $totalTransactionWithdrawFee += $transactionWithdrawFee;


        //     // Update and save the transaction fee for each record
        //     // $clientAmount->transaction_fee_withdraw = $transactionWithdrawFee;
        //     $clientAmount->total_available_amount = $totalDepositAmount - $totalTransactionFee - $totalWithdrawAmount - $totalTransactionWithdrawFee;
        //     $clientAmount->transaction_fee_deposite = $transactionFee;
        //     $clientAmount->save();
        $totalAmount = $clientAmount->total_outstanding_amount;
        }

        return response()->json([
            'total_amount' => $totalAmount
        ], 200);
    }

    public function submitSettlement(Request $request, FirebaseService $firebaseService)
    {
        $username = auth()->user()->username;

        // Validate the request data
        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'payment_details' => 'required|array',
        ]);
        if($validatedData['amount']<20000)
        {
            return response()->json(['success'=>false,'message'=>"minimum settlement amount is 20000"],500);
        }
        // Retrieve the client's account details from the clientamountdetails table
        $transactionfees=0;
        $percentage=0;
        $SettlementAmount =0;
        $clientAccount = ClientAmountDetail::where('merchant_name', $username)->first();
        $percentage=User::where('username',$username)->value('client_settlement_perc');
        // Check if the client has enough balance to process the settlement
        if ($clientAccount && $clientAccount->total_outstanding_amount >= $validatedData['amount']) {
            // Generate a unique settlement number
            $settlementNumber = 'FTSET-' . mt_rand(1000, 9999) . time();

            // Begin a transaction to ensure atomicity
            DB::beginTransaction();
            try {
                $amount=$validatedData['amount'];
                $transactionfees=($amount*$percentage)/100;
                $SettlementAmount =$amount -$transactionfees;
                $clientAccount->client_settlement_fees += $transactionfees;
                $clientAccount->save();
                // Create a new settlement request
                $settlement = ClientSettlement::create([
                    'settlement_no' => $settlementNumber,
                    'settlement_amount' => $validatedData['amount'],
                    'settlement_date' => Carbon::now('Asia/Kolkata'),
                    'payment_method' => $validatedData['payment_method'],
                    'payment_details' => json_encode($validatedData['payment_details']),
                    'status' => 'Pending',
                    'merchant_name' => $username,
                    'final_settled_amount' => $SettlementAmount,
                    'settlement_transaction_fees'=>$transactionfees,
                ]);
               

                // Commit the transaction
                DB::commit();
                $adminUsers = User::where('role', 'admin')->get();
                foreach ($adminUsers as $admin) {
                    if ($admin->fcm_token) {
                        $firebaseService->sendNotification(
                            $admin->fcm_token,
                            'New Settlement Request',
                            'A Settlement of $' . $validatedData['amount'] . ' has been processed for user: ' . $username
                        );
                        Log::info('Settlement Notification sent to admin', ['admin_username' => $admin->username, 'withdraw_no' =>$settlementNumber]);
                    }
                }
                // Return a success response
                return response()->json([
                    'message' => 'Settlement request submitted successfully.',
                    'settlement_no' => $settlement->settlement_no,
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

}
