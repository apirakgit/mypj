<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBrandToVinnomasters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vin_no_masters', function (Blueprint $table) {
            $table->unsignedBigInteger('brand_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vin_no_masters', function (Blueprint $table) {
            $table->dropColumn('brand_id');
        });
    }
}
