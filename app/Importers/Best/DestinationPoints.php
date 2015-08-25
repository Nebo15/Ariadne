<?php  namespace App\Importers\Best;

use App\Importers\Best\LaravelTemporaryTableDataImporter;

class DestinationPoints extends LaravelTemporaryTableDataImporter
{
	protected $table = 'destination_points';
	protected $temp_table = 'destination_points_temp';

	function convertRow($row)
	{
		$is_published = (boolean) $row[5];
		return [
			'system_id' => $row[1],
			'country_id' => $row[2],
			'city_id' => $row[3],
			'point_id' => $row[4],
			'currencies' => $row[5],
			'is_published' => $is_published
		];
	}
}
