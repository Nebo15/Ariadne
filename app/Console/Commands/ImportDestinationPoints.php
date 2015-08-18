<?php namespace App\Console\Commands;

use App\Importers\Best\DestinationPoints, DB;
use App\Models\Best\DestinationPoint;

class ImportDestinationPoints extends ImportBestDataCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'import:destination-points';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import destination points from BEST';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$path = $this->best->downloadDictionaryToFile('DestinationPlace');
		$importer = new DestinationPoints();
		$count = $importer
			->truncate()
			->importFile($path);
		$importer->swapTempAndMainTables();
		$this->info('Imported '.$count.' destination points');

		DB::table('destination_points')
			->join('agent_points', 'destination_points.point_id', '=', 'agent_points.id')
			->update([
				'destination_points.name' => DB::raw('agent_points.name'),
				'destination_points.address' => DB::raw('agent_points.address'),
				'destination_points.address_crc' => DB::raw('agent_points.address_crc'),
			]);

		DestinationPoint::whereNull('address')->delete();
		DestinationPoint::where('address', '')->delete();
		DestinationPoint::where('address', '.')->delete();
		DestinationPoint::where('address', '-')->delete();
		DestinationPoint::where('name', 'LIKE', '%PayDirect%')->delete();

		//Временно оставляем только unistream
		//DestinationPoint::where('system_id', '<>', 19)->delete();
	}

	protected function importInboundDestinationPoints()
	{
		$destination_points = [
			['city_id' => 398, 'point_id' => 1001],
			['city_id' => 1338, 'point_id' => 10010001],
			['city_id' => 1500, 'point_id' => 10010002],
			['city_id' => 1781, 'point_id' => 10010003],
			['city_id' => 1338, 'point_id' => 10010004],
		];

		foreach($destination_points as $destination_points)
		{
			DestinationPoint::insert([
				'system_id' => 0,
				'country_id' => 643,
				'city_id' => $destination_points['city_id'],
				'point_id' => $destination_points['point_id'],
				'currencies' => 'RUR,USD,EUR',
				'is_published' => 1,
				'direction' => 'inout'
			]);
		}

		return count($destination_points);
	}
}
