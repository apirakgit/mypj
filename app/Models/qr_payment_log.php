<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class qr_payment_log extends Model
{
    use HasFactory;

    protected $fillable = [
      'response',
    ];
}
