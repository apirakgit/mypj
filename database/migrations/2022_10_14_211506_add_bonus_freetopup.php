<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBonusFreetopup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('bonus')->default(0);
            $table->integer('free_topup')->default(0);
            $table->string('promotion_name')->nullable();
            $table->string('promotion_code')->nullable();
            $table->integer('promotion_code_list_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('bonus');
            $table->dropColumn('free_topup');
            $table->dropColumn('promotion_name');
            $table->dropColumn('promotion_code');
            $table->dropColumn('promotion_code_list_id');
        });
    }
}
