<?php

namespace App\Imports;

use App\Models\PromotionCode;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Collection;

class PromotionCodeImport implements ToCollection, WithStartRow
{
    public $pmc_id;
    public $data_dup;

    public function __construct($pmc_id)
    {
        $this->pmc_id = $pmc_id;
    }

	public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows)
    {
        try{

          $dup = 0;
          foreach ($rows as $row){

            $cnt = PromotionCode::where('code',$row[0])->count();

            if($cnt == 0){

              /*PromotionCode::insert([
                'code' => $row[0],
                'amount' => $row[1],
                'promotion_campaign_id' => $this->pmc_id,
                'created_at' => date('Y-m-d H:i:s'),
              ]);*/
              if($row[0] != '' && $row[1] != ''){
                $fss[] = [
                  'code' => $row[0],
                  'amount' => $row[1],
                  'promotion_campaign_id' => $this->pmc_id,
                  'created_at' => date('Y-m-d H:i:s')
                ];
              }

            }else{
              $dup++;
              $this->data_dup[] =  $row[0];

            }
          }

          if( $dup == 0 ){
            PromotionCode::insert($fss);
          }

        } catch (\Exception $e) {
          return response()->json([
            'errors' => $e->getMessage(),
          ]);
        }
    }
}
