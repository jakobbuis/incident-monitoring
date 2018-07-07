<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $fillable = ['type', 'data', 'resolved_at', 'level', 'website_id'];
    protected $casts = ['data' => 'array'];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function scopeUnresolved(Builder $query) : Builder
    {
        return $query->whereNull('resolved_at');
    }

    public function scopeOrdered(Builder $query) : Builder
    {
        return $query->orderBy('level', 'asc')->orderBy('created_at', 'desc');
    }
}
