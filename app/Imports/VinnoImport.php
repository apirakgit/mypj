<?php

namespace App\Imports;

use App\Models\vin_no_master;
use App\Models\Customers;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Auth;

class VinnoImport implements ToCollection
{
    public $brand_id;
    public $data_dup;

    public function __construct($brand_id)
    {
        $this->brand_id = $brand_id;
    }

    public function collection(Collection $rows)
    {
        $vinno_status = 0;

        try{

          foreach ($rows as $row){

            $cnt = vin_no_master::where('vin_no',$row[0])->count();

            if($cnt == 0){

              $cus_cnt = Customers::where('vin_no', $row[0])->count();
              $vinno_status = ( $cus_cnt > 0 ) ? 1 : 0;

              vin_no_master::insert([
              'vin_no' => $row[0],
              'brand_id' => $this->brand_id,
              'action' => 1,
              'status' => $vinno_status,
              'import_time' => date('Y-m-d H:i:s'),
              'admin' => auth()->user()->name,
              'created_at' => date('Y-m-d H:i:s'),
              ]);

            }else{
              $this->data_dup[] =  $row[0];
            }
          }

        } catch (\Exception $e) {
          return response()->json([
          'errors' => $e->getMessage(),
          ]);
        }
    }
}
/*
class VinnoImport implements ToModel
{
    public function model(array $row)
    {
        return new vin_no_master([
            'vin_no' => $row[0],
            'action' => 1,
            'status' => 0,
            'import_time' => date('Y-m-d H:i:s'),
            'admin' => auth()->user()->name,
        ]);
    }
}*/
