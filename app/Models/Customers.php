<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customers extends Model
{
    use HasFactory;

    protected $fillable = [
      'f_name',
      'l_name',
      'birthday',
      'sex',
      'tel',
      'email',
      'address',
      'province',
      'district',
      'sub_district',
      'zipcode',
      'car_brand',
      'vin_no',
      'virta_id',
      'status_code',
      'register_date',
    ];

    public function getCustomersAPI($search){

      $email = $search['email'] ?? '';
      $name = $search['name'] ?? '';
      $brand = $search['vehicle_brand'] ?? '';
      $vin_no = $search['vin_no'] ?? '';
      $phone_number = $search['phone_number'] ?? '';
      $register_date_start = $search['register_date_start'] ?? '';
      $register_date_end = $search['register_date_end'] ?? '';

      $data = $this->where('delete',0)
                ->select(
                    'id',
                    DB::raw(' CASE when status_code != "" and virta_id = "" THEN status_code ELSE "Approved" END STATUS'),
                    'register_date',
                    DB::raw('CONCAT(f_name, " ",l_name) as name'),
                    'email',
                    DB::raw('LPAD(tel, 10, 0) AS tel'),
                    'address',
                    'sub_district',
                    'district',
                    'province',
                    'zipcode',
                    DB::raw('"THAILAND" as country'),
                    'birthday',
                    'sex as gender',
                    'car_brand as vehicle_brand',
                    DB::raw('IFNULL(vin_no,"") as vin_no'),
                );

        if( $email != '' ){
          $data->where('email','like','%'.$email.'%');
        }

        if( $name != '' ){
          $data->where( DB::raw("CONCAT(f_name, ' ', l_name)"),'LIKE',  "%".$name."%" );
        }

        if( $brand != '' ){
          $data->where('car_brand', $brand);
        }

        if( $vin_no != '' ){
          $data->where('vin_no','like', '%'.$vin_no.'%');
        }

        if( $phone_number != '' ){
      		$tel = $phone_number;
      		if(strlen($tel) == 10){
      			$tel = substr($phone_number,1,9);
      		}
          $data->where('tel','like', '%'.$tel.'%');
        }

        if( $register_date_start != '' ){
          $data->where('register_date','>=', $register_date_start . ' 00:00:00');
        }

        if( $register_date_end != '' ){
          $data->where('register_date','<=', $register_date_end . ' 23:59:59');
        }

        $data->orderBy('register_date','DESC');
        $result = $data->paginate(20);

      return $result;

    }
}
