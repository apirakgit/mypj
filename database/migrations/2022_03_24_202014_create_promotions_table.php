<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id');
            $table->integer('discount')->default(0);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('user_create');
            $table->string('user_last_update')->nullable();
            $table->integer('status');
            $table->timestamps();

            $table->foreign('vehicle_id')->references('id')->on('vehicle_brands');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotions');
    }
}
