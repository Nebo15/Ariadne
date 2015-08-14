<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDestinationPointsIsPublished extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('destination_points', function(Blueprint $table)
		{
			$table->boolean('is_published')->nullable();
			$table->float('latitude', 11, 8)->nullable();
			$table->float('longitude', 11, 8)->nullable();
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
			$table->dropColumn('is_published');
			$table->dropColumn('latitude');
			$table->dropColumn('longitude');
		});
	}

}
