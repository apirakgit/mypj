<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Customers;
use App\Models\cronlog;
use App\Models\vin_no_master;
class SyncVirtaWithEvolt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'virta:sync_customer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      $perpage = 100;
      $page = 1;
      $cnt_data_all = 0;
	    $date = date('Y-m-d');

      do {

        $response = Http::withToken( $this->auth_virta() )->withHeaders([
          'x-api-key' => env('VIRTA_API_KEY')
        ])->get( env('VIRTA_URL') . 'customers',[
          'page' => $page,
          'perPage' => $perpage,
          'updatedAfter' => $date,
        ]);

        $response = $response->json();
        $cnt_data = count($response);

        foreach ($response as $key => $value) {
      		if( $value['email'] != '' && $value['sellerId'] == env('VIRTA_SELLERID') ){
      			$customer = Customers::updateOrCreate(
      			  [ 'email' => $value['email'] ],
      			  [
      					'virta_id' => $value['id'],
      					'f_name' => $value['nameFirst'],
      					'l_name' => $value['nameLast'],
      					'tel' => $value['mobile'],
      					'email' => $value['email'],
      					'car_brand' => $value['externalReference'] ?? '',
      					'vin_no' => $value['customFieldInformation'] ?? '',
      					'register_date' => date('Y-m-d H:i:s', strtotime( $value['created'] )) ?? '',
      			  ]
      			);

      			vin_no_master::where('vin_no', $value['externalReference'])->update(['status' => 1]);
      		}
        }

        $cnt_data_all += $cnt_data;
        $page++;
		    echo '--------- ' . $page * $cnt_data . ' row --------- ';

      } while ($cnt_data == $perpage);

      $cron = [
        'cron_name' => 'virta:sync_customer',
        'count' => $cnt_data_all,
        'created_at' => date('Y-m-d H:i:s'),
      ];
      cronlog::insert($cron);
      echo 'Update Or Create Success.';

    }

    public function auth_virta(){
      $response = Http::withHeaders([
          'x-api-key' => env('VIRTA_API_KEY')
      ])->post( env('VIRTA_URL') . 'auth', [
          'username' => env('VIRTA_USER'),
          'password' => env('VIRTA_PASS'),
      ]);
      return $response['access_token'];
    }
}
