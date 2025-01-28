<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regions extends Model
{
    protected $table = 'regions';
    protected $primaryKey = "id";

    public function Continent() {
	    return $this->belongsTo('App\Models\Continents', 'continent_id', 'id');
	}
}
