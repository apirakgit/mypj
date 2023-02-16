<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Partner;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Exports\PartnerAPIExport;
use Maatwebsite\Excel\Facades\Excel;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function generate_secret_key(){
      $secret_key = Str::random(60);
      return $secret_key;
    }

    public function index(Request $request)
    {
        $partner = Partner::where('del', 0)
          ->select('id','partner_id','partner_name','admin',DB::raw("CASE WHEN status = '1' THEN 'Enable' ELSE 'Disable' END status"),'created_at','last_connect')
          ->orderby('id','desc');
        if($request->partner_id != ''){
          $partner->where('partner_id','like','%'.$request->partner_id.'%');
        }
        if($request->partner_name != ''){
          $partner->where('partner_name','like','%'.$request->partner_name.'%');
        }

        $dataexcel = $partner->get();
        if($request->has('exportExcel')){
          return Excel::download(new PartnerAPIExport($dataexcel), 'PartnerAPI.xlsx');
        }

        $datas = $partner->paginate(10);

        return view('pages.partner.home', [ 'datas' => $datas ] );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $secret_key = $this->generate_secret_key();
        $max_id = Partner::max('id') + 1;
        $partner_id = 'P' . str_pad($max_id,4,"0",STR_PAD_LEFT);

        return view('pages.partner.create', ['secret_key' => $secret_key, 'partner_id' => $partner_id]);
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
        $data['admin'] = auth()->user()->name;
        Partner::create($data);

        return redirect('en/partner/create')->with('message','Create Partner success.');
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
    public function edit($locale,$id)
    {
        $data = Partner::find($id);
        return view('pages.partner.edit', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$locale,$id)
    {
        $upd = [
          'partner_name' => $request->partner_name,
          'secret_key' => $request->secret_key,
          'status' => $request->status
        ];

        Partner::where('id',$id)->update($upd);

        return redirect('/en/partner')->with('message', 'Updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($locale,$id)
    {
        Partner::where('id',$id)->update([ 'del' => 1 ]);

        return redirect('/en/partner')->with('message', 'Deleted.');
    }
}
