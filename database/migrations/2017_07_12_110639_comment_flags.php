<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CommentFlags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_flags', function (Blueprint $table) {
            $table->increments('id', true);
            $table->integer('comment_id');
            $table->integer('flagger');
            $table->boolean('dismissed')->default(false);
            $table->timestamps();
        });
        Schema::table('comment_flags', function (Blueprint $table) {
            $table->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade');
            $table->foreign('flagger')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment_flags');
    }
}
