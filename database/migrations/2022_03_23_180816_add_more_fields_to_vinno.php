<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreFieldsToVinno extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vin_no_masters', function (Blueprint $table) {
            $table->dateTime('import_time')->nullable();
            $table->dateTime('bound_time')->nullable();
            $table->string('admin')->nullable();
            $table->string('user_bound')->nullable();
            $table->integer('action')->nullable();
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
            $table->dropColumn('import_time');
            $table->dropColumn('bound_time');
            $table->dropColumn('admin');
            $table->dropColumn('user_bound');
            $table->dropColumn('action');
        });
    }
}
