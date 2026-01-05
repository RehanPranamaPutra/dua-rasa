<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'address_id',
        'invoice_number',
        'total_price',
        'shipping_service',
        'shipping_cost',
        'order_status',
        'payment_status'
    ];

     // 🔗 Setiap order dimiliki oleh 1 customer
    public function customer()
    {
        return $this->belongsTo(UserCustomer::class, 'customer_id');
    }

    // 🔗 Setiap order punya 1 alamat pengiriman
    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    // 🔗 1 order punya 1 pembayaran
    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id');
    }

     // 🔗 Order punya banyak detail
    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }
}
