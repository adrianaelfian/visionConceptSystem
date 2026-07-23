<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $primaryKey = 'appointmentID';

    protected $fillable = [
        'customerID',
        'appointmentDate',
        'appointmentTime',
        'status'
    ];

    public function customer(){
        return $this->belongsTo(Customer::class, 'customerID');    }
}
