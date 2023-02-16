<?php

namespace App\Exports;

use App\Models\Customers;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class CustomerExport implements FromCollection, WithHeadings, ShouldAutoSize,WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function headings():array{
      return[
        'Name - Surname',
        'E-mail',
        'Phone No',
        'Address',
        'District',
        'Sub-District',
        'Province',
        'Post Code',
        'Country',
        'Birth Date',
        'Gender',
        'Vehicle Brand',
        'Vin No.',
        'Register Date',
      ];
    }

    public $data_search;

    public function __construct($data_search)
    {
       $this->data_search = $data_search;
    }

    public function collection()
    {
        $data = $this->data_search;

        foreach ($data as $key => $value) {
          $data[$key]['tel'] = str_pad($value['tel'],10,"0",STR_PAD_LEFT);
          //$data[$key]['register_date'] = $value['created_at'];
          unset( $data[$key]['id'] );
          unset( $data[$key]['virta_id'] );
          unset( $data[$key]['created_at'] );
        }
        return $data;
    }
}
