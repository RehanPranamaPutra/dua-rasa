<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'customer_name',
        'no_telp',
        'province',
        'city',
        'subdistrict',
        'postal_code',
        'specific_address',
    ];

    public function customer()
    {
        return $this->belongsTo(UserCustomer::class, 'customer_id');
    }
     public function orders()
    {
        return $this->hasMany(Order::class, 'address_id');
    }
}
