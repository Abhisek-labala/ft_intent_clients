<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ClientWithdraw;
use App\Models\DepositTransMaster;
use App\Models\Transaction;
use App\Models\ClientAmountDetail;
use App\Models\ClientSettlement;
use App\Models\WithdrawTransMaster;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function clientdashboardData()
    {
        $username = auth()->user()->username;
        $deposite_percentage = auth()->user()->client_deposite_percentage;
        $withdraw_percentage = auth()->user()->client_withdraw_percentage;

        $overaldeposit=DepositTransMaster::where('our_client_user_name',$username)->sum('apply_amount');

        $resolvedpayment=DepositTransMaster::where('deposite_status_code', '2')->where('our_client_user_name',$username)->sum('realamount');
        $deposite_percentage_amount=($resolvedpayment * $deposite_percentage)/100;

        $withdraw=WithdrawTransMaster::where('withdraw_status_code', '3')->where('our_client_user_name',$username)->sum('remittance_amount');
        $settlemtnamount =ClientSettlement::where('merchant_name',$username)->where('status','approved')->sum('settlement_amount');
        $outstanding =ClientAmountDetail::where('merchant_name',$username)->value('total_outstanding_amount');
        $withdrawload=ClientWithdraw::where('merchant_name',$username)->where('status','approved')->sum('withdraw_amount');
        $withdrawpercamount =($withdrawload * $withdraw_percentage)/100;
        $availbaleamount =ClientAmountDetail::where('merchant_name',$username)->value('total_available_amount');
        return view('client.dashboard', compact(
            'overaldeposit','resolvedpayment', 'deposite_percentage_amount','withdraw','withdraw_percentage',
            'deposite_percentage','withdrawpercamount','settlemtnamount','outstanding','withdrawload','availbaleamount'
        ));
    }
}
