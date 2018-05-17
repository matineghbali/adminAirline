<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->increments('id');
            $table->string('DepartureAirport');
            $table->string('ArrivalAirport');
            $table->string('DepartureDateTime');
            $table->string('ArrivalDateTime');
            $table->string('AvailableSeatQuantity');
            $table->string('FlightNumber');
            $table->string('FareBasisCode');
            $table->string('MarketingAirline');
            $table->string('cabinType');
            $table->string('AirEquipType');
            $table->string('price');
            $table->string('ADTPrice');
            $table->string('CHDPrice');
            $table->string('INFPrice');
//            $table->string('passengerNumber');
//            $table->string('ADTNumber');
//            $table->string('CHDNumber');
//            $table->string('INFNumber');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flights');
    }
}
