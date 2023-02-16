<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
			$table->id();
            $table->string('order_id')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('tel');
            $table->string('amount');
            $table->integer('approve_by')->nullable();
            $table->timestamp('approve_date')->nullable();
            $table->timestamp('reject_date')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
