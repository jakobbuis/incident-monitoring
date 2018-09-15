<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $fillable = ['name', 'url'];

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }
}
