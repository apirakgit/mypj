<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\PromotionCodeList;
use App\Models\Customers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApproveMail;
use App\Mail\RejectMail;
use Illuminate\Support\Facades\DB;
use App;
use App\http\Controllers\PaymentController;
use Illuminate\Support\Facades\Http;
use App\Models\RefundLog;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($locale)
    {
        if (! in_array($locale, ['en', 'th'])) {
            abort(400);
        }

        App::setLocale($locale);
        return view('topup.create');
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
      $request->validate([
        'name' => 'required',
        'email' => 'required',
        'tel' => 'required|size:10',
        'amount' => 'required',
      ]);

      $now = Carbon::now();
      //$unique_code = $now->format('YmdHisu');
      $unique_code = $now->format('usYHidm');
      $status = 0;
      if($request->free_topup != '' && $request->amount == '000000000000'){
        $status = 1;
      }

      $code_list_id = '';

      if($request->promotion_code_id != ''){
        $p_code_list = PromotionCodeList::create([
          'promotion_code_id' => $request->promotion_code_id,
          'customer_id' => $request->customer_id,
        ]);
        $code_list_id = $p_code_list->id;
      }

      //dd('code list',$code_list_id);

      $order = new Order();
      $order->order_id = $unique_code;
      $order->name = $request->name;
      $order->email = $request->email;
      $order->tel = $request->tel;
      $order->amount = $request->amount;
      $order->discount = $request->discount;
      $order->topup_amount = $request->topup_amount;
      $order->bonus = $request->bonus;
      $order->free_topup = $request->free_topup;
      $order->promotion_name = $request->promotion_name;
      $order->promotion_code = $request->promotion_code;
  	  if($code_list_id != ''){
  		    $order->promotion_code_list_id = $code_list_id;
  	  }

      $order->status = $status;
      $order->save();

      $data = [
        'order_id' => $order->order_id,
        'amount' => $request->amount,
        'topup_amount' => $request->topup_amount
      ];

      if($request->free_topup != '' && $request->amount == '000000000000'){ // promotion code free only

        $tran = new Transaction();
        $tran->transaction_ref = '-';
        $tran->merchant_id = '-';
        $tran->currency = '-';
        $tran->amount = $request->amount;
        $tran->channel_response_code = '00';
        $tran->payment_channel = '-';
        $tran->payment_status = '-';
        $tran->channel_response_desc = '-';
        $tran->masked_pan = '-';
        $tran->request_timestamp = date('Y-m-d H:i:s');
        $tran->transaction_datetime = date('Y-m-d H:i:s');
        $tran->order_id = $unique_code;
        $tran->save();

        $cus = Customers::where('email', $request->email)->select('virta_id')->first();
        $topup_amount = $request->free_topup.'00';
        $virta_id = $cus->virta_id;
        $this->virta_refund($virta_id,'0', $order->order_id, date('Y-m-d H:i:s'),'0', $topup_amount,'0',$request->free_topup);

        return redirect( env('APP_URL_TOPUP') . '/' . app()->getLocale() )->with('success' , 'Payment process successful');
      }

      //return redirect( env('APP_URL_TOPUP') . '/' . app()->getLocale() .'/payment' )->with('data' , $data);
      return view('topup.select_payment',['data' => $data]);
    }

	public function auth_virta(){

      $response = Http::withHeaders([
       'x-api-key' => env('VIRTA_API_KEY')

      ])->post( env('VIRTA_URL') . 'auth', [
       'username' => env('VIRTA_USER'),
       'password' => env('VIRTA_PASS'),
      ]);

      return $response['access_token'];
    }

	public function virta_refund($id = 0, $amount = 0, $transaction_ref = '', $timestamp = '', $discount = 0, $topup_amount = 0, $bonus = 0, $free_topup = 0){

      //$amount = substr($amount,0,-2);
      //description = [4776927/2022-03-29 09:51:14] or [4776927/2022-03-29 09:51:14/200 Discount]
      $description = '';
      $res_status = 0;

      try {

        $description = $transaction_ref.'/'.$timestamp;

        if($discount > 0){
          $description = $transaction_ref.'/'.$timestamp.'/'.$discount.' Discount';
        }
        if($bonus > 0){
          $description .= '/' . $bonus . ' Bonus';
        }
        if($free_topup > 0){
          $description .= '/' . $free_topup . ' Free Top-up';
        }
        $body = [
          'amount' => (int) $topup_amount,
          'description' => $description,
        ];
        $path = 'customers/'.$id.'/billing/refund';

        $response = Http::withToken( $this->auth_virta() )->withHeaders([
            'x-api-key' => env('VIRTA_API_KEY')
        ])->post( env('VIRTA_URL') . $path, $body);
        $res_status = $response->status();

        $refund_log = [
          'virta_id' => $id,
          'amount' => (int) $topup_amount,
          'transaction_ref' => $transaction_ref,
          'description' => $description,
          'status_code' => $res_status,
        ];

        RefundLog::create($refund_log);

      } catch (\Exception $e) {

        $refund_log = [
          'virta_id' => $id,
          'amount' => (int) $topup_amount,
          'transaction_ref' => $transaction_ref,
          'description' => $e->getMessage(),
          'status_code' => $res_status,
        ];

        RefundLog::create($refund_log);

      }

      if( $response->status() == 200 ){
        return true;
      }
      return false;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      return view('topup.create');
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

    public function changeStatus(Request $request)
    {
        $userId = Auth::id();
        $massage ="";
        if (Auth::check()) {
          $request->validate([
            'order_id' => 'required',
            'status' => 'required',
          ]);

          $order = Order::where('order_id', $request->order_id);
          $order->status = $request->status;
          Order::where('order_id', $request->order_id)
            ->update([
              'status' => $request->status,
              'approve_by' => $userId,
              'approve_date' => now(),
              'updated_at' => now()
            ]);

            $res = DB::select('select o.email, o.name, o.amount, t.transaction_datetime
            from orders o, transactions t
            where t.order_id = o.order_id
            and o.order_id = ?', [$request->order_id]);
            $mail = $res[0]->email;

			$amount_sub = number_format(substr($res[0]->amount, 0 ,-2));

			$mail_data = [
				'name' => $res[0]->name,
				'amount' => $amount_sub,
				'order_id' => $request->order_id,
				'transaction_datetime' => $res[0]->transaction_datetime
			];

          if($request->status == 1){

            $massage = "Approve";

            Mail::to($mail)->send(new ApproveMail($mail_data));

          }elseif($request->status == 9){

            $massage = "Reject";

            Mail::to($mail)->send(new RejectMail($mail_data));

          }

          return redirect()->back()->with('success',"$massage successfully.");
      }else{
          return redirect()->back()->with('error','No Auth');
      }
  }
}
