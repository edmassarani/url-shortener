<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    protected $fillable = [
        'short_code',
        'long_url',
        'expires_at',
        'visit_count',
    ];
}
