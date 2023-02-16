<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
      'name_en',
      'type',
      'price',
      'repeat_status',
      'status',
      'start_date',
      'end_date',
      'create_by',
    ];
}
