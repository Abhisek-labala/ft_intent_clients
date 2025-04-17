<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'email_verified_at',
        'remember_token',
        'username',
        'phone',
        'address',
        'gender',
        'dob',
        'country',
        'state',
        'pannumber',
        'pancardimage',
        'aadharnumber',
        'aadharimage',
        'payin_limit',
        'payout_limit',
        'client_id',
        'client_secret',
        'client_settlement_perc',
        'client_netbanking_dep_perc'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function account()
    {
        return $this->hasOne(Account::class);
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'dob' => 'date:Y-m-d', 
        'password' => 'hashed',
    ];
}
