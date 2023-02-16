<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('f_name');
            $table->string('l_name');
            $table->date('birthday')->nullable();
            $table->string('sex')->nullable();
            $table->string('tel');
            $table->string('email')->unique();
            $table->text('address')->nullable();
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->string('sub_district')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('car_brand');
            $table->string('vin_no')->nullable();
            $table->string('virta_id')->nullable();
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
        Schema::dropIfExists('customers');
    }
}
