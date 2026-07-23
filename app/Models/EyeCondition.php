<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EyeCondition extends Model
{
    use HasFactory;

    // Pastikan ini sama dengan nama primary key di phpMyAdmin
    protected $table = 'eye_conditions';

    // Senaraikan semua column yang anda buat dalam SQL tadi
    protected $fillable = [
        'appointmentID','salespersonID', 'rightPower', 'leftPower',
        'rightCylinder', 'leftCylinder', 'rightAxis',
        'leftAxis', 'pupilDistance'
    ];

    // Hubungan dengan model Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customerID', 'customerID');
    }

    // Tambah ini ke dalam class EyeCondition
    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointmentID', 'appointmentID');
    }
}
