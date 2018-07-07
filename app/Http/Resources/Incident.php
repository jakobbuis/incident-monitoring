<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Incident extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'data' => $this->data,
            'level' => $this->level,
            'website' => Website::make($this->website),
        ];
    }
}
