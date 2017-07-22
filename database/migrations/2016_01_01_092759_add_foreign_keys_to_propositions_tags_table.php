<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPropositionsTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('propositions_tags', function(Blueprint $table)
		{
			$table->foreign('proposition_id', 'fk_propositions_tags_propositions')->references('id')->on('propositions')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('tag_id', 'fk_propositions_tags_tags')->references('id')->on('tags')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('propositions_tags', function(Blueprint $table)
		{
			$table->dropForeign('fk_propositions_tags_propositions');
			$table->dropForeign('fk_propositions_tags_tags');
		});
	}

}
