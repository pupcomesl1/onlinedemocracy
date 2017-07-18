<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePropositionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('propositions', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('proposer_id')->index('fk_propositions_users_idx');
			$table->string('propositionSort', 140)->nullable();
			$table->text('propositionLong')->nullable();
			$table->dateTime('deadline')->default('0000-00-00 00:00:00');
			$table->integer('status')->default(2)->index('fk_propositions_statuses_idx');
			$table->string('block_reason', 120)->nullable();
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
		Schema::drop('propositions');
	}

}
