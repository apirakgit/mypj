<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionCode extends Model
{
    use HasFactory;

    protected $fillable = [
      'code',
      'amount',
      'status',
      'delete',
      'promotion_campaign_id',
      'created_at',
    ];
}
