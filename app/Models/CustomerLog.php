<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerLog extends Model
{
    use HasFactory;

    protected $fillable = [
      'customer_id',
      'vin_old',
      'vin_new',
      'admin_id',
    ];
}
