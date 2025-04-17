<?php
use App\Http\Controllers\Client\ClientDepositeController;
use App\Http\Controllers\Client\ClientLoginController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Client\ClientReportController;
use App\Http\Controllers\Client\ClientSettlementController;
use App\Http\Controllers\Client\ClientWithdrawController;
use App\Http\Controllers\Client\ClientWithdrawalController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Admin\ChartController;



Route::get('clientlogout', [LogoutController::class, 'clientlogout'])->name('clientlogout');
Route::get('/', [ClientLoginController::class, 'showLoginForm'])->name('clientlogin');
Route::post('/', [ClientLoginController::class, 'login'])->name('clientlogin');
Route::post('/register',[ClientLoginController::class , 'register'])->name('clientregister');
Route::get('/register',[ClientLoginController::class ,'showregisterform'])->name('register');


Route::middleware(['client'])->group(function () {
    Route::get('/clientdashboard', [ClientController::class, 'index'])->name('client.dashboard');
    Route::get('/clientdashboard', [DashboardController::class, 'clientdashboardData'])->name('client.dashboard');
    Route::get('/clientpayinanalytics', [ClientController::class, 'clientpayinAnalytics'])->name('client.payinAnalytics');
    Route::get('/client/getpayin', [ChartController::class, 'getclientPayinData'])->name('client.getpayin');
    Route::get('/clientpayoutanalytics', [ClientController::class, 'clientpayoutAnalytics'])->name('client.payoutAnalytics');
    Route::get('/client/getpayout', [ChartController::class, 'getclientPayoutData'])->name('client.getpayout');
    Route::get('/analytics', [ClientController::class, 'analytics'])->name('client.analytics');
    Route::get('client/getpayin', [ChartController::class, 'getclientPayinData'])->name('client.getpayin');
    Route::get('client/getpayout', [ChartController::class, 'getclientPayoutData'])->name('client.getpayout');
    Route::get('/clientdeposit', [ClientDepositeController::class, 'index'])->name('clientdeposit');
    Route::get('/clientdepositedata', [ClientDepositeController::class, 'getData'])->name('clientdepositedata');
    Route::get('/clientwithdraw', [ClientWithdrawController::class, 'index'])->name('clientwithdraw');
    Route::get('/clientwithdrawdata', [ClientWithdrawController::class, 'getData'])->name('clientwithdrawdata');
    Route::get('/clientreport', [ClientReportController::class, 'index'])->name('clientreport');
    Route::post('/clientreport/generate', [ClientReportController::class, 'generateReport'])->name('clientreport.generate');
    Route::get('/clientsupport',[ClientController::class,'support'])->name('clientsupport');
    Route::get('/clientsettlement',[ClientController::class,'clientsettlement'])->name('clientsettlement');
    Route::get('/clientwithdrawal',[ClientController::class,'clientwithdrawal'])->name('clientwithdrawal');
    Route::get('/settlements/data', [ClientSettlementController::class, 'getData'])->name('settlements.data');
    Route::get('/withdraw/data', [ClientWithdrawalController::class, 'getData'])->name('withdraw.data');
    Route::get('/getTotalAmount',[ClientSettlementController::class,'gettotalAmount'])->name('getTotalAmount');
    Route::get('/getTotalavailableAmount',[ClientWithdrawalController::class,'gettotalAmount'])->name('getTotalavailableAmount');
    Route::post('/submitSettlement',[ClientSettlementController::class,'submitSettlement'])->name('submitSettlement');
    Route::post('/submitwithdraw',[ClientWithdrawalController::class,'submitwithdraw'])->name('submitwithdraw');
});