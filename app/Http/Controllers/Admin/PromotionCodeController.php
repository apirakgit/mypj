<?php

namespace App\Http\Controllers\Admin;

use App\Models\PromotionCode;
use App\Models\PromotionCampaign;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PromotionCodeImport;
use App\Exports\PromotionCodeExport;
use Illuminate\Support\Facades\DB;
use Validator;

class PromotionCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

      $pmcp_id = $request->pmcp_id;
      $pmcp = PromotionCampaign::find($pmcp_id);
      $pmc = PromotionCode::where('promotion_codes.promotion_campaign_id', $pmcp_id)
        ->select('promotion_codes.*',
          DB::raw("CASE WHEN promotion_codes.status = 1 THEN 'Enable' ELSE 'Disable' END status"),
          DB::raw("(SELECT COUNT(b.id) FROM promotion_code_lists b WHERE b.promotion_code_id = promotion_codes.id AND b.status = 1) AS active"),
      );
      if($request->code != ''){
        $pmc->where('code','like','%'.$request->code.'%');
      }
      $datas = $pmc->paginate(10);
      return view('pages.promotion_code.home',['datas' => $datas, 'pmcp' => $pmcp]);
    }

    public function exportPromotionCode($localte, $id){
        return Excel::download(new PromotionCodeExport($id), 'PromotionCodeExport.xlsx');
    }

    public function searchImport(Request $request){

      $pmcp_id = $request->pmcp_id;
	  $url = 'en/promotion_code?pmcp_id='.$pmcp_id.'&code='.$request->code;

      if($request->has('importExcel')){

		$validated = Validator::make($request->all(), [
			'file' => 'required|mimes:xlsx,csv',
		]);

		if ($validated->fails()) {
			return redirect($url)->withErrors($validated)->withInput();
		}

        $import = new PromotionCodeImport( $pmcp_id );
        Excel::import($import, $request->file('file')->store('temp'));
        if(isset($import->data_dup)){
          return redirect($url)->with('dup',$import->data_dup);
        }else{
          return redirect($url)->with('message','Import Promotion Code Success.');
        }

      }

      return redirect('en/promotion_code?pmcp_id='.$pmcp_id.'&code='.$request->code);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.promotion_code.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      $validated = Validator::make($request->all(), [
        'code' => 'required|unique:promotion_codes|max:255',
        'amount' => 'required',
    	]);

      $url = 'en/promotion_code/create?pmcp_id=' . $request->promotion_campaign_id;

      if ($validated->fails()) {
          return redirect($url)->withErrors($validated)->withInput();
      }

      $data = $request->all();
      PromotionCode::create($data);

      return redirect($url)->with('message','Create Promotion Code Success.');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PromotionCode  $promotionCode
     * @return \Illuminate\Http\Response
     */
    public function show($locale, $pmcp_id)
    {
        /*$pmcp = PromotionCampaign::find($pmcp_id);
        $pmc = PromotionCode::where('promotion_campaign_id', $pmcp_id)->paginate(10);

        $datas = $pmc;
        return view('pages.promotion_code.home',['datas' => $datas, 'pmcp' => $pmcp]);*/
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PromotionCode  $promotionCode
     * @return \Illuminate\Http\Response
     */
    public function edit($locale, $id)
    {
      $data = PromotionCode::where('id', $id)->first();
      return view('pages.promotion_code.edit',['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PromotionCode  $promotionCode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $locale, $id)
    {
      $pmcp_id = $request->promotion_campaign_id;
      $update_data = $request->all();
      unset($update_data['_token']);
      unset($update_data['_method']);
      PromotionCode::where('id', $id)->where('promotion_campaign_id',$pmcp_id)->update($update_data);
      return redirect('/en/promotion_code?pmcp_id='.$pmcp_id)->with('message', 'Updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PromotionCode  $promotionCode
     * @return \Illuminate\Http\Response
     */
    public function destroy(PromotionCode $promotionCode)
    {
        //
    }
}
