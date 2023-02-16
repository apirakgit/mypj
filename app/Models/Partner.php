<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'partner_id',
      'partner_name',
      'secret_key',
      'status',
      'del',
      'admin'
    ];

    public function access_secret($id,$secret){

      $count = $this->where([
        'partner_id' => $id,
        'secret_key' => $secret,
        'status' => 1,
        'del' => 0,
      ])->count();

      return $count;

    }
}
