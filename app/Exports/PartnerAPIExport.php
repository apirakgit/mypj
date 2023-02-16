<?php

namespace App\Exports;

use App\Partner;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PartnerAPIExport implements FromCollection, WithHeadings
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
        'PARTNER ID',
        'PARTNER NAME',
        'USER CREATE',
        'STATUS',
        'LAST CONNECT',
      ];
    }

    public function collection()
    {
        $data = $this->data_search;
        foreach ($data as $key => $value) {
          unset( $data[$key]['id'] );
          unset( $data[$key]['created_at'] );
        }
        return $data;
    }


}
