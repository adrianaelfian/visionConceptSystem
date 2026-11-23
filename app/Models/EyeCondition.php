<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EyeCondition extends Model
{
    use HasFactory;

    protected $table = 'eye_conditions';

    // Tetapkan Primary Key kerana pangkalan data anda menggunakan 'conditionID'
    protected $primaryKey = 'conditionID';

    // Jika conditionID jenis auto-increment integer (biasanya ya):
    public $incrementing = true;
    protected $keyType = 'int';

    // Tambah 'customer_id' ke dalam senarai fillable
    protected $fillable = [
        'customer_id',
        'appointmentID',
        'salespersonID',
        'rightPower',
        'leftPower',
        'rightCylinder',
        'leftCylinder',
        'rightAxis',
        'leftAxis',
        'pupilDistance'
    ];

    // Hubungan dengan model Customer menggunakan kolum customer_id
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    // Hubungan dengan model Appointment
    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointmentID', 'appointmentID');
    }
}
