<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = ['category_id', 'name', 'description', 'price', 'stok', 'weight', 'status'];

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    // 🔗 Produk bisa muncul di banyak order detail
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'product_id');
    }
}
