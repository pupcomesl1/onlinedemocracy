<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPropositionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('propositions', function(Blueprint $table)
		{
			$table->foreign('status', 'fk_propositions_statuses')->references('statusId')->on('statuses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('proposer_id', 'fk_propositions_users')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('propositions', function(Blueprint $table)
		{
			$table->dropForeign('fk_propositions_statuses');
			$table->dropForeign('fk_propositions_users');
		});
	}

}
