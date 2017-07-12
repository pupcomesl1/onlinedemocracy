<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CommentDeletedAndDistinguished extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->boolean('can_distinguish')->default(false);
        });

        Schema::table('comments', function (Blueprint $table) {
           $table->integer('deleted_by')->nullable();
           $table->string('distinguish')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('can_distinguish');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('deleted_by');
            $table->dropColumn('distinguish');
        });
    }
}
