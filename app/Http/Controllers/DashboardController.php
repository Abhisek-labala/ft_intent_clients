<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\ClientAmountDetail;
use App\Models\ClientSettlement;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function clientdashboardData()
    {
        $username = auth()->user()->username;
        $deposite_percentage = auth()->user()->client_deposite_percentage;
        $deposite_percentage_netbanking = auth()->user()->client_netbanking_dep_perc;
        $withdraw_percentage = auth()->user()->client_withdraw_percentage;
    
        // Calculate totals for all users
        $overallPayment = Transaction::where('transaction_type', 'credited')
            ->where('merchant_name', $username)
            ->sum('amount_inr');
    
        $resolvedpayment = Transaction::where('transaction_type', 'credited')
            ->where('merchant_name', $username)
            ->where('status', 'approved')
            ->sum('amount_inr');
        $comissionupi = Transaction::where('transaction_type', 'credited')
        ->where('merchant_name', $username)
        ->where('status', 'approved')
        ->where('channel','like','%UPI%')
        ->sum('amount_inr');
        $comissionupiamount = ($comissionupi *$deposite_percentage )/100;
        $comissionnetbanking = Transaction::where('transaction_type', 'credited')
        ->where('merchant_name', $username)
        ->where('status', 'approved')
        ->where('channel','like','%Bank A/c%')
        ->sum('amount_inr');
        $comissionnetbankingamount = ($comissionnetbanking *$deposite_percentage_netbanking )/100;
        $failed = Transaction::where('transaction_type', 'credited')
            ->where('merchant_name', $username)
            ->where('status', 'failed')
            ->sum('amount_inr');
    
        $pending = Transaction::where('transaction_type', 'credited')
            ->where('merchant_name', $username)
            ->whereNotIn('status', ['approved', 'failed'])
            ->sum('amount_inr');
        $transactionFeePercent = 0;
        $totalAvailableBalance = 0;
        $totalAmount = 0;
        $totalWithdrawApproved = 0;
        $totalwithdrawfees = 0;
        $resolvedwithdraw = 0;
        $pendingwithdrawal = 0;
        $outstanding =0;
        $Settlement =0;
        $totalpayout=0;
        $totalTransactionWithrawFee=0;
        $totalWithdrawsAmount=0;
        $totalpendingpayoutAmount=0;
        $remaininpayoutamount=0;
        $Settlement =ClientSettlement::where('merchant_name', $username)->where('status','approved')->sum('settlement_amount');
        $ClientAmountDetail = ClientAmountDetail::where('merchant_name', $username)->first();
        if ($ClientAmountDetail) {
            $totalAmount = $ClientAmountDetail->total_deposit_amount;
            $transactionFeePercent = ($totalAmount * $deposite_percentage) / 100;
            $remainingAmount = $totalAmount - $transactionFeePercent;
            $totalWithdrawsAmount = $ClientAmountDetail->total_withdraw_amount;
            $outstanding=$ClientAmountDetail->total_outstanding_amount;
            $totalpayout=$ClientAmountDetail->total_payout;
            $totalAvailableBalance=$totalWithdrawsAmount -$totalpayout;
            $ClientAmountDetail->transaction_fee_withdraw;
            $ClientAmountDetail->total_available_amount=$totalAvailableBalance;
            $ClientAmountDetail->save();
        }
    
        $totalWithdraw = Transaction::where('transaction_type', 'debited')
            ->where('merchant_name', $username)
            ->sum('amount_inr');
    
        $resolvedwithdraw = Transaction::where('transaction_type', 'debited')
            ->where('merchant_name', $username)
            ->where('status', 'approved')
            ->sum('amount_inr');
    
        $pendingwithdrawal = Transaction::where('transaction_type', 'debited')
            ->where('merchant_name', $username)
            ->where('status', 'pending')
            ->sum('amount_inr');
        $totalpendingpayoutAmount = Transaction::where('transaction_type', 'debited')
            ->where('merchant_name', $username)
            ->whereIn('status', ['pending', 'timed_out'])
            ->sum('amount_inr');
    
            
            $totalwithdrawfees = ClientAmountDetail::where('merchant_name', $username)->value('transaction_fee_withdraw');
            $total_available_amount = ClientAmountDetail::where('merchant_name', $username)->value('total_available_amount');
            $remaininpayoutamount =number_format(($total_available_amount - $totalpendingpayoutAmount),2);
          
        return view('client.dashboard', compact(
            'resolvedpayment', 'pending', 'totalAvailableBalance', 'totalWithdraw','totalpayout', 'total_available_amount',
            'resolvedwithdraw', 'overallPayment', 'failed', 'transactionFeePercent', 'comissionnetbankingamount','comissionupiamount','deposite_percentage_netbanking',
            'pendingwithdrawal','totalwithdrawfees','withdraw_percentage','deposite_percentage','outstanding','Settlement','totalAmount','totalWithdrawsAmount','totalpendingpayoutAmount','remaininpayoutamount'
        ));
    }
}
