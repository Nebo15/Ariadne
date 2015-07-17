<?php  namespace App\Importers\Best;

use Topor\Best\LaravelDataImporter;

class Countries extends LaravelDataImporter
{
	function getTableName()
	{
		return 'countries';
	}

	function convertRow($row)
	{
		return [
			'id' => $row[1],
			'name' => trim($row[2]),
			'is_send_authorized' => $row[3]
		];
	}
}
