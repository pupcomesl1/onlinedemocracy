<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PropPageViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('propositions', function (Blueprint $table) {
            $table->unsignedInteger('views');
        });

        Schema::create('unique_page_views', function (Blueprint $table) {
           $table->increments('id');
           $table->integer('proposition_id');
           $table->ipAddress('ip');
           $table->string('referrer');
           $table->timestamps();

           $table->foreign('proposition_id')->references('id')->on('propositions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('propositions', function (Blueprint $table) {
            $table->dropColumn('views');
        });

        Schema::dropIfExists('unique_page_views');
    }
}
