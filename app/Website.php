<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Website extends Model
{
    use CrudTrait;

    protected $fillable = ['name', 'url'];

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }
}
