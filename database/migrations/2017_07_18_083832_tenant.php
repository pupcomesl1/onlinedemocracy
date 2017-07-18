<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Tenant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenants', function(Blueprint $table) {
            $table->increments('id');
            $table->string('long_name');
            $table->string('prefix');
        });

        Schema::table('users', function(Blueprint $table) {
            $table->unsignedInteger('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants');
        });
        Schema::table('propositions', function (Blueprint $table) {
            $table->unsignedInteger('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants');
        });
        Schema::table('comments', function (Blueprint $table) {
            $table->unsignedInteger('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants');
        });
        Schema::table('flags', function (Blueprint $table) {
            $table->unsignedInteger('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants');
        });
        Schema::table('comment_flags', function (Blueprint $table) {
            $table->unsignedInteger('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants');
        });
        Schema::table('likes', function (Blueprint $table) {
            $table->unsignedInteger('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants');
        });
        Schema::table('votes', function (Blueprint $table) {
            $table->unsignedInteger('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants');
        });
        Schema::table('marker', function (Blueprint $table) {
            $table->unsignedInteger('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants');
        });
        Schema::table('roles', function (Blueprint $table) {
            $table->unsignedInteger('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants');
        });
        Schema::table('tags', function (Blueprint $table) {
            $table->unsignedInteger('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants');
        });
        Schema::table('propositions_tags', function (Blueprint $table) {
            $table->unsignedInteger('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants');
        });
        Schema::table('password_resets', function (Blueprint $table) {
            $table->unsignedInteger('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants');
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
