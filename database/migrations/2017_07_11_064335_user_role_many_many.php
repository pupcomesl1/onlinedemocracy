<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserRoleManyMany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_user', function (Blueprint $table) {
           $table->integer('role_id');
           $table->integer('user_id');
        });

        // Move the old role system to the new one
        $userRoles = DB::table('users')->select(['id', 'roleid'])->get();
        foreach ($userRoles as $result) {
            DB::table('role_user')->insert([
                'role_id' => $result->roleid,
                'user_id' => $result->id
            ]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('fk_users_roles');
            $table->dropColumn('roleid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        throw new Exception('Impossible');
    }
}
