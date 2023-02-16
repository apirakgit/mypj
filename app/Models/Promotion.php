<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
      'vehicle_id',
      'discount',
      'start_date',
      'end_date',
      'user_create',
      'user_last_update',
      'status',
      'name',
      'name_en',
      'is_vin',
    ];
}
