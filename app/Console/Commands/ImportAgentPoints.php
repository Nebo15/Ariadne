<?php namespace App\Console\Commands;

use App\Importers\Best\AgentPoints, DB;
use App\Models\Best\AgentPoint;

class ImportAgentPoints extends ImportBestDataCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'import:agents';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import agent points from BEST';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$path = $this->best->downloadDictionaryToFile('DestinationPlace');
		$count = (new AgentPoints($path))
			->truncate()
			->importFile($path);
		$this->info('Imported '.$count.' agent points');

		DB::table('agent_points')
			->join('points', 'agent_points.point_id', '=', 'points.id')
			->update([
				'agent_points.name' => DB::raw('points.name'),
				'agent_points.address' => DB::raw('points.address'),
				'agent_points.address_crc' => DB::raw('points.address_crc'),
			]);

		AgentPoint::whereNull('address')->delete();
		AgentPoint::where('address', '')->delete();
		AgentPoint::where('address', '.')->delete();
		AgentPoint::where('address', '-')->delete();
		AgentPoint::where('name', 'LIKE', '%PayDirect%')->delete();

		//Временно оставляем только unistream
		//AgentPoint::where('system_id', '<>', 19)->delete();
	}

	protected function importInboundAgentPoints()
	{
		$agent_points = [
			['city_id' => 398, 'point_id' => 1001],
			['city_id' => 1338, 'point_id' => 10010001],
			['city_id' => 1500, 'point_id' => 10010002],
			['city_id' => 1781, 'point_id' => 10010003],
			['city_id' => 1338, 'point_id' => 10010004],
		];

		foreach($agent_points as $agent_point)
		{
			AgentPoint::insert([
				'system_id' => 0,
				'country_id' => 643,
				'city_id' => $agent_point['city_id'],
				'point_id' => $agent_point['point_id'],
				'currencies' => 'RUR,USD,EUR',
				'is_published' => 1,
				'direction' => 'inout'
			]);
		}

		return count($agent_points);
	}
}
