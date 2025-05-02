<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PgParameter extends Model
{
    protected $table = 'pg_parameter'; 

    protected $fillable = [
        'deposite_serviceId',
        'withdraw_serviceId',
        'merchantCode',
    ];

    public $timestamps = false;
}
