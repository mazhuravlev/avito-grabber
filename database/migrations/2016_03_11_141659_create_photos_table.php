<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreatePhotosTable extends Migration
{

    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('offer_id');
            $table->foreign('offer_id', 'photo_offer_fk')
                ->references('id')->on('offers');
            $table->text('href');
        });
    }

    public function down()
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->dropForeign('photo_offer_fk');
        });
        Schema::drop('photos');
    }
}
