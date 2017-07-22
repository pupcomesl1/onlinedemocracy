<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToVotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('votes', function(Blueprint $table)
		{
			$table->foreign('proposition_id', 'fk_downvotes_propositions')->references('id')->on('propositions')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('vote_user', 'fk_downvotes_users')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('votes', function(Blueprint $table)
		{
			$table->dropForeign('fk_downvotes_propositions');
			$table->dropForeign('fk_downvotes_users');
		});
	}

}
