<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Auth;
use App\Models\vin_no_master;
use App\Models\Customers;
use App\Imports\VinnoImport;
use App\Exports\VinnoExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Models\VehicleBrand;
use Illuminate\Support\Facades\Input;

class VinnoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fileImportExport()
    {
      $brand = VehicleBrand::where('status', 1)->get();
      return view('pages.vinno.ImportExport', ['brand' => $brand]);
    }

    public function fileImport(Request $request)
    {
        $import = new VinnoImport( $request->brand_id );
        Excel::import($import, $request->file('file')->store('temp'));

        return back()->with('message','Import Vin no success.')->with('dup',$import->data_dup);
    }

    public function fileExport()
    {
        return Excel::download(new VinnoImport, 'vinno-collection.xlsx');
    }

    public function index(Request $request)
    {
      $role = auth()->user()->status;
      if($role != User::ADMIN_ACTIVE){
        Auth::logout();
        return redirect('/auth/login')->withErrors(['msg' =>  'ท่านไม่ได้รับอนุญาตให้เข้าถึงระบบ กรุณาติดต่อ Manager Admin']);
      }
      $vinno = vin_no_master::where('vin_no_masters.delete','!=', 1)
        ->Leftjoin('customers as c','vin_no_masters.user_bound','c.id')
        ->Leftjoin('vehicle_brands as b','vin_no_masters.brand_id','b.id');
      if( $request->vin_no != '' ){
        $vinno->where('vin_no_masters.vin_no','like','%'.$request->vin_no.'%');
      }
      if( $request->status != '' ){
        $vinno->where('vin_no_masters.status',$request->status);
        $vinno->orderBy('vin_no_masters.bound_time','DESC');
      }else{
		$vinno->orderBy('vin_no_masters.id','DESC');
	  }
      if( $request->dates != '' ){
        $dates = explode("-",$request->dates);
        $start_date = date('Y-m-d 00:00:01', strtotime($dates[0]));
        $end_date = date('Y-m-d 23:59:59', strtotime($dates[1]));

        $vinno->whereBetween('vin_no_masters.import_time',[$start_date, $end_date]);
      }

      $vinno->select(
          'vin_no_masters.id',
          'b.brand',

          'vin_no_masters.vin_no',
          'vin_no_masters.import_time',
          'vin_no_masters.bound_time',
          DB::raw("CASE WHEN vin_no_masters.status = 1 THEN 'Bound' ELSE 'Store' END status"),
          'vin_no_masters.admin',
          //'c.f_name',
          //'c.l_name',
          DB::raw("CONCAT(c.f_name,' ',c.l_name) as f_name"),
          DB::raw("CASE WHEN vin_no_masters.action = 1 THEN 'Enable' ELSE 'Disable' END action"),
          //'vin_no_masters.created_at',
          DB::raw("DATE_FORMAT(vin_no_masters.created_at, '%Y-%m-%d %H:%i:%s' ) as create_date")
        );

      $dataexcel = $vinno->get();
      if($request->has('exportExcel')){
        return Excel::download(new VinnoExport($dataexcel), 'VinnoMaster.xlsx');
      }

      $vinno = $vinno->paginate(10);

      return view('pages.vinno.home', ['vinno' => $vinno]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brand = VehicleBrand::where('status', 1)->get();
        return view('pages.vinno.create', ['brand' => $brand]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vin_no' => 'required|unique:vin_no_masters|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $reqAll = $request->all();
        $reqAll['admin'] = auth()->user()->name;

        $cus = Customers::where('vin_no', $request->vin_no);
        $q_cus = $cus->first();
        $cnt = $cus->count();
        $status = ( $cnt > 0 ) ? 1 : 0;
        $reqAll['status'] = $status;
        if( $status == 1 ){
          $reqAll['user_bound'] = $q_cus->id;
          $reqAll['bound_time'] = date('Y-m-d H:i:s');
        }

        vin_no_master::create($reqAll);

        return redirect('en/vinno/create')->with('message','Create VIN No success.');
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
    public function edit($locale, $id)
    {
        //$data = vin_no_master::find($id);
        $data = vin_no_master::where('vin_no_masters.id',$id)
        ->select(
          'vin_no_masters.*',
          DB::raw("(SELECT COUNT(*) FROM customers AS c WHERE c.vin_no = vin_no_masters.vin_no) AS cc")
         )->first();
        return view('pages.vinno.edit', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $locale, $id)
    {

        $data = vin_no_master::where('id', $id);
        $reqAll = $request->all();
        unset($reqAll['_method']);
        unset($reqAll['_token']);
        unset($reqAll['id']);

        $cus = Customers::where('vin_no', $request->vin_no);
        $q_cus = $cus->first();
        $cnt = $cus->count();
        $status = ( $cnt > 0 ) ? 1 : 0;
        $reqAll['status'] = $status;
        if( $status == 1 ){
          $reqAll['user_bound'] = $q_cus->id;
          $reqAll['bound_time'] = date('Y-m-d H:i:s');
        }else{
          $reqAll['user_bound'] = '';
          $reqAll['bound_time'] = NULL;
        }
        $reqAll['admin'] = auth()->user()->name;

        $data->update($reqAll);

        return redirect('/en/vinno')->with('message', 'Updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($locale, $id)
    {
        vin_no_master::where('id', $id)->update(['delete' => 1]);

        return redirect('en/vinno')->with('message','Delete success.');
    }
}
