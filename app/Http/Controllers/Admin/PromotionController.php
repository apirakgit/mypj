<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Auth;
use App\Models\Promotion;
use App\Models\VehicleBrand;

class PromotionController extends Controller
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

         $data = Promotion::join('vehicle_brands','vehicle_brands.id','=','promotions.vehicle_id')->orderBy('promotions.id','DESC');
         if( $request->vehicle != '' ){
           $data->where('promotions.vehicle_id',$request->vehicle);
         }
         if( $request->status != '' ){
           $data->where('promotions.status',$request->status);
         }
         if( $request->dates != '' ){
           $dates = explode("-",$request->dates);
           $start_date = date('Y-m-d 00:00:01', strtotime($dates[0]));
           $end_date = date('Y-m-d 23:59:59', strtotime($dates[1]));

           $data->whereBetween('promotions.created_at',[$start_date, $end_date]);
         }
         $data->select('promotions.name','promotions.name_en','promotions.is_vin','promotions.id','vehicle_brands.brand','promotions.discount','promotions.start_date','promotions.end_date','promotions.created_at','promotions.updated_at','promotions.user_create','promotions.user_last_update','promotions.status');
         $data->where('promotions.delete','!=',1);
         $data = $data->paginate(10);
         $vehicles = VehicleBrand::where('status', 1)->get();
         return view('pages.promotion.home', ['datas' => $data,'vehicles' => $vehicles]);

     }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vehicles = VehicleBrand::where('status', 1)->get();
        return view('pages.promotion.create', ['vehicles' => $vehicles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $vehicle_id = $request->vehicle_id;
        $promotion = Promotion
          ::where( function($query) use ($start_date,$vehicle_id) {
            $query->where('end_date','>',$start_date)->where('status',1)->where('vehicle_id',$vehicle_id)->where('delete','!=',1);
          })
          ->where( function($query) use ($end_date,$vehicle_id) {
            $query->where('start_date','<',$end_date)->where('status',1)->where('vehicle_id',$vehicle_id)->where('delete','!=',1);
          });

        $count = $promotion->count();

        if( $count > 0 ){
          $promotion_id = $promotion->select('id')->first();
          return back()->withErrors([ 'msg' => 'ไม่สามารถเพิ่มข้อมูลได้ เนื่องจากมี Promotion อื่นในช่วงเวลาที่ท่านเลือก [ Promotion ID:'.$promotion_id.' ]' ]);
        }

        $data = $request->all();
        $data['user_create'] = auth()->user()->name;
        Promotion::create($data);

        return redirect('en/promotion/create')->with('message','Create Promotion success.');
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
         $data = Promotion::find($id);
         $vehicles = VehicleBrand::where('status', 1)->get();
         return view('pages.promotion.edit', ['data' => $data,'vehicles' => $vehicles]);
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

         $start_date = $request->start_date;
         $end_date = $request->end_date;
         $vehicle_id = $request->vehicle_id;
         $promotion = Promotion
           ::where( function($query) use ($start_date,$vehicle_id,$id) {
             $query->where('end_date','>',$start_date)->where('status',1)->where('vehicle_id',$vehicle_id)->where('id','!=',$id)->where('delete','!=',1);
           })
           ->where( function($query) use ($end_date,$vehicle_id,$id) {
             $query->where('start_date','<',$end_date)->where('status',1)->where('vehicle_id',$vehicle_id)->where('id','!=',$id)->where('delete','!=',1);
           });
         $count = $promotion->count();

         if( $count > 0 ){
           $promotion_id = $promotion->select('id')->first();
           $msg = "ไม่สามารถเพิ่มข้อมูลได้ เนื่องจากมี Promotion อื่นในช่วงเวลาที่ท่านเลือก Promotion ID:".$promotion_id;
           return back()->withErrors([ "msg" => $msg ]);
         }

         $data = Promotion::where('id', $id);
         $reqAll = $request->all();
         unset($reqAll['_method']);
         unset($reqAll['_token']);
         unset($reqAll['id']);
         $reqAll['user_last_update'] = auth()->user()->name;
         $reqAll['is_vin'] = ( $request->is_vin == NULL ) ? 0 : $request->is_vin;
         $data->update($reqAll);

         return redirect('/en/promotion')->with('message', 'Updated.');
     }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($locale, $id)
    {
        promotion::where('id', $id)->update(['delete' => 1]);

        return redirect('en/promotion')->with('message','Delete success.');
    }
}
