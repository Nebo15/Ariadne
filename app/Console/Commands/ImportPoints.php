<?php namespace App\Console\Commands;

use App\Best;
use App\Importers\Best\Points;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use GuzzleHttp\Client;
use GuzzleHttp\Stream\Stream;


class ImportPoints extends ImportBestDataCommand
{
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'import:points';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import points from BEST';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$is_last_chunk = false;
		$current_chunk = $this->argument('chunk') ?: 0;
		$count = 0;
		$importer = new Points();

		if(!$current_chunk)
			$importer->truncate();

		while(!$is_last_chunk)
		{
			$response = $this->best->requestDictionary('AgentsPoints', $current_chunk);
			$tmp_count = $importer->importString($response->getBody()->__toString());
			$count += $tmp_count;
			$current_chunk = $importer->current_best_chunk;
			$is_last_chunk = $importer->is_last_best_chunk;
			$collected_cycles_count = gc_collect_cycles();
			$this->info('Imported chunk #'.$current_chunk.' with '.$tmp_count.' points [cycles: '.$collected_cycles_count.']');
		}

		$this->info('Imported '.$count.' points');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			[
				'chunk',
				InputArgument::OPTIONAL,
				'Start from chunk number',
				0
			]
		];
	}
}
