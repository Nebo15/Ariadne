<?php namespace App\Console\Commands;

use Log;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class LoggableCommand extends Command
{
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$time = microtime(true);
		$this->comment('Started '.$this->name);
		$result = $this->fire();
		$delta = $this->formatTimeInterval(microtime(true) - $time);

		$collected_cycles_count = gc_collect_cycles();
		$this->comment(
			'Ended '.$this->name.' / '.$delta.'s / '.
			$this->getMemoryUsage().' / '.$collected_cycles_count.' cycles'
		);
		return $result;
	}

	protected function formatTimeInterval($delta)
	{
		return number_format($delta, 2);
	}


	protected function getMemoryUsage()
	{
//		$pid = getmypid();
//		$output = @shell_exec("ps -o rss -p $pid");
//		if(!$output)
//			return '-';
//		$output = str_replace('RSS', '', $output);
//
//		$size = trim((int) $output) *1024;
		$size = memory_get_peak_usage(true);
		$unit = array('b','kb','mb','gb','tb','pb');
		return @round($size/pow(1024,($i=floor(log($size,1024))))).$unit[$i];
	}

	public function comment($string)
	{
		parent::comment($string);
		Log::info($string, [get_called_class()]);
	}

	public function warning($string)
	{
		parent::error($string);
		Log::warning($string, [get_called_class()]);
	}


	public function error($string)
	{
		parent::error($string);
		Log::error($string, [get_called_class()]);
        return null;
	}

	function exec($cmd)
	{
		$this->comment('Exec: '.$cmd);
		return exec($cmd);
	}
}
