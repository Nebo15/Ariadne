<?php namespace App\Importers\Best;

use App\Importers\Best\LaravelTemporaryTableDataImporter;

class AgentPoints extends LaravelTemporaryTableDataImporter
{
	protected $table = 'agent_points';
	protected $temp_table = 'agent_points_temp';

	function convertRow($row)
	{
//		if(!$row[6])
//			return null;
//
//		if(2 > strlen($row[6]))
//			return null;
//
//		if(false !== strpos($row[4], 'PayDirect'))
//			return null;

		return [
			'id' => $row[1],
			'country_id' => $row[2],
			'city_id' => $row[3],
			'name' => trim($row[4]),
			'currencies' => '',
			'address' => $row[5],
			'address_crc' => crc32($row[5])
		];
	}
}
