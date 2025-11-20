<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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

     public function addresses()
    {
        return $this->hasMany(Address::class, 'customer_id');
    }

    /**
     * Relasi untuk ambil alamat terakhir (opsional)
     */
    public function latestAddress()
    {
        return $this->hasOne(Address::class, 'customer_id')->latestOfMany();
    }

     /** @return HasManyThrough<Payment, Order, $this> */
    public function payments(): HasManyThrough
    {
        return $this->hasManyThrough(Payment::class, Order::class, 'customer_id' );
    }
}
