<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class ClientReportController extends Controller
{
    public function index()
    {
        return view('client.clientReports');
    }

    public function generateReport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $type = $request->input('type');
        $username = auth()->user()->username;

        $query = Transaction::query()
            ->select('transactions.*');

        if ($startDate) {
            $query->whereDate('transactions.created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('transactions.created_at', '<=', $endDate);
        }

        if ($type == 'deposite') {
            $query->where('transactions.transaction_type', 'credited')
                  ->where('transactions.merchant_name', $username)
                  ->whereNotNull('transactions.transaction_ref_no');
        } else if ($type == 'withdraw') {
            $query->where('transactions.transaction_type', 'debited')
                  ->where('transactions.merchant_name', $username)
                  ->whereNotNull('transactions.transaction_ref_no');
        }

        $results = $query->get();

        return view('client.clientReports', compact('results', 'startDate', 'endDate', 'type'));
    }
}
