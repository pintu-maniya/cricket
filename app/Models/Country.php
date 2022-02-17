<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'short_code',
        'code',
        'name',
        'official_name',
        'is_region',
        'status',
    ];

}
