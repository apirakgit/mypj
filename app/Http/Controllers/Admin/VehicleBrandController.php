<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Auth;
use App\Models\VehicleBrand;

class VehicleBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(Request $request)
     {
       $role = auth()->user()->status;
       if($role != User::ADMIN_ACTIVE){
         Auth::logout();
         return redirect('/auth/login')->withErrors(['msg' =>  'ท่านไม่ได้รับอนุญาตให้เข้าถึงระบบ กรุณาติดต่อ Manager Admin']);
       }
       $data = VehicleBrand::orderBy('id','DESC');
       if( $request->brand != '' ){
         $data->where('brand','like','%'.$request->brand.'%');
       }
       if( $request->status != '' ){
         $data->where('status',$request->status);
       }
       $data->select(
        'vehicle_brands.*',
        DB::raw('( SELECT COUNT(*) from customers AS c WHERE c.car_brand = vehicle_brands.brand ) AS customer_cnt')
        );
       $data = $data->paginate(10);

       return view('pages.vehicle.home', ['datas' => $data]);
     }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.vehicle.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'brand' => 'unique:vehicle_brands',
        ]);

        VehicleBrand::create($request->all());

        return redirect('en/vehicle/create')->with('message','Create Vehicle Brand success.');
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

         $data = VehicleBrand::where('vehicle_brands.id', $id)
          ->select(
            'vehicle_brands.*',
            DB::raw("(SELECT COUNT(*) FROM customers AS c WHERE c.car_brand = vehicle_brands.brand) AS cc")
           )
          ->first();
         return view('pages.vehicle.edit', ['data' => $data]);
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
         $data = VehicleBrand::where('id', $id);
         $reqAll = $request->all();
         unset($reqAll['_method']);
         unset($reqAll['_token']);
         unset($reqAll['id']);
         $data->update($reqAll);

         return redirect('/en/vehicle')->with('message', 'Updated.');
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
