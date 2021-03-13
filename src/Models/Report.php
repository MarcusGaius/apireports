<?php

namespace MarcusGaius\ApiReports\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'content',
        'headers'
    ];
    protected $casts = [
        'headers' => 'array',
        'content' => 'array'
    ];
}
