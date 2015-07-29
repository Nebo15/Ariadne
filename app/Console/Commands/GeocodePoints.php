<?php namespace App\Console\Commands;

use App\Models\Best\AgentPoint;
use Geocoder\Exception\ChainNoResultException;
use Geocoder\Geocoder;
use Illuminate\Support\Facades\DB;

class GeocodePoints extends LoggableCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'geo:code';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * @var Geocoder
	 */
	protected $geocoder;

	public function __construct(Geocoder $geocoder)
	{
		$this->geocoder = $geocoder;
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$points = AgentPoint::with(['city', 'country'])
			->whereNull('agent_points.latitude')
			->orderBy('id')
			->get();

		$count = 0;

		foreach($points as $point)
		{
			$count++;
			if(0 == $count % 1000)
			{
				$unprocessed_points = AgentPoint::with(['city', 'country'])
						->whereNull('agent_points.latitude')
						->count();

				$collected_cycles_count = gc_collect_cycles();
				$this->comment($unprocessed_points.' unprocessed agent points ('.$this->getMemoryUsage().' / '.$collected_cycles_count.' cycles)');
			}

			if(!$country = $point->country()->first())
				return $this->error("Skipped point #{$point->id}: country not found");

			if(!$city = $point->city()->first())
				return $this->error("Skipped point #{$point->id}: city not found");

			$address = $point->address;
			if(false === mb_strpos($address, $city->name))
				$address = $city->name.', '.$address;
			if(false === mb_strpos($address, $country->name))
				$address = $country->name.', '.$address;

			try
			{
				if($point = $this->geocode($point, $address))
					$point->save();
			}
			catch (ChainNoResultException $e)
			{
				$this->error("Not founded {$address}");
			}
		}
		return;
	}

	protected function tryToGetAddressFromGeocode($address, $attempts = 0) {
		if ($attempts >=3) {
			return;
		}
		$result = null;
		/**
		 * @var \Geocoder\Model\Address
		 */
		try {
			$result = $this->geocoder->geocode($address)->first();
			return $result;
		} catch(\Geocoder\Exception\NoResult $no_result) {
			$this->writeString('x');
			return;
		} catch(\ErrorException $response_error) {
			$this->tryToGetAddressFromGeocode($address,++$attempts);
		}

		return $result;
	}

	/**
	 * @param $address
	 * @return Geocoded
	 */
	protected function geocode($point, $address)
	{
		if($value = $this->getFromCache($address))
		{
			$point->latitude = $value->latitude;
			$point->longitude = $value->longitude;
			$point->geo_accuracy = 'address';
			return $point;
		}

		$result = $this->tryToGetAddressFromGeocode($address);
		if (!$result) {
			return;
		}

		$accuracy = $result->getStreetName() ? 'address' : 'city';
		$this->addToCache($address, $result->getLatitude(), $result->getLongitude());

		if('address' == $accuracy)
			$this->writeString('.');
		else
			$this->writeString('O');

		$point->latitude = $result->getLatitude();
		$point->longitude = $result->getLongitude();
		$point->geo_accuracy = $accuracy;
		return $point;
	}

	protected function getFromCache($address)
	{
		return DB::table('geocode_cache')
			->where('address_crc', crc32($address))
			->first();
	}

	protected function addToCache($address, $latitude, $longitude)
	{
		DB::table('geocode_cache')->insert([
			'address' => $address,
			'address_crc' => crc32($address),
			'latitude' => $latitude,
			'longitude' => $longitude,
		]);
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

	protected function writeString($message)
	{
		$this->output->write($message);
	}

}
