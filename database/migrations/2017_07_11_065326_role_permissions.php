<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RolePermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
           $table->boolean('can_vote')->default(false);
           $table->boolean('can_comment')->default(false);
           $table->boolean('can_edit_own_comments')->default(false);
           $table->boolean('can_delete_own_comments')->default(false);
           $table->boolean('can_post_propositions')->default(false);
           $table->boolean('can_delete_own_propositions')->default(false);

           $table->boolean('can_approve_or_block_propositions')->default(false);
           $table->boolean('can_delete_comments')->default(false);
           $table->boolean('can_set_proposition_markers')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
