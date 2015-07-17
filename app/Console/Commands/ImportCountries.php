<?php namespace App\Console\Commands;

use App\Best, Topor;
use App\Importers\Best\Countries as Importer;

class ImportCountries extends ImportBestDataCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'import:countries';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import countries from BEST';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
	public function fire()
	{
		$path = $this->best->downloadDictionaryToFile('Countries');
		$count = (new Importer())
			->truncate()
			->importFile($path);
		$this->info('Imported '.$count.' countries');
	}
}
