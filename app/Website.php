<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $fillable = ['name', 'url'];

    public function startIncident(string $type, int $level = Incident::LEVEL_NOIMPACT, $data = null)
    {
        if (!in_array($level, Incident::LEVELS)) {
            throw new \DomainException('Invalid incident level');
        }

        $this->incidents()->firstOrCreate(compact('type'), compact('level', 'data'));
    }

    public function resolveIncident(string $type)
    {
        $incident = $this->incidents()->where('type', $type)->first();
        if ($incident) {
            $incident->resolve();
        }
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }
}
