<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundLog extends Model
{
    use HasFactory;

    protected $fillable = [
      'virta_id',
      'amount',
      'status_code',
      'transaction_ref',
      'description',
    ];
}
