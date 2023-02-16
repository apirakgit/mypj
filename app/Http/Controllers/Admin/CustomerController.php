<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Auth;
use App\Models\Customers;
use App\Models\VehicleBrand;
use App\Models\vin_no_master;
use App\Models\CustomerLog;
use Illuminate\Support\Facades\Http;
use App\Exports\CustomerExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fileImportExport()
    {

      return Excel::download(new CustomerExport, 'customer.xlsx');
    }

    public function index(Request $request)
    {

      $role = auth()->user()->status;
      if($role != User::ADMIN_ACTIVE){
        Auth::logout();
        return redirect('/auth/login')->withErrors(['msg' =>  'ท่านไม่ได้รับอนุญาตให้เข้าถึงระบบ กรุณาติดต่อ Manager Admin']);
      }

      $customers = Customers::where('delete',0)->orderBy('register_date','DESC');

      if( $request->name != '' ){
        //$customers->where('f_name','like','%'.$request->name.'%');
        //$customers->orWhere('l_name','like','%'.$request->name.'%');
        $customers->where( DB::raw("CONCAT(f_name, ' ', l_name)"),'LIKE',  "%".$request->name."%" );
      }

      if( $request->vehicle != '' ){
        $customers->where('car_brand', $request->vehicle);
      }
      if( $request->vin_no != '' ){
        $customers->where('vin_no','like', '%'.$request->vin_no.'%');
      }
      if( $request->email != '' ){
        $customers->where('email','like', '%'.$request->email.'%');
      }
      if( $request->tel != '' ){
		$tel = $request->tel;
		if(strlen($tel) == 10){
			$tel = substr($request->tel,1,9);
		}
        $customers->where('tel','like', '%'.$tel.'%');
      }
      if( $request->dates != '' ){
        $dates = explode("-",$request->dates);
        $start_date = date('Y-m-d 00:00:01', strtotime($dates[0]));
        $end_date = date('Y-m-d 23:59:59', strtotime($dates[1]));

        $customers->whereBetween('register_date',[$start_date, $end_date]);
      }

      $customers->select(
        'id',
        DB::raw("CONCAT(f_name,' ',l_name) AS name"),
        'email',
        'tel',
        'address',
        'district',
        'sub_district',
        'province',
        'zipcode',
        DB::raw("'THAILAND' as country"),
        'birthday',
        'sex',
        'car_brand',
        'vin_no',
        'register_date',
        'status_code',
        'virta_id',
      );
      $dataexcel = $customers->get();

      if($request->has('exportExcel')){
        return Excel::download(new CustomerExport($dataexcel), 'Customer.xlsx');
      }

      $customers = $customers->paginate(20);
      $vehicles = VehicleBrand::where('status', 1)->get();



      return view('pages.customer.home', ['customers' => $customers,'vehicles' => $vehicles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vehicles = VehicleBrand::where('status', 1)->get();
        $vinno = vin_no_master::where('delete','!=', 1)->Where('status',0)->Where('action',1)->get();
        return view('pages.customer.create',['vehicles' => $vehicles,'vinno'=>$vinno]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   public function auth_virta(){

     $response = Http::withHeaders([
         'x-api-key' => env('VIRTA_API_KEY')

     ])->post( env('VIRTA_URL') . 'auth', [
         'username' => env('VIRTA_USER'),
         'password' => env('VIRTA_PASS'),
     ]);

     return $response['access_token'];
   }

    public function store(Request $request)
    {
        try {

          $validator = Validator::make($request->all(), [
              'email' => 'required|unique:customers|max:255',
              'tel' => 'required|unique:customers|max:255',
              'vin_no' => 'unique:customers|max:255',
          ]);

          if ($validator->fails()) {
              return back()->withErrors($validator)->withInput();
          }

          DB::beginTransaction();
          $data = $request->all();
          $data['register_date'] = date('Y-m-d H:i:s');
          $customer = Customers::create($data);
          $customer_id = $customer->id;
          if($request->vin_no != ''){
            vin_no_master::updateOrCreate(
              ['vin_no' => $request->vin_no],
              [
                'vin_no' => $request->vin_no,
                'status' => 1,
                'action' => 1,
                'user_bound' => $customer_id,
                'admin' => auth()->user()->name,
              ]
            );
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
            'EMP' => 'Evolt',
            'Bill Type' => 'Prepaid',
            'Register Date' => date('Y-m-d H.i.s'),
          ];

          $response = Http::withToken( $this->auth_virta() )->withHeaders([
              'x-api-key' => env('VIRTA_API_KEY')
          ])->post( env('VIRTA_URL') . 'customers', $body);

          if( $response->status() != '200'){
            DB::rollBack();
            return back()->withErrors(['msg' => 'Create Virta API : STATUS CODE = ' . $response->status()]);
          }

          $response = $response->json();

          $virta_id = '';
          $status_code = '';

          if( isset($response['id']) ){
            $virta_id = $response['id'];
          }

          if( isset($response['status_code']) ){
            $status_code = $response['status_code'];
          }

          $upd_virta = Customers::where('id', $customer_id)->update(['virta_id' => $virta_id,'status_code' => $status_code]);

          if( $request->vin_no != '' ){

            vin_no_master::where('vin_no', $request->vin_no)->update([
              'status' => 1,
              'user_bound' => $customer_id,
              'bound_time' => date('Y-m-d H:i:s')
            ]);

            $customer_log = [
              'customer_id' => $customer_id,
              'vin_old' => '',
              'vin_new' => $request->vin_no,
              'admin_id' => auth()->user()->id,
            ];

            CustomerLog::create($customer_log);

          }

          if($upd_virta){
            DB::commit();
            return back()->with('message', 'Create success.');
          }
        } catch (\Exception $e) {
          return back()->withErrors(['msg' => $e->getMessage()]);
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
    public function edit($lang_code, $id)
    {
        $data = Customers::find($id);
        $vehicles = VehicleBrand::where('status', 1)->get();
        $vinno = vin_no_master::where('delete','!=', 1)->Where('status',0)->Where('action',1)->get();
        return view('pages.customer.edit', ['data' => $data,'vehicles'=>$vehicles,'vinno'=>$vinno]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $lang_code, $id)
    {

      DB::beginTransaction();

      if( $request->vin_no != '' ){
        $my_vin = Customers::where('id', $id)->where('vin_no', $request->vin_no)->count();

        if( $my_vin == 0 ){
          $validator = Validator::make($request->all(), [
              'vin_no' => 'unique:customers|max:255',
          ]);

          if ($validator->fails()) {
              return back()->withErrors($validator)->withInput();
          }
        }
      }

      $vin_old = $request->old_vin_no;

      $customer = Customers::where('id', $id);

      $cust = $customer->select('id','virta_id','vin_no')->first();
      $cur_vin_no = $cust->vin_no;
      $customer_id = $cust->id;
      $virta_id = $cust->virta_id;
      if( $virta_id == ''){
        return back()->withErrors(['msg' => 'Not found Virta ID.']);
      }
      $updates = $request->all();
      unset($updates['_method']);
      unset($updates['_token']);
      unset($updates['old_vin_no']);
      unset($updates['id']);
      $customer->update($updates);

      if( $request->vin_no != '' ){

        vin_no_master::where('vin_no', $request->vin_no)->update([
          'status' => 1,
          'user_bound' => $customer_id,
          'bound_time' => date('Y-m-d H:i:s')
        ]);

        vin_no_master::where('vin_no', $cur_vin_no)->update([
          'status' => 0,
          'user_bound' => '',
          'bound_time' => NULL
        ]);

        $customer_log = [
          'customer_id' => $customer_id,
          'vin_old' => '',
          'vin_new' => $request->vin_no,
          'admin_id' => auth()->user()->id,
        ];

        CustomerLog::create($customer_log);

      }

      if( $request->vin_no != $cur_vin_no ){
        vin_no_master::where('vin_no', $cur_vin_no)->update([
          'status' => 0,
          'user_bound' => '',
          'bound_time' => NULL
        ]);
      }

      $body = [
        'nameFirst' => $request->f_name,
        'nameLast' => $request->l_name,
        'email' => $request->email,
        'mobile' => $request->tel,
        'address' => [
          'street' => $request->address,
          'city' => $request->province,
          'zipcode' => $request->zipcode,
          'country' => 'Thailand',
        ],
        'customFieldInformation' => $request->vin_no ?? '',
        'externalReference' => $request->car_brand,
        'EMP' => 'Evolt',
        'Bill Type' => 'Prepaid',
        'Language' => 'en',
        'Country' => 'Thailand',
        'Register Date' => date('Y-m-d H.i.s'),
      ];

      $response = Http::withToken( $this->auth_virta() )->withHeaders([
          'x-api-key' => env('VIRTA_API_KEY')
      ])->patch( env('VIRTA_URL') . 'customers/' . $virta_id, $body);

      if( $response->status() != '200'){
        DB::rollBack();
        return back()->withErrors(['msg' => 'Update Virta API : STATUS CODE = ' . $response->status()]);
      }

      DB::commit();

      return redirect('/en/customers')->with('message', 'Updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($lang_code,$id)
    {
        //Customers::where('id',$id)->update(['delete'=>1]);
        Customers::destroy($id);
        return redirect('/en/customers')->with('message', 'Deleted.');
    }
}
