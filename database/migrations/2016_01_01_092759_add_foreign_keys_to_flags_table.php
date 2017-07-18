<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToFlagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('flags', function(Blueprint $table)
		{
			$table->foreign('type', 'fk_flags_flag_categories')->references('id')->on('flag_categories')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('proposition', 'fk_flags_propositions')->references('id')->on('propositions')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('flags', function(Blueprint $table)
		{
			$table->dropForeign('fk_flags_flag_categories');
			$table->dropForeign('fk_flags_propositions');
		});
	}

}
