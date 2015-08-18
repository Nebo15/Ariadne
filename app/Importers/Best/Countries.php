<?php  namespace App\Importers\Best;

use App\Importers\Best\LaravelTemporaryTableDataImporter;

class Countries extends LaravelTemporaryTableDataImporter
{
	protected $table = 'countries';
	protected $temp_table = 'countries_temp';

	function convertRow($row)
	{
		return [
			'id' => $row[1],
			'name' => trim($row[2]),
			'is_send_authorized' => $row[3]
		];
	}
}
