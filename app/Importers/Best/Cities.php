<?php namespace App\Importers\Best;

use App\Importers\Best\LaravelTemporaryTableDataImporter;

class Cities extends LaravelTemporaryTableDataImporter
{
	protected $table = 'cities';
	protected $temp_table = 'cities_temp';

	function convertRow($row)
	{
		return [
			'country_id' => $row[1],
			'id' => $row[2],
			'name' => trim($row[3]),
		];
	}
}
