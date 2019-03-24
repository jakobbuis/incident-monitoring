<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Website extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at', 'monitoring_suspended'];

    protected $fillable = ['name', 'url', 'monitoring_suspended'];

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    public function scopeMonitored(Builder $query) : Builder
    {
        return $query->where('monitoring_suspended', null);
    }

    public function scopeSuspended(Builder $query) : Builder
    {
        return $query->whereNotNull('monitoring_suspended');
    }
}
