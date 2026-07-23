<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{

    protected $fillable = [
        'customerName',
        'customerEmail',
        'customerPhoneNumber'
    ];

    public function eyeConditions()
    {
        return $this->hasMany(EyeCondition::class, 'customerID', 'customerID');
    }

    public function orders() {
        return $this->hasMany(Order::class, 'customer_id');
    }
}
