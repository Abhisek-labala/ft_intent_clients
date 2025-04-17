<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientSettlement extends Model
{
    // Specify the table name if it doesn't follow Laravel's naming convention
    protected $table = 'client_settlement';

    // Specify the primary key if it doesn't follow Laravel's default 'id'
    protected $primaryKey = 'id';

    // Disable timestamps if not using Laravel's created_at and updated_at fields automatically
    public $timestamps = false;

    // Allow mass assignment for these fields
    protected $fillable = [
        'settlement_no',
        'settlement_amount',
        'settlement_date',
        'created_at',
        'updated_at',
        'payment_method',
        'payment_details','status','merchant_name','transaction_fee_withdraw','final_settled_amount','settlement_transaction_fees'
    ];

    // If you want Laravel to automatically manage `created_at` and `updated_at` timestamps, you can enable it:
    // public $timestamps = true;

    // If you have different column names for timestamps:
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}