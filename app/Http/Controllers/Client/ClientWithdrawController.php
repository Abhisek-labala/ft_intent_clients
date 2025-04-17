<?php

namespace App\Http\Controllers\Client;

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
        $payouts = Transaction::select([
                'transactions.id',
                'transactions.client_transaction_id',
                'transactions.order_id',
                'transactions.channel',
                'transactions.channel_id',
                'transactions.status',
                'transactions.transaction_ref_no',
                'transactions.created_at',
                'transactions.amount_inr',
            ])
            ->leftJoin('users as users', 'transactions.user_id', '=', 'users.id')
            ->where('transaction_type', 'debited')
            ->where('merchant_name',$username)
            ->orderBy('transactions.id','desc')
            ->get();

        return DataTables::of($payouts)
            ->make(true);
    }

}