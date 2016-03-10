<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateOfferPhoneTable extends Migration
{
    public function up()
    {
        Schema::create('offer_phone', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('offer_id');
            $table->unsignedBigInteger('phone_id');
            $table->unique(['offer_id', 'phone_id'], 'offer_phone_uq');
            $table->foreign('offer_id', 'offer_phone_offer_fk')->references('id')->on('offers');
            $table->foreign('phone_id', 'offer_phone_phone_fk')->references('id')->on('phones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offer_phone', function (Blueprint $table) {
            $table->dropForeign('offer_phone_offer_fk');
            $table->dropForeign('offer_phone_phone_fk');
        });
        Schema::drop('offer_phone');
    }
}
