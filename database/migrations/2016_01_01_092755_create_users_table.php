<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('email', 100)->unique('email_UNIQUE');
			$table->string('contactEmail', 100)->nullable();
			$table->string('firstName', 50);
			$table->string('lastName', 50);
			$table->string('avatar', 100)->nullable();
			$table->integer('facebookId')->nullable();
			$table->integer('googleId')->nullable();
			$table->string('password', 100)->nullable();
			$table->string('remember_token', 100)->nullable()->unique('remember_token_UNIQUE');
			$table->integer('gradeLevel')->nullable();
			$table->integer('roleId')->default(1)->index('fk_users_roles_idx');
			$table->string('languageCode', 2)->default('en');
			$table->timestamps();
			$table->string('googleEmail', 100)->nullable()->unique('googleEmail_UNIQUE');
			$table->integer('belongsToSchool')->nullable()->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
