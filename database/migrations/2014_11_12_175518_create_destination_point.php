<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDestinationPoint extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('destination_points', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('country_id');
			$table->unsignedInteger('city_id')->index();
			$table->unsignedInteger('system_id');
			$table->unsignedInteger('point_id')->index();
			$table->string('name', 1024)->nullable();
			$table->string('currencies', 50);
			$table->string('address', 1024)->nullable();
			$table->string('address_crc', 1024)->nullable();

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('destination_points');
	}

}
