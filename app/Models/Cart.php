<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'shooping_carts';

    protected $fillable = [
        'customer_id',
        'product_id',
        'amount',
        'total',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function userCustomer()
    {
        return $this->belongsTo(UserCustomer::class, 'customer_id');
    }
}
