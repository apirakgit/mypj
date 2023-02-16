<?php

namespace App\Exports;

use App\Models\vin_no_master;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Carbon\Carbon;

class VinnoExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($data_search)
    {
       $this->data_search = $data_search;
    }

    public function headings():array{
      return[
        'BRAND',
        'VIN NO',
        'IMPORT TIME',
        'BOUND TIME',
        'STATUS',
        'ADMIN',
        'USER BOUND',
        'ACTION',
        'CREATEDATE',
      ];
    }

    public function collection()
    {
        $data = $this->data_search;
        foreach ($data as $key => $value) {
          unset( $data[$key]['id'] );
          //$data[$key]['created_at'] = 'sdfds';
          //unset( $data[$key]['created_at'] );
        }

        return $data;
    }
}
