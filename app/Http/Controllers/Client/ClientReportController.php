<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\DepositTransMaster;
use App\Models\WithdrawTransMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientReportController extends Controller
{
    public function index()
    {
        return view('client.clientReports');
    }

    public function generateReport(Request $request) {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $type = $request->input('type');
        $username = auth()->user()->username;
        if ($type === 'payin') {
            // Fetch settlements data
            $results = DepositTransMaster::query()
                ->join('deposite_status_code', 'deposit_trans_master.deposite_status_code', '=', 'deposite_status_code.status_code')
                ->select('deposit_trans_master.id',
            'deposit_trans_master.order_id',
            'deposit_trans_master.our_client_order_id',
            'deposite_status_code.status_label',
            'deposit_trans_master.apply_amount',
            'deposit_trans_master.realamount',
            'deposit_trans_master.created_at', 'deposite_status_code.status_label')
                ->where('deposit_trans_master.our_client_user_name',$username)
                ->when($startDate, function ($query) use ($startDate) {
                    return $query->whereDate('deposit_trans_master.created_at', '>=', $startDate);
                })
                ->when($endDate, function ($query) use ($endDate) {
                    return $query->whereDate('deposit_trans_master.created_at', '<=', $endDate);
                })
                ->get();
        } else {
            // Fetch transactions data
            $results = WithdrawTransMaster::query()
                ->join('withdraw_status_code', 'withdraw_trans_master.withdraw_status_code', '=', 'withdraw_status_code.status_code')
                ->select( 'withdraw_trans_master.id',
            'withdraw_trans_master.order_id',
            'withdraw_trans_master.our_client_order_id',
            'withdraw_status_code.status_level',
            'withdraw_trans_master.remittance_amount',
            'withdraw_trans_master.apply_amount',
            'withdraw_trans_master.apply_user_name',
            'withdraw_trans_master.apply_account',
            'withdraw_trans_master.apply_bank_name',
            'withdraw_trans_master.apply_ifsc',
            'withdraw_trans_master.created_at', 'withdraw_status_code.status_level')
                 ->where('withdraw_trans_master.our_client_user_name',$username)
                ->when($startDate, function ($query) use ($startDate) {
                    return $query->whereDate('withdraw_trans_master.created_at', '>=', $startDate);
                })
                ->when($endDate, function ($query) use ($endDate) {
                    return $query->whereDate('withdraw_trans_master.created_at', '<=', $endDate);
                })
                ->get();
        }

        // Handle case where no results are found
        if ($results->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No results found for the selected criteria.',
                'results' => []
            ]);
        }

        // Return results as JSON
        return response()->json([
            'success' => true,
            'results' => $results,
        ]);
    }
}
