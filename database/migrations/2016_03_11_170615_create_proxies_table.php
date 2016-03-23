<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateProxiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proxies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip');
            $table->smallInteger('port');
            $table->string('login')->nullablle();
            $table->string('password')->nullablle();
            $table->unsignedInteger('hits')->default(0);
            $table->unsignedInteger('fails')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('proxies');
    }
}
