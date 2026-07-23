<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentLog extends Model
{
    protected $fillable = ['appointmentID', 'action', 'details'];
}
