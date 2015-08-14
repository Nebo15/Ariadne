<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GeoAccuracy extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('destination_points', function(Blueprint $table)
		{
			$table->enum('geo_accuracy', ['address', 'city'])->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('destination_points', function(Blueprint $table)
		{
			$table->dropColumn('geo_accuracy');
		});
	}

}
