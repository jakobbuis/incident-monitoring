<?php

namespace App\Http\Controllers;

use App\Http\Resources\Incident as IncidentResource;
use App\Incident;
use Illuminate\Http\Request;

class IncidentsController extends Controller
{
    public function index()
    {
        $ongoing = Incident::unresolved()->ordered()->get();
        return IncidentResource::collection($ongoing);
    }
}
