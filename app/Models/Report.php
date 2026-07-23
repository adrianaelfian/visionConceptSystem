<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'report_type',
        'start_date',
        'end_date',
        'data_summary'
    ];

    protected $casts = [
        'data_summary' => 'array',
    ];
}
