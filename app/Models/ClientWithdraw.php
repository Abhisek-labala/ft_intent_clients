<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientWithdraw extends Model
{
    // Specify the table name if it doesn't follow Laravel's naming convention
    protected $table = 'client_withdraw';

    // Specify the primary key if it doesn't follow Laravel's default 'id'
    protected $primaryKey = 'id';

    // Disable timestamps if not using Laravel's created_at and updated_at fields automatically
    public $timestamps = false;

    // Allow mass assignment for these fields
    protected $fillable = [
        'withdraw_no',
        'withdraw_amount',
        'withdraw_date',
        'created_at',
        'updated_at',
        'status',
        'merchant_name'
    ];

    // If you want Laravel to automatically manage `created_at` and `updated_at` timestamps, you can enable it:
    // public $timestamps = true;

    // If you have different column names for timestamps:
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}

