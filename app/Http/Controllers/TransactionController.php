<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Exports\TransactionExport;
use App\Models\User;
use Excel;
use Auth;
class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $role = auth()->user()->status;
      if($role != User::ADMIN_ACTIVE)
      {
        Auth::logout();
        return redirect('/auth/login')->withErrors(['msg' =>  'ท่านไม่ได้รับอนุญาตให้เข้าถึงระบบ กรุณาติดต่อ Manager Admin']);
      }
      $trans = DB::table('transactions')
        ->select('o.order_id','o.discount','o.topup_amount','o.amount','transactions.transaction_datetime','o.name','o.email','o.tel','o.bonus','o.free_topup','o.promotion_code','o.promotion_name',
          DB::raw('CASE WHEN o.status = 1 THEN "Approved" WHEN o.status = 9 THEN "Reject" ELSE "Pending" END status')
          ,'u.name as admin_name','o.approve_date',
          DB::raw('CASE WHEN transactions.channel_response_code = "00" THEN "Success" ELSE "Failed to top up" END channel_response_code' ),
          DB::raw('CASE WHEN transactions.payment_channel = "PPQR" THEN "QR Payment" ELSE "Credit / Debit" END payment_channel')
        )
        ->join('orders AS o', 'o.order_id', '=', 'transactions.order_id')
        ->leftjoin('users AS u', 'u.id', '=', 'o.approve_by')
        ->orderByDesc('transactions.id')
        ->paginate(20);
      return view('pages.transaction', ['trans' => $trans]);
    }

    public function search(Request $request)
    {
      //dd($request);
      $query = DB::table('transactions')
        ->select(
          DB::raw('CASE WHEN o.status = 1 THEN "Approved" WHEN o.status = 9 THEN "Reject" ELSE "Pending" END status'),

          DB::raw('CASE WHEN transactions.channel_response_code = "00" THEN "Success" ELSE "Failed to top up" END channel_response_code' ),
          'o.order_id',
          'transactions.transaction_datetime',
          DB::raw('CASE WHEN transactions.payment_channel = "PPQR" THEN "QR Payment" ELSE "Credit / Debit" END payment_channel'),
          'o.name',
          'o.email',
          'o.tel',
          'o.amount',
          'o.promotion_name',
          'o.promotion_code',
          'o.discount',
          'o.bonus',
          'o.free_topup',
          'o.topup_amount',
          'u.name as admin_name',
          'o.approve_date',
        )
        ->join('orders AS o', 'o.order_id', '=', 'transactions.order_id')
        ->leftjoin('users AS u', 'u.id', '=', 'o.approve_by');
        if($request->order_id != ''){
          $query->where('o.order_id','like','%'.$request->order_id.'%');
        }

        if($request->name != ''){
          $query->where('o.name','like','%'.$request->name.'%');
        }

        if($request->email != ''){
          $query->where('o.email','like','%'.$request->email.'%');
        }

        if($request->phone != ''){
          $query->where('o.tel','like','%'.$request->phone.'%');
        }

        if($request->name_admin != ''){
          $query->where('u.name','like','%'.$request->name_admin.'%');
        }

        if($request->amount != ''){
          $query->where('o.amount',$request->amount);
        }

        if($request->status != ''){
          $query->where('o.status',$request->status);
        }

        if($request->dates != ''){
          $dates = explode("-",$request->dates);
          $start_date = date('Y-m-d 00:00:01', strtotime($dates[0]));
          $end_date = date('Y-m-d 23:59:59', strtotime($dates[1]));

          $query->whereBetween('transaction_datetime',[$start_date, $end_date]);
        }

        $query->orderByDesc('transactions.id');

        if($request->has('exportExcel')){
          $trans = $query->get();
          return Excel::download(new TransactionExport($trans), 'Transactionlist.xlsx');
        }

        $trans = $query->paginate(50);

        return view('pages.transaction', ['trans' => $trans]);
    }

    public function exportIntoExcel(Request $request)
    {
      return Excel::download(new TransactionExport, 'Transactionlist.xlsx');
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
