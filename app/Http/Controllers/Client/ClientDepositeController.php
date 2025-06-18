<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\DepositTransMaster;
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
    $payins =  DB::table('deposit_trans_master as dtm')
    ->where('our_client_user_name', $username)
    ->leftJoin('deposite_status_code as dsc', 'dtm.deposite_status_code', '=', 'dsc.status_code')
    ->select([
        'dtm.id as payin_id',
        'dtm.our_client_order_id as order_id',
        'dtm.realamount as real_amount',
        'dtm.apply_amount as applied_amount',
        'dtm.deposite_status_code as status_code',
        'dtm.created_at as created_at',
        'dsc.status_label as status'
    ])->orderBy('dtm.id','desc')
    ->get();

    return DataTables::of($payins)
        ->make(true);
}

}
