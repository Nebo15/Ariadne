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
		$importer = new Cities();
		$count = $importer
			->truncate()
			->importFile($path);
		$importer->swapTempAndMainTables();
		$this->info('Imported '.$count.' places');
	}
}
