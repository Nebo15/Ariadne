<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GeocodeCache extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('geocode_cache', function(Blueprint $table)
		{
			$table->unsignedInteger('address_crc')->index();
			$table->string('address');
			$table->float('latitude', 11, 8);
			$table->float('longitude', 11, 8);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('geocode_cache');
	}

}
