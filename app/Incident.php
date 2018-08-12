<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    const LEVEL_CRITICAL = 1;
    const LEVEL_IMPORTANT = 2;
    const LEVEL_SMALL = 3;
    const LEVEL_NOIMPACT = 4;
    const LEVELS = [1, 2, 3, 4];

    protected $fillable = ['type', 'data', 'resolved_at', 'level', 'website_id'];
    protected $casts = ['data' => 'array', 'resolved_at' => 'datetime'];

    protected $dispatchesEvents = [
        'created' => \App\Events\IncidentStarted::class,
    ];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    public function resolve()
    {
        $this->resolved_at = Carbon::now();
        $this->save();
    }

    public function scopeOngoing(Builder $query) : Builder
    {
        return $query->whereNull('resolved_at');
    }

    public function scopeOrdered(Builder $query) : Builder
    {
        return $query->orderBy('resolved_at', 'asc')->orderBy('level', 'asc')->orderBy('created_at', 'desc');
    }
}
