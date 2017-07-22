<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('votes', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('vote_value');
			$table->integer('proposition_id')->index('fk_downvotes_propositions1_idx');
			$table->integer('vote_user')->index('fk_downvotes_users_idx');
			$table->timestamps();
			$table->string('vote_school_email', 50);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('votes');
	}

}
