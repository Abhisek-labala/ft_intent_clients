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
    $payins =DepositTransMaster::where('our_client_user_name',$username)
    ->select([
        'id',
        'our_client_order_id',
        'realamount',
        'apply_amount',
        'status',
        'created_at'
    ])->get();

    return DataTables::of($payins)
        ->make(true);
}

}
