<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePropositionsTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('propositions_tags', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('proposition_id')->index('fk_propositions_tags_propositions_idx');
			$table->integer('tag_id')->index('fk_propositions_tags_tags_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('propositions_tags');
	}

}
