<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CountryValueForGeoAccuracy extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('destination_points', function(Blueprint $table)
		{
			DB::statement("ALTER TABLE destination_points MODIFY COLUMN geo_accuracy ENUM('country','city','address')");
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
			DB::statement("ALTER TABLE destination_points MODIFY COLUMN geo_accuracy ENUM('city','address')");
		});
	}

}
