<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Transaction; 
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class ClientDepositeController extends Controller
{
    public function index()
    {
        return view('client.clientdeposite');
    }
    public function getData(Request $request)
{
    $userid=Auth::id();
    $username = User::where('id', $userid)->value('username');
    $payins = Transaction::select([
            'transactions.id',
            'transactions.client_transaction_id',
            'transactions.order_id',
            'transactions.channel',
            'transactions.channel_id',
            'transactions.status',
            'transactions.transaction_ref_no',
            'transactions.amount_inr',
            'transactions.created_at',
        ])
        ->leftJoin('users as users', 'transactions.user_id', '=', 'users.id') // Use left join to include transactions without a user
        ->where('transaction_type', 'credited')
        ->where('merchant_name',$username)
        ->whereNot('status','Initiated')
        ->orderBy('transactions.id','desc')
        ->get();

    return DataTables::of($payins)
        ->make(true);
}
    
}
