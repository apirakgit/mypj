<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_ref');
            $table->string('order_id');
            $table->string('merchant_id');
            $table->timestamp('request_timestamp');
            $table->string('currency');
            $table->string('amount');
            $table->timestamp('transaction_datetime');
            $table->string('payment_channel');
            $table->string('payment_status');
            $table->string('channel_response_code');
            $table->string('channel_response_desc');
            $table->string('masked_pan');
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
        Schema::dropIfExists('transactions');
    }
}
