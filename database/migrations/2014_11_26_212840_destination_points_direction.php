<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DestinationPointsDirection extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('destination_points', function(Blueprint $table)
		{
			$table->enum('direction', ['in', 'out', 'inout']);
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
			$table->dropColumn('direction');
		});
	}

}
