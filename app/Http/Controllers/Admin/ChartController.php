<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use DB;
use Carbon\Carbon;

class ChartController extends Controller
{
    public function getPayinData(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Adjust to current time in Asia/Kolkata timezone
        $currentDateTime = Carbon::now('Asia/Kolkata');

        // Convert start and end dates to Asia/Kolkata timezone
        $startDate = Carbon::parse($startDate)->timezone('Asia/Kolkata')->format('Y-m-d');
        $endDate = Carbon::parse($endDate)->timezone('Asia/Kolkata')->endOfDay()->format('Y-m-d H:i:s');

        // Log date range for debugging
        \Log::info("Payin Data - Start Date: $startDate, End Date: $endDate");

        $labels = $this->getLabels($startDate, $endDate);
        $barChartData = $this->getBarChartData($startDate, $endDate, 'credited');

        $data = [
            'labels' => $labels,
            'barChartData' => $barChartData,
            'currentDateTime' => $currentDateTime->toDateTimeString()
        ];

        return response()->json(['data' => $data]);
    }

    public function getPayoutData(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Adjust to current time in Asia/Kolkata timezone
        $currentDateTime = Carbon::now('Asia/Kolkata');

        // Convert start and end dates to Asia/Kolkata timezone
        $startDate = Carbon::parse($startDate)->timezone('Asia/Kolkata')->format('Y-m-d');
        $endDate = Carbon::parse($endDate)->timezone('Asia/Kolkata')->endOfDay()->format('Y-m-d H:i:s');

        // Log date range for debugging
        \Log::info("Payout Data - Start Date: $startDate, End Date: $endDate");

        $labels = $this->getLabels($startDate, $endDate);
        $barChartData = $this->getBarChartData($startDate, $endDate, 'debited');

        $data = [
            'labels' => $labels,
            'barChartData' => $barChartData,
            'currentDateTime' => $currentDateTime->toDateTimeString()
        ];

        return response()->json(['data' => $data]);
    }

    private function getLabels($startDate, $endDate)
    {
        // Set timezone to Asia/Kolkata
        $timezone = 'Asia/Kolkata';
        $start = Carbon::parse($startDate)->timezone($timezone);
        $end = Carbon::parse($endDate)->timezone($timezone);
        $labels = [];

        while ($start->lte($end)) {
            $labels[] = $start->format('Y-m-d');
            $start->addDay();
        }

        return $labels;
    }

    private function getBarChartData($startDate, $endDate, $transactionType)
    {
        // Perform query with timezone conversion
        $query = Transaction::select(DB::raw('SUM(amount_inr) as total_amount, DATE(created_at AT TIME ZONE \'UTC\' AT TIME ZONE \'Asia/Kolkata\') as date'))
            ->where('transaction_type', $transactionType)
            ->where('status','approved')
            ->whereBetween(DB::raw('DATE(created_at AT TIME ZONE \'UTC\' AT TIME ZONE \'Asia/Kolkata\')'), [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at AT TIME ZONE \'UTC\' AT TIME ZONE \'Asia/Kolkata\')'))
            ->get();

        // Prepare the range for labels and data
        $dates = [];
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($start->lte($end)) {
            $dates[] = $start->format('Y-m-d');
            $start->addDay();
        }

        // Initialize with zero values
        $barChartData = array_fill_keys($dates, 0);

        // Populate with actual data
        foreach ($query as $result) {
            if (isset($barChartData[$result->date])) {
                $barChartData[$result->date] = $result->total_amount;
            }
        }

        // Convert to a flat array
        return array_values($barChartData);
    }

    public function getclientPayinData(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Adjust to current time in Asia/Kolkata timezone
        $currentDateTime = Carbon::now('Asia/Kolkata');

        // Convert start and end dates to Asia/Kolkata timezone
        $startDate = Carbon::parse($startDate)->timezone('Asia/Kolkata')->format('Y-m-d');
        $endDate = Carbon::parse($endDate)->timezone('Asia/Kolkata')->endOfDay()->format('Y-m-d H:i:s');

        // Log date range for debugging
        \Log::info("Payin Data - Start Date: $startDate, End Date: $endDate");

        $labels = $this->getLabels($startDate, $endDate);
        $username = auth()->user()->username;
        $barChartData = $this->getclientBarChartData($startDate, $endDate, 'credited',$username);

        $data = [
            'labels' => $labels,
            'barChartData' => $barChartData,
            'currentDateTime' => $currentDateTime->toDateTimeString()
        ];

        return response()->json(['data' => $data]);
    }
    
    public function getclientPayoutData(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Adjust to current time in Asia/Kolkata timezone
        $currentDateTime = Carbon::now('Asia/Kolkata');

        // Convert start and end dates to Asia/Kolkata timezone
        $startDate = Carbon::parse($startDate)->timezone('Asia/Kolkata')->format('Y-m-d');
        $endDate = Carbon::parse($endDate)->timezone('Asia/Kolkata')->endOfDay()->format('Y-m-d H:i:s');
        $username = auth()->user()->username;
        // Log date range for debugging
        \Log::info("Payout Data - Start Date: $startDate, End Date: $endDate , username :$username");

        $labels = $this->getLabels($startDate, $endDate);
        
        $barChartData = $this->getclientBarChartData($startDate, $endDate, 'debited',$username);

        $data = [
            'labels' => $labels,
            'barChartData' => $barChartData,
            'currentDateTime' => $currentDateTime->toDateTimeString()
        ];

        return response()->json(['data' => $data]);
    }

    private function getclientBarChartData($startDate, $endDate, $transactionType,$username)
    {
        // Perform query with timezone conversion
        $query = Transaction::select(DB::raw('SUM(amount_inr) as total_amount, DATE(created_at AT TIME ZONE \'UTC\' AT TIME ZONE \'Asia/Kolkata\') as date'))
            ->where('transaction_type', $transactionType)
            ->where('merchant_name',$username)
            ->where('status','approved')
            ->whereBetween(DB::raw('DATE(created_at AT TIME ZONE \'UTC\' AT TIME ZONE \'Asia/Kolkata\')'), [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at AT TIME ZONE \'UTC\' AT TIME ZONE \'Asia/Kolkata\')'))
            ->get();

            \Log::info("qibgg : $query");
        // Prepare the range for labels and data
        $dates = [];
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($start->lte($end)) {
            $dates[] = $start->format('Y-m-d');
            $start->addDay();
        }

        // Initialize with zero values
        $barChartData = array_fill_keys($dates, 0);

        // Populate with actual data
        foreach ($query as $result) {
            if (isset($barChartData[$result->date])) {
                $barChartData[$result->date] = $result->total_amount;
            }
        }

        // Convert to a flat array
        return array_values($barChartData);
    }
}
