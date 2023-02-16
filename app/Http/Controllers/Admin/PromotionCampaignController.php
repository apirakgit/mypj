<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromotionCampaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class PromotionCampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = PromotionCampaign::where('delete', 0)->select('id','name','name_en','type','start_date','end_date','create_by','created_at',
          DB::raw("CASE WHEN status = 1 THEN 'Enable' ELSE 'Disable' END as status"),
          DB::raw("CASE WHEN repeat_status = 1 THEN 'Yes' ELSE 'No' END as repeat_status"),
        );

        if($request->name != ''){
          $name = $request->name;
          $query->where(function ($query) use ($name) {
              $query->where('name','like','%'.$name.'%');
              $query->orWhere('name_en','like','%'.$name.'%');
          });
        }
        if($request->type != ''){
          $query->where('type',$request->type);
        }
        if($request->repeat != ''){
          $query->where('repeat_status',$request->repeat);
        }
        if($request->status != ''){
          $query->where('status',$request->status);
        }
        if( $request->dates != '' ){
          $dates = explode("-",$request->dates);
          $start_date = date('Y-m-d 00:00:01', strtotime($dates[0]));
          $end_date = date('Y-m-d 23:59:59', strtotime($dates[1]));

          $query->whereBetween('created_at',[$start_date, $end_date]);
        }

        $query->orderBy('id','DESC');

        $datas = $query->paginate(10);

        return view('pages.promotion_campaign.home',['datas' => $datas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.promotion_campaign.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $data = $request->all();
      $data['create_by'] = auth()->user()->name;
      PromotionCampaign::create($data);

      return redirect('en/promotion_campaign/create')->with('message','Create Promotion Campaign success.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PromotionCampaign  $promotionCampaign
     * @return \Illuminate\Http\Response
     */
    public function show(PromotionCampaign $promotionCampaign)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PromotionCampaign  $promotionCampaign
     * @return \Illuminate\Http\Response
     */
    public function edit($locale, $id)
    {
        $data = PromotionCampaign::where('id', $id)->first();
        return view('pages.promotion_campaign.edit',['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PromotionCampaign  $promotionCampaign
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $locale, $id)
    {
        $update_data = $request->all();
        unset($update_data['_token']);
        unset($update_data['_method']);
        PromotionCampaign::where('id', $id)->update($update_data);
        return redirect('/en/promotion_campaign')->with('message', 'Updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PromotionCampaign  $promotionCampaign
     * @return \Illuminate\Http\Response
     */
    public function destroy($locale, $id)
    {
        PromotionCampaign::where('id', $id)->update(['delete' => 1]);

        return redirect('en/promotion_campaign')->with('message','Delete success.');
    }
}
