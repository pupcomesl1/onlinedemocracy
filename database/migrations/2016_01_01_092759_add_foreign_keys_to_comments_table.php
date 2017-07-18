<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('comments', function(Blueprint $table)
		{
			$table->foreign('proposition_id', 'fk_comments_propositions')->references('id')->on('propositions')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('commenter_id', 'fk_comments_users')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('comments', function(Blueprint $table)
		{
			$table->dropForeign('fk_comments_propositions');
			$table->dropForeign('fk_comments_users');
		});
	}

}
