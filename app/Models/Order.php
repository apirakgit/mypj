<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
      'order_id',
      'name',
      'email',
      'tel',
      'amount',
      'approve_by',
      'approve_date',
      'reject_date',
      'discount',
      'topup_amount',
      'bonus',
      'free_topup',
      'promotion_code',
      'promotion_code_list_id',
    ];
}
