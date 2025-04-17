<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientAmountDetail extends Model
{
    // Specify the table name if it's different from the pluralized form of the model name
    protected $table = 'client_amount_details';

    // Disable timestamps if your table doesn't have `created_at` and `updated_at` columns
    public $timestamps = false;

    // Specify the primary key column if it's not 'id'
    protected $primaryKey = 'id';

    // Specify the fields that can be mass assigned
    protected $fillable = [
        'total_deposit_amount',
        'total_available_amount',
        'total_withdraw_amount',
        'merchant_name',
        'transaction_fee_deposite',
        'transaction_fee_withdraw',
        'tota_amt_to_withdraw',
        'client_settlement_fees'
    ];

    // Specify the fields that should be cast to a specific type
    protected $casts = [
        'total_deposit_amount' => 'decimal:2',
        'total_available_amount' => 'decimal:2',
        'total_withdraw_amount' => 'decimal:2',
        'transaction_fee_deposite' =>'decimal:2',
    ];
}
