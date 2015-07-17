<?php namespace App\Importers\Best;

use Topor\Best\LaravelDataImporter;

class Cities extends LaravelDataImporter
{
	function getTableName()
	{
		return 'cities';
	}

	function convertRow($row)
	{
		return [
			'country_id' => $row[1],
			'id' => $row[2],
			'name' => trim($row[3]),
		];
	}
}
