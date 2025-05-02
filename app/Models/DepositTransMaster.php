<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepositTransMaster extends Model
{
    protected $table = 'deposit_trans_master';

    protected $primaryKey = 'id';

    public $timestamps = false; // Because you're handling timestamps manually in DB

    protected $fillable = [
        'order_id',
        'signature',
        'merchant_code',
        'deposite_service_id',
        'deposite_status_code',
        'realamount',
        'merchant_fee',
        'apply_amount',
        'return_url',
        'callback_url',
        'payment_link',
        'transaction_merchant_order_id',
        'payee_name',
        'payee_bank_name',
        'payee_branch_name',
        'payee_account',
        'our_client_order_id',
        'our_client_callback_url',
        'our_client_return_url',
        'our_client_user_name',
        'status',
        'created_at',
        'updated_at'
    ];
}
