<?php namespace App\Models\Best;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
	protected $guarded = [];

	function agentPoints()
	{
		return $this->hasMany('AgentPoint');
	}
}
