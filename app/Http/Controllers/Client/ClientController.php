<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return view('client.dashboard');
        }

    public function clientpayinAnalytics()
    {
        return view('client.payinanalytics');
    }   
    public function clientpayoutAnalytics()
    {
        return view('client.payoutanalytics');
    }
    public function analytics()
    {
        return view('client.analytics');
    }
    public function support()
    {
        return view('client.clientsupport');
    }
    public function clientsettlement()
    {
        return view('client.clientsettlement');
    }
    public function clientwithdrawal()
    {
        return view('client.clientwithdrawal');
    }
}
