<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'beneficiary_name', 'account_number', 'ifsc_code',
        'bank_name', 'branch_name', 'name', 'upi_id', 'is_active', 'type','is_deleted','qr_code'
    ];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
