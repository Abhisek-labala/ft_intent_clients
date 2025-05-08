<?php

namespace App\Http\Controllers\Client;

use App\Models\WithdrawTransMaster;
use Auth;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ClientWithdrawController extends Controller
{
    public function index()
    {
        return view('client.clientwithdraw');
    }

    public function getData(Request $request)
    {
        $userid=Auth::id();
    $username = User::where('id', $userid)->value('username');
        $payouts = DB::table('withdraw_trans_master as wtm')
        ->where('our_client_user_name',$username)
        ->leftJoin('withdraw_status_code as wsc','wsc.status_code','=','wtm.withdraw_status_code')
        ->select(['wtm.id',
        'wtm.our_client_order_id',
        'wtm.remittance_amount',
        'wtm.apply_amount',
        'wtm.apply_user_name',
        'wtm.apply_account',
        'wtm.apply_bank_name',
        'wtm.apply_bank_code',
        'wtm.apply_ifsc',
        'wtm.status',
        'wtm.created_at',
        'wsc.status_label'
        ])
            ->get();

        return DataTables::of($payouts)
            ->make(true);
    }

}
