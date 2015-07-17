<?php namespace App\Importers\Best;

use Topor\Best\LaravelDataImporter;

class Points extends LaravelDataImporter
{
	function getTableName()
	{
		return 'points';
	}

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
