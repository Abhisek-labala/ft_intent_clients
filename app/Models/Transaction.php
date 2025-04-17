<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'order_id',
        'channel',
        'channel_id',
        'status',
        'transaction_ref_no',
        'expires_in',
        'amount_inr',
        'credited_time_date',
        'transaction_type',
        'transaction_image',
        'commision_amount',
        'user_id',
        'commission_redeem_status',
        'is_credited',
        'is_debited',
        'remarked_by',
        'coupon_selected',
        'mobile_no',
        'merchant_name',
    ];

    protected $casts = [
        'expires_in' => 'datetime',
        'credited_time_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'commission_redeem_status' => 'boolean',
        'is_credited' => 'boolean',
        'is_debited' => 'boolean'
    ];
}
