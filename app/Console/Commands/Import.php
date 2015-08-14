<?php namespace App\Console\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class Import extends LoggableCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'import';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import data from BEST';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->call('import:countries');
		$this->call('import:cities');
		$this->call('import:agent-points');
		$this->call('import:destination-points');
		$this->call('geo:code');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [];
	}

}
