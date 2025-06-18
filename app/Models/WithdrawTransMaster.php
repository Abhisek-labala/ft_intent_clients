<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawTransMaster extends Model
{
    protected $table = 'withdraw_trans_master';

    protected $primaryKey = 'id';

    public $timestamps = false; // Because you're handling timestamps manually in DB

    protected $fillable = [
        'order_id',
        'signature',
        'merchant_code',
        'withdraw_service_id',
        'withdraw_status_code',
        'remittance_amount',
        'merchant_fee',
        'apply_amount',
        'return_url',
        'callback_url',
        'payment_link',
        'transaction_merchant_order_id',
        'apply_user_name',
        'apply_account',
        'apply_bank_name',
        'apply_bank_code',
        'apply_ifsc',
        'our_client_order_id',
        'our_client_callback_url',
        'our_client_return_url',
        'our_client_user_name',
        'status',
        'created_at',
        'updated_at',
        'withdraw_fee'
    ];
}
