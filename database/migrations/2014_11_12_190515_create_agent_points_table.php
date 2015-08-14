<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentPointsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('agent_points', function(Blueprint $table)
		{
			$table->unsignedInteger('id')->index();
			$table->unsignedInteger('country_id');
			$table->unsignedInteger('city_id');
			$table->string('name', 1024);
			$table->string('currencies', 50);
			$table->string('address', 1024);
			$table->string('address_crc', 1024);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('agent_points');
	}

}
