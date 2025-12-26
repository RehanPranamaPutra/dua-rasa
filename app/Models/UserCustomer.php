<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserCustomer extends Authenticatable
{
    use Notifiable;

    protected $table = 'user_customers';

    protected $fillable = [
        'name',
        'email',
        'password',
        'no_hp',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // 🔗 Customer punya banyak alamat
    public function addresses()
    {
        return $this->hasMany(Address::class, 'customer_id');
    }

    // 🔗 Alamat terakhir
    public function latestAddress()
    {
        return $this->hasOne(Address::class, 'customer_id')->latestOfMany();
    }

    // 🔗 Customer punya banyak order
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    // 🔗 Customer punya banyak payment melalui order
    public function payments()
    {
        return $this->hasManyThrough(
            Payment::class,
            Order::class,
            'customer_id',  // FK di orders
            'order_id',     // FK di payments
            'id',           // PK UserCustomer
            'id'            // PK Order
        );
    }
}
