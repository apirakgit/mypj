<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vin_no_master extends Model
{
    use HasFactory;

    protected $fillable = [
      'vin_no',
      'status',
      'import_time',
      'bound_time',
      'admin',
      'user_bound',
      'action',
      'brand_id',
    ];
}
