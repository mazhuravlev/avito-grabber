<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->text('title');
            $table->text('description');
            $table->string('price_string');
            $table->string('cat_path');
            $table->string('address');
            $table->string('user_address')->nullable();
            $table->text('details')->nullable();
            $table->timestamps();
            $table->unsignedInteger('grabbed_link_id');
            $table->foreign('grabbed_link_id', 'grabbed_link_fk')
                ->references('id')
                ->on('grabbed_links');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropForeign('grabbed_link_fk');
        });
        Schema::drop('offers');
    }
}
