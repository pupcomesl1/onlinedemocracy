<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMarkerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('marker', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('proposition_id')->index('fk_marker_propositions_idx');
			$table->integer('marker_id')->index('fk_marker_markers_idx');
			$table->string('marker_text', 240)->nullable();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('marker');
	}

}
