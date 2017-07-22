<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToLikesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('likes', function(Blueprint $table)
		{
			$table->foreign('comment_id', 'fk_likes_comments')->references('id')->on('comments')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('user_id', 'fk_likes_users')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('likes', function(Blueprint $table)
		{
			$table->dropForeign('fk_likes_comments');
			$table->dropForeign('fk_likes_users');
		});
	}

}
