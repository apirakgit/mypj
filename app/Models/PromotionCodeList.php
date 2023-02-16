<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionCodeList extends Model
{
    use HasFactory;

    protected $fillable = [
      'promotion_code_id',
      'customer_id',
      'created_at',
    ];
}
