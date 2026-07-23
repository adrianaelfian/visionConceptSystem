<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'stock',
        'price', // Pastikan anda tambah 'price' dalam migration jika belum ada
    ];

    // Hubungan: Satu Item boleh berada dalam banyak OrderItem
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
