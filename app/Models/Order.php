<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'total_price',
        'amount_paid',
        'remaining_balance',
        'payment_status',
        'order_status',
        'orderDate'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class,'order_id');
    }

    public function getRemainingBalanceAttribute()
    {
        return $this->total_price - $this->amount_paid;
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
