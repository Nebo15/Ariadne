<?php namespace App\Models\Best;

use Illuminate\Database\Eloquent\Model;

class AgentPoint extends Model
{
	public $timestamps = false;

	function country()
	{
		return $this->belongsTo('\App\Models\Best\Country');
	}

	function city()
	{
		return $this->belongsTo('\App\Models\Best\City');
	}
}
