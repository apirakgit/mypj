<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->integer('amount')->default(0);
            $table->integer('status')->default(1);
            $table->integer('delete')->default(0);
            $table->unsignedBigInteger('promotion_campaign_id');
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
        Schema::dropIfExists('promotion_codes');
    }
}
