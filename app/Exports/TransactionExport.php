<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TransactionExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function headings():array{
      return[
        'STATUS TRANSACTION',
        'TRANSACTION ID',
        'TRANSACTION TIMESTAMP',
        'PAYMENT CHANNEL',
        'NAME',
        'EMAIL',
        'PHONE',
        'CUSTOMER PAID AMOUNT(BAHT)',
        'PROMOTION NAME',
        'PROMOTION CODE',
        'DISCOUNT(BAHT)',
        'BONUS(BAHT)',
        'FREE TOP UP(BAHT)',
        'TOP UP AMOUNT(BAHT)',
        'ADMIN NAME',
        'ADMIN TIMESTAMP',

      ];
    }

    public $data_search;

    public function __construct($data_search)
     {
         $this->data_search = $data_search;
     }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = $this->data_search;
        foreach ($data as $key => $value) {
  			if($data[$key]->channel_response_code == 'Failed to top up'){
  				$data[$key]->status = 'Failed to top up';
  			}
          $data[$key]->topup_amount = number_format(substr((int)($value->topup_amount),0,-2));
          $data[$key]->amount = number_format(substr((int)($value->amount),0,-2));
          unset( $data[$key]->channel_response_code);
          //unset( $data[$key]->amount);
        }
        return $data;

    }
}
