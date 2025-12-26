<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
      protected $fillable = [
        'order_id',
        'product_id',
        'address_id',
        'product_name',
        'price',
        'amount',
        'total',
    ];

    // 🔗 Detail milik satu order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // 🔗 Detail mengacu pada satu produk
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
