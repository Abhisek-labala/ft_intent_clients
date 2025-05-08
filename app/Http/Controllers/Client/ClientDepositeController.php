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
    $payins = DepositTransMaster::where('our_client_user_name', $username)
    ->leftJoin('deposite_status_code as dsc', 'DepositTransMaster.deposite_status_code', '=', 'dsc.status_code')
    ->select([
        'DepositTransMaster.id as payin_id',
        'DepositTransMaster.our_client_order_id as order_id',
        'DepositTransMaster.realamount as real_amount',
        'DepositTransMaster.apply_amount as applied_amount',
        'DepositTransMaster.deposite_status_code as status_code',
        'DepositTransMaster.created_at as created_at',
        'dsc.status_label as status'  // Alias for 'deposite_status_code'
    ])
    ->get();

    return DataTables::of($payins)
        ->make(true);
}

}
