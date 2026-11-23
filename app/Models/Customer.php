<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'user_id', // 🛠️ DITAMBAH: Wajib diisi supaya pautan ke jadual users disimpan
        'customerName',
        'customerEmail',
        'customerPhoneNumber'
    ];

    /**
     * Hubungan dengan Model User (Satu profil Pelanggan milik satu User)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Hubungan dengan Keadaan Mata / Preskripsi
     */
    public function eyeConditions()
    {
        return $this->hasMany(EyeCondition::class, 'customer_id', 'id');
    }

    /**
     * Hubungan dengan Pesanan / Invois
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function getFormattedIdAttribute()
{
    return 'CUST-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
}

}
