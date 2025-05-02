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
        $payouts = WithdrawTransMaster::where('our_client_user_name',$username)
        ->select(['id',
        'our_client_order_id',
        'remittance_amount',
        'apply_amount',
        'apply_user_name',
        'apply_account',
        'apply_bank_name',
        'apply_bank_code',
        'apply_ifsc',
        'status',
        'created_at',
        ])
            ->get();

        return DataTables::of($payouts)
            ->make(true);
    }

}
