<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Customers;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\vin_no_master;
use App;
use App\Models\VehicleBrand;
use App\Models\Promotion;
use App\Models\PromotionCampaign;
use App\Models\PromotionCode;
use App\Models\PromotionCodeList;
use Carbon\Carbon;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index($locale)
    {
      if (! in_array($locale, ['en', 'th'])) {
          abort(400);
      }
      App::setLocale($locale);

      $vehicles = VehicleBrand::where('status', 1)->get();
      return view('customer.register', ['vehicles' => $vehicles]);
    }

    public function getName(Request $request){

      $name = '';
      $id = '';
      if(isset($request->getname)){
        $query = Customers::where('email',$request->getname)->first();
        if($query){
          $name = $query->f_name . ' ' . $query->l_name;
          $id = $query->id;
        }

      }

      return response()->json([
        'status' => true,
        'name' => $name,
        'id' => $id,
      ]);
    }

    public function check_promotion_code(Request $request){

      $code = $request->code ?? '';
      $email = $request->email ?? '';
      $customer_id = '';

      if($email != ''){
        $customer = Customers::where('email',$email)->first();
        $customer_id = $customer->id;
      }

      if($code != ''){

        $query = DB::table('promotion_codes as a')
          ->join('promotion_campaigns as b','b.id','a.promotion_campaign_id')
          ->where('a.code',$code)
          ->where('a.status', 1)->where('a.delete', 0)->where('b.status', 1)->where('b.delete', 0)
          ->select(
            DB::raw("a.id as p_code_id"),
            DB::raw("b.id as p_camp_id"),
            'a.code', 'a.amount', 'b.name', 'b.name_en', 'b.price', 'b.repeat_status','b.type','b.start_date','end_date'
          );
        $cnt = $query->count();
        $p_code_detail = $query->first();
        $data = $cnt;

        if($cnt > 0){

          $today = date('Y-m-d H:i:s');

          $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $p_code_detail->start_date);
          $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $p_code_detail->end_date);
          $now = Carbon::createFromFormat('Y-m-d H:i:s', $today);
          //gt() greater than gte() greater than or equals lt() less than lte() less than or equals
          $result1 = $start_date->lt($now);
          $result2 = $end_date->gt($now);

          if($result1 == false || $result2 == false){
            return response()->json([
              'status' => true,
              'data' => 0,
              'res' => '',
              'msg' => 'โปรโมชั่นโค้ดหมดอายุแล้ว'
            ]);
          }

          $count_active = PromotionCodeList::where('promotion_code_id', $p_code_detail->p_code_id)->where('status', 1)->count();

          ////////////////////////////////////// repeat = 1
          if($p_code_detail->repeat_status == 1){
            $count_active = PromotionCodeList::where('promotion_code_id', $p_code_detail->p_code_id)->where('status', 1)->count();
            if($p_code_detail->amount > $count_active){ //500 > 2
              return response()->json([
                'status' => true,
                'data' => $data ?? 0,
                'res' => $p_code_detail ?? '',
                'msg' => ''
              ]);
            }else{
              return response()->json([
                'status' => true,
                'data' => 0,
                'res' => $p_code_detail ?? '',
                'msg' => 'โปรโมชั่นโค้ดถูกใช้งานครบแล้ว'
              ]);
            }
          }
          ////////////////////////////////////// repeat = 0
		  if($p_code_detail->repeat_status == 0){
            $count_active = PromotionCodeList::where('promotion_code_id', $p_code_detail->p_code_id)->where('status', 1)->count();
            if($customer_id != ''){ //check email data

        		  $count_active_user = PromotionCodeList::where('promotion_code_id', $p_code_detail->p_code_id)->where('customer_id',$customer_id)->where('status', 1)->count();
        		  if($count_active_user == 0 && $p_code_detail->amount > $count_active){
        			return response()->json([
        			  'status' => true,
        			  'data' => $data ?? 0,
        			  'res' => $p_code_detail ?? '',
        			  'msg' => ''
        			]);
        		  }else{
        			return response()->json([
        			  'status' => true,
        			  'data' => 0,
        			  'res' => $p_code_detail ?? '',
        			  'msg' => 'คุณได้ใช้งานโปรโมชั่นนี้ไปแล้ว'
        			]);
        		  }

        		}
            if($p_code_detail->amount > $count_active){ //500 > 2
              return response()->json([
                'status' => true,
                'data' => $data ?? 0,
                'res' => $p_code_detail ?? '',
                'msg' => ''
              ]);
            }else{
              return response()->json([
                'status' => true,
                'data' => 0,
                'res' => $p_code_detail ?? '',
                'msg' => 'คุณได้ใช้งานโปรโมชั่นนี้ไปแล้ว'
              ]);
            }
          }




        }

        return response()->json([
          'status' => true,
          'data' => 0,
          'res' => '',
          'msg' => ''
        ]);

      }else{
        $data = 0;
      }

      return response()->json([
        'status' => true,
        'data' => $data,
        'res' => $res ?? '',
        'msg' => ''
      ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

    public function check_dup_email(Request $request){

      $car_brand = '';
      $email_cnt = 0;
      $tel_cnt = 0;
      $f_name = '';
      $l_name = '';
      $tel = '';
      $vinno = '';

      $cnt_customer = Customers::where('email', $request->email)
        ->orWhere('tel', $request->tel)
        ->orWhere('tel', substr($request->tel, 1))
        ->select('car_brand','f_name','l_name','tel','vin_no');
      $cnt = $cnt_customer->count();

      $email_cnt = Customers::where('email', $request->email)->count();
      $tel_cnt = Customers::where('tel', $request->tel)->count();
      if( $tel_cnt == 0 ){
        $tel_cnt = Customers::where('tel', substr($request->tel, 1))->count();
      }

      if($cnt > 0){
        $data = $cnt_customer->first();
        $car_brand = $data->car_brand;
        $f_name = $data->f_name;
        $l_name = $data->l_name;
        $tel = $data->tel;
        $vin_no = $data->vin_no;
      }

      return response()->json([
        'status' => 1,
        'count' => $cnt,
        'email_count' => $email_cnt,
        'tel_count' => $tel_cnt,
        'car_brand' => $car_brand,
        'vin_no' => $vin_no ?? '',
        'f_name' => $f_name,
        'l_name' => $l_name,
        'tel' => str_pad($tel,10,"0",STR_PAD_LEFT),
      ]);

    }

    public function check_dup_email_topup(Request $request){

      $cnt_customer = Customers::where('email', $request->email);
      $cnt = $cnt_customer->count();

      return response()->json([
        'status' => 1,
        'count' => $cnt,
      ]);

    }

    public function check_promotion(Request $request){

      $discount = 0;
      $datetime = date('Y-m-d H:i:s');
      $q_customers = Customers::where('email', $request->email)->select('car_brand','vin_no')->first();
      $customer_car_brand = $q_customers['car_brand'];
      $customer_vinno = $q_customers['vin_no'];

      if( $customer_car_brand != '' ){

        $cnt_customer = Customers::where('email', $request->email)
          ->join('vehicle_brands as v','customers.car_brand','v.brand')
          ->join('promotions as p','v.id','p.vehicle_id')
          ->where('p.start_date','<=',$datetime)
          ->where('p.end_date','>=',$datetime)
          ->where('p.status',1)
          ->where('p.delete',0);
        $cnt = $cnt_customer->count();
        //check promotion
        if( $cnt > 0 ){
          $data = $cnt_customer->select('p.discount','p.name','p.name_en','p.is_vin','v.id','customers.vin_no')->first();
          $promotion_name = ( $request->locale == 'en' ) ? $data->name_en : $data->name;
          //promotion check vin no master
          if( $data->is_vin == 1 ){
              $cnt_is_vin = vin_no_master::where('vin_no',$data->vin_no)
                ->where('brand_id',$data->id)
                ->where('action',1)
                ->where('delete',0)
                ->count();
              if( $cnt_is_vin > 0 ){
                return response()->json([
                  'status' => 1,
                  'discount' => $data->discount ?? 0,
                  'promotion_name' => $promotion_name ?? '',
                  'locale' => $request->locale,
                ]);
              }else{
                return response()->json([
                  'status' => 1,
                  'discount' => 0,
                  'promotion_name' => '',
                  'locale' => $request->locale,
                ]);
              }
          }else{
            return response()->json([
              'status' => 1,
              'discount' => $data->discount ?? 0,
              'promotion_name' => $promotion_name ?? '',
              'locale' => $request->locale,
            ]);
          }

        }else{
          return response()->json([
            'status' => 1,
            'discount' => 0,
            'promotion_name' => '',
            'locale' => $request->locale,
          ]);
        }
      }else{
		  return response()->json([
            'status' => 1,
            'discount' => 0,
            'promotion_name' => '',
            'locale' => $request->locale,
          ]);
	  }

    }

    public function check_promotion_for_regis($email, $locale){

      $discount = 0;
      $datetime = date('Y-m-d H:i:s');
      $q_customers = Customers::where('email', $email)->select('car_brand','vin_no')->first();
      $customer_car_brand = $q_customers['car_brand'];
      $customer_vinno = $q_customers['vin_no'];

      if( $customer_car_brand != '' ){

        $cnt_customer = Customers::where('email', $email)
          ->join('vehicle_brands as v','customers.car_brand','v.brand')
          ->join('promotions as p','v.id','p.vehicle_id')
          ->where('p.start_date','<=',$datetime)
          ->where('p.end_date','>=',$datetime)
          ->where('p.status',1)
          ->where('p.delete',0);
        $cnt = $cnt_customer->count();
        //check promotion
        $promotion_name = '';
        if( $cnt > 0 ){
          $data = $cnt_customer->select('p.discount','p.name','p.name_en','p.is_vin','v.id','customers.vin_no')->first();
          $promotion_name = ( $locale == 'en' ) ? $data->name_en : $data->name;
          //promotion check vin no master
          if( $data->is_vin == 1 ){
              $cnt_is_vin = vin_no_master::where('vin_no',$data->vin_no)->where('brand_id',$data->id)->where('action',1)->where('delete',0)->count();
              if( $cnt_is_vin > 0 ){
                return $promotion_name;
              }else{
                return '';
              }
          }else{
            return $promotion_name;
          }

        }else{
          return '';
        }
      }else{
		return '';
	  }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      try {

        DB::beginTransaction();

        $chk_update = Customers::where('email', $request->email);
        $cnt = $chk_update->count();
        $is_update_vin = 0;

        // update
        if( $cnt > 0 ){

            $chk_update = $chk_update->first();

            if( $request->vin_no != '' ){

              $vin_cus = Customers::where('vin_no', $request->vin_no)->count();
              if( $vin_cus > 0 ){
                return response()->json([
                  'status' => 2,
                  'errors' => 'Vin no duplicate',
                ]);
              }

              $vinno_cnt = vin_no_master::where('vin_no', $request->vin_no)->where('status', 1)->count();

              if( $vinno_cnt > 0 ){
                return response()->json([
                  'status' => 2,
                  'errors' => 'Vin no duplicate',
                ]);
              }else{
                $update_vismaster = vin_no_master::where('vin_no', $request->vin_no)->update([
                  'status' => 1,
                  'bound_time' => date('Y-m-d H:i:s'),
                  'user_bound' => $chk_update->id,
                ]);
                if($update_vismaster){
                  $is_update_vin = 1;
                }

              }
            }

            $body = [
              'car_brand' => $request->car_brand,
              'vin_no' => $request->vin_no,
            ];

            $upd_cus = Customers::where('virta_id', $chk_update->virta_id)->update( $body );

            if($upd_cus){

              $body = [
                'customFieldInformation' => $request->vin_no,
                'externalReference' => $request->car_brand,
              ];
              $response = Http::withToken( $this->auth_virta() )->withHeaders([
                  'x-api-key' => env('VIRTA_API_KEY')
              ])->patch( env('VIRTA_URL') . 'customers/' . $chk_update->virta_id, $body);
              $response = $response->json();

              if(isset($response['id'])){

                DB::commit();

                $chk_pro = $this->check_promotion_for_regis($request->email,'th');
                return response()->json([
                  'status' => 1,
                  'message' => 'customer updated.',
                  'is_update_vin' => $is_update_vin,
                  'promotion_name' => $chk_pro ?? ''
                ]);

              }else{
                return response()->json([
                  'status' => 0,
                  'errors_code' => '011',
                  'errors' => 'error api customer update',
                ]);
              }
            }else{
              return response()->json([
                'status' => 0,
                'errors_code' => '001',
                'errors' => 'error customer update',
              ]);
            }

        }else{
          // create
          if( $request->vin_no != '' ){

            $vin_cus = Customers::where('vin_no', $request->vin_no)->count();
            if( $vin_cus > 0 ){
              DB::rollBack();
              return response()->json([
                'status' => 2,
                'errors' => 'Vin no duplicate1',
                'vinno' => $request->vin_no,
              ]);
            }

            $vinno_cnt = vin_no_master::where('vin_no', $request->vin_no)->where('status', 1)->count();
            if( $vinno_cnt > 0 ){
              DB::rollBack();
              return response()->json([
                'status' => 2,
                'errors' => 'Vin no duplicate2',
              ]);
            }

          }
          $resAll = $request->all();

          if( $request->birthday != '' || $request->birthday_r != '' ){
            $resAll['birthday'] = $request->birthday ?? $request->birthday_r;
            if( $resAll['birthday_locale'] == 'th' ){
              $datearray = explode("-",$resAll['birthday']);
              $meyear = $datearray[0] - 543;
              $datadate = $meyear.'-'.$datearray[1]."-".$datearray[2];
              $resAll['birthday'] = $datadate;
            }
          }

          $resAll['register_date'] = date('Y-m-d H:i:s');
          $customer = Customers::create($resAll);
          $customer_id = $customer->id;

		  $update_vismaster = vin_no_master::where('vin_no', $request->vin_no)->update([
			  'status' => 1,
			  'bound_time' => date('Y-m-d H:i:s'),
			  'user_bound' => $customer_id,
			]);
			if($update_vismaster){
			  $is_update_vin = 1;
			}

          $body = [
            'sellerId' => env('VIRTA_SELLERID'),
            'wantRecharge' => false,
            'mobile' => $request->tel,
            'email' => $request->email,
            'language' => 'en',
            'nameFirst' => $request->f_name,
            'nameLast' => $request->l_name,
            'wantHardware' => false,
            'address' => [
              'street' => $request->address,
              'city' => $request->province,
              'zipcode' => $request->zipcode,
              'country' => 'Thailand',
            ],
            'billing_type' => 0,
            'skipWelcomeEmail' => false,
            'externalReference' => $request->car_brand,
            'customFieldInformation' => $request->vin_no,
          ];

          $response = Http::withToken( $this->auth_virta() )->withHeaders([
              'x-api-key' => env('VIRTA_API_KEY')
          ])->post( env('VIRTA_URL') . 'customers', $body);

          $response = $response->json();

          $virta_id = '';
          $status_code = '';

          if( isset($response['id']) ){
            $virta_id = $response['id'];
          }

          if( isset($response['status_code']) ){
            $status_code = $response['status_code'];

            if( isset($response['validation']['errors'])){
              foreach ($response['validation']['errors'] as $key => $value) {
                $status_code .= ' - ' . $value;
              }
            }

          }

          $upd_virta = Customers::where('id', $customer_id)->update(['virta_id' => $virta_id,'status_code' => $status_code]);

          if($upd_virta){
            DB::commit();
            $chk_pro = $this->check_promotion_for_regis($request->email,'th');
            return response()->json([
              'status' => 1,
              'message' => 'customer created.',
			  'is_update_vin' => $is_update_vin,
              'promotion_name' => $chk_pro ?? ''
            ]);
          }else{
            DB::rollBack();
            return response()->json([
              'status' => 0,
              'errors_code' => '002',
              'errors' => 'last update errors.',
            ]);
          }
        }

      } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
          'status' => 0,
          'errors_code' => '003',
          'errors' => $e->getMessage(),
        ]);
      }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
