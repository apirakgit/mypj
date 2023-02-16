<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cronlog extends Model
{
    use HasFactory;

    protected $fillable = [
      'cron_name',
      'count',
      'status',
    ];
}
