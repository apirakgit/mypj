<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customers;
use Illuminate\Support\Facades\Validator;
use App\Models\VehicleBrand;
use App\Models\Partner;
use Illuminate\Support\Facades\DB;

class CustomerAPIController extends Controller
{

    public function check_secret_key($id,$secret){

      $partner = new Partner();
      return $partner->access_secret($id,$secret);

    }

    public function index(Request $request){
      try {

        $partner_id = $request->header('partner-id');
        $partner_secret = $request->header('partner-secret');

        if( empty($partner_id) || empty($partner_secret) ){
          return response()->json([ 'status' => false, 'errors' => 'No access information found.', 'error_code' => '01'],401);
        }

        $pass_secret = $this->check_secret_key($partner_id,$partner_secret);

        if(!$pass_secret){
          return response()->json([ 'status' => false, 'errors' => 'Unauthorized','error_code' => '02'],401);
        }

        Partner::where('partner_id', $partner_id)->update(['last_connect' => date('Y-m-d H:i:s')]);

        $cus = new Customers;
        $data = $cus->getCustomersAPI($request->all());

        return response()->json([ 'status' => true, 'result' => $data, ]);

      } catch (\Exception $e) {

        return response()->json([ 'status' => false, 'errors' => $e->getMessage(), ]);

      }

    }

    public function store(Request $request)
    {
      return response()->json([
        'status' => false,
        'errors' => 'No data.',
      ]);

      try {

        $partner_id = $request->header('partner-id');
        $partner_secret = $request->header('partner-secret');

        if( empty($partner_id) || empty($partner_secret) ){
          return response()->json([
            'status' => false,
            'errors' => 'Unauthorized'
          ],401);
        }

        $pass_secret = $this->check_secret_key($partner_id,$partner_secret);

        if(!$pass_secret){
          return response()->json([
            'status' => false,
            'errors' => 'Unauthorized'
          ],401);
        }
        /////////////////////////////////
        $car_brand = VehicleBrand::where('status',1)->select('brand')->get();

        foreach ($car_brand as $key => $value) {
          $array[] = $value['brand'];
        }
        $imp = implode(",", $array);
        $messages = [
          'in' => 'กรุณากรอกข้อมูลเฉพาะ [ ' . $imp .' ]'
        ];
        $car_require = 'in:'.$imp;

        $rules = [
  			  'f_name' => 'required','l_name' => 'required','sex' => 'required',
  			  'tel' => 'required|unique:customers,tel',
  			  'email' => 'required|unique:customers,email',
  			  'address' => 'required','province' => 'required',
  			  'district' => 'required','sub_district' => 'required','zipcode' => 'required',
  			  'car_brand' => $car_require, //in:BMW,TOYOTA
  		  ];

        $attributes = [
          'f_name' => 'first name',
          'l_name' => 'last name',
          'tel' => 'Phone number',
          'car_brand' => 'Car band',
        ];



        $validator = Validator::make( $request->all(), $rules, $messages, $attributes );

        if ($validator->fails()) {

            return response()->json([
              'status' => false,
              'errors' => $validator->errors()
            ]);
        }

        DB::beginTransaction();
        $create = $request->all();
        $create['register_date'] = date('Y-m-d H:i:s');
        $customer = Customers::create($create);

        DB::commit();

        return response()->json([
          'status' => true,
          'message' => 'Create Success.',
        ]);

      } catch (\Exception $e) {

        DB::rollBack();
        return response()->json([
          'status' => false,
          'errors' => $e->getMessage(),
        ]);

      }
    }
}
