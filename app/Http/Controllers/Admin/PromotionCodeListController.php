<?php

namespace App\Http\Controllers\Admin;

use App\Models\PromotionCodeList;
use App\Models\PromotionCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class PromotionCodeListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PromotionCodeList  $promotionCodeList
     * @return \Illuminate\Http\Response
     */
    public function show($localte, $id)
    {
        $pro_code = PromotionCode::find($id);
        $datas = PromotionCodeList::join('customers as c','c.id','promotion_code_lists.customer_id')
          ->where('promotion_code_lists.promotion_code_id',$id)
          ->where('promotion_code_lists.status', 1)
          ->select('c.f_name','c.l_name',
            DB::raw("LPAD(c.tel,10,'0') tel"),
            'c.email','promotion_code_lists.created_at','promotion_code_lists.id')
          ->paginate(10);
        return view('pages.promotion_code_list.home',['datas'=>$datas,'promotioncode'=>$pro_code]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PromotionCodeList  $promotionCodeList
     * @return \Illuminate\Http\Response
     */
    public function edit(PromotionCodeList $promotionCodeList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PromotionCodeList  $promotionCodeList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PromotionCodeList $promotionCodeList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PromotionCodeList  $promotionCodeList
     * @return \Illuminate\Http\Response
     */
    public function destroy($localte, $id,Request $request)
    {
        PromotionCodeList::where('id',$id)->update(['status' => 0]);
        return redirect()->back()->with('message','Delete success.');
    }
}
