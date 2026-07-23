<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'item_id',
        'quantity',
        'price',
    ];

    // Hubungan: Setiap OrderItem kepunyaan satu Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Hubungan: Setiap OrderItem merujuk kepada satu Item
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
