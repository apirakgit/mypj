<?php

namespace App\Exports;

use App\Models\PromotionCode;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class PromotionCodeExport implements FromCollection, WithHeadings, WithColumnWidths, WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($id)
    {
       $this->id = $id;
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 23,
            'B' => 18,
            'C' => 9,
            'D' => 8,
            'E' => 19,
            'F' => 12,
            'G' => 28,
            'H' => 12,
            'I' => 28,
            'J' => 19,
        ];
    }

    public function headings():array{
      return[
        'PROMOTION CAMPAIGN',
        'PROMOTION CODE',
        'AMOUNT',
        'STATUS',
        'DATE CREATE',
        'USER ACTIVE',
        'USER NAME',
        'USER PHONE',
        'USER EMAIL',
        'USER TIMESTAMP'
      ];
    }

    public function collection()
    {
        return PromotionCode::where('promotion_campaign_id',$this->id)
        ->leftJoin('promotion_code_lists as a','a.promotion_code_id','promotion_codes.id')
        ->leftJoin('promotion_campaigns as b','b.id','promotion_codes.promotion_campaign_id')
        ->leftJoin('customers as c','c.id','a.customer_id')
        ->select(
          'b.name',
          'promotion_codes.code',
          'promotion_codes.amount',
          DB::raw("CASE WHEN promotion_codes.status = 1 THEN 'Enable' ELSE 'Disable' END status"),
          DB::raw("DATE_FORMAT(promotion_codes.created_at, '%Y-%m-%d %H:%i:%s' ) as date"),
          DB::raw("(SELECT FORMAT(COUNT(b.id),0) FROM promotion_code_lists b WHERE b.promotion_code_id = promotion_codes.id AND b.status = 1) AS active"),

          DB::raw("CONCAT(c.f_name,' ',c.l_name) as name2"),
          DB::raw("LPAD(c.tel,10,'0') tel"),
          'c.email',
          DB::raw("DATE_FORMAT(a.created_at, '%Y-%m-%d %H:%i:%s' ) as date2")
          )
        //->where('a.status', '1')
        ->get();
    }
}
