<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
      'transaction_ref',
      'order_id',
      'merchant_id',
      'request_timestamp',
      'curency',
      'amount',
      'transaction_datetime',
      'payment_channel',
      'payment_status',
      'channel_response_code',
      'channel_response_desc',
      'masked_pan',
    ];
}
