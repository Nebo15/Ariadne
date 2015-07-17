<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use GuzzleHttp\Client;
use GuzzleHttp\Stream\Stream;
use Topor\Topor;

abstract class ImportBestDataCommand extends LoggableCommand
{
    /**
     * @var Topor
     */
	protected $topor;
    /**
     * @var \Topor\Best
     */
    protected $best;

	function __construct(Topor $topor)
	{
		$this->topor = $topor;
        $this->best = $this->topor->best();
		parent::__construct();
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
