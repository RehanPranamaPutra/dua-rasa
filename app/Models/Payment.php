<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'method',
        'transaction_code',
        'amount',
        'payment_status',
        'snap_token',
        'payment_time',
    ];

    // 🔗 Pembayaran milik satu order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
