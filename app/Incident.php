<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $fillable = ['type', 'data', 'resolved_at'];
    protected $casts = ['data' => 'array'];
}
