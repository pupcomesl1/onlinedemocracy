<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToMarkerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('marker', function(Blueprint $table)
		{
			$table->foreign('marker_id', 'fk_marker_markers')->references('id')->on('markers')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('proposition_id', 'fk_marker_propositions')->references('id')->on('propositions')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('marker', function(Blueprint $table)
		{
			$table->dropForeign('fk_marker_markers');
			$table->dropForeign('fk_marker_propositions');
		});
	}

}
