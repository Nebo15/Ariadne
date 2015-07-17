<?php namespace App\Console\Commands;

use App\Best;
use App\Importers\Best\Cities;

class ImportCities extends ImportBestDataCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'import:cities';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import cities from BEST';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$path = $this->best->downloadDictionaryToFile('Places');
		$count = (new Cities())
			->truncate()
			->importFile($path);
		$this->info('Imported '.$count.' places');
	}
}
