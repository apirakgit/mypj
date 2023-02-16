<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\RefundLog;
use App\Models\Customers;
use App\Models\Order;
use App\Models\PromotionCodeList;
class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
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

    public function virta_refund($id = 0, $amount = 0, $transaction_ref = '', $timestamp = '', $discount = 0, $topup_amount = 0, $bonus = 0, $free_topup = 0){

      //$amount = substr($amount,0,-2);
      //description = [4776927/2022-03-29 09:51:14] or [4776927/2022-03-29 09:51:14/200 Discount]
      $description = '';
      $res_status = 0;

      try {

        $description = $transaction_ref.'/'.$timestamp;

        if($discount > 0){
          $description .= '/' . $discount.' Discount';
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
          'virta_id' => $id ?? '',
          'amount' => (int) $topup_amount ?? '0',
          'transaction_ref' => $transaction_ref ?? '',
          'description' => $description ?? '',
          'status_code' => $res_status ?? '11',
        ];

        RefundLog::create($refund_log);

		if( $response->status() == 200 ){
			return true;
		}else{
			return false;
		}

      } catch (\Exception $e) {

        $refund_log = [
          'virta_id' => $id,
          'amount' => (int) $topup_amount,
          'transaction_ref' => $transaction_ref,
          'description' => $description . '-' . $e->getMessage(),
          'status_code' => $res_status,
        ];

        RefundLog::create($refund_log);

		return false;

      }

      return false;

    }

    public function index()
    {
        return view('topup.payment');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return "create";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      // In case customer click cancel button on 2c2p page.
      //dd($request->all());
      //if(!$request->get('transaction_ref') && !$request->get('invoice_no')){
      if(!$request->get('transaction_ref')){

          $order = Order::where('order_id',$request->order_id)->first();
          if($order){
            if($order->promotion_code_list_id != ''){
              PromotionCodeList::where('id',$order->promotion_code_list_id)->update(['status' => 0]);
            }
          }
          return redirect()->route('topup.index', app()->getLocale())->withErrors(['msg', 'Payment process fail']);
      }

      $request->validate([
        'transaction_ref' => 'required',
        'order_id' => 'required',
        'merchant_id' => 'required',
        'amount' => 'required',
      ]);

      $version = $_REQUEST["version"];
      $request_timestamp = $_REQUEST["request_timestamp"];
      $merchant_id = $_REQUEST["merchant_id"];
      $currency = $_REQUEST["currency"];
      $order_id = $_REQUEST["order_id"];
      $amount = $_REQUEST["amount"];
      $invoice_no = $_REQUEST["invoice_no"];
      $transaction_ref = $_REQUEST["transaction_ref"];
      $approval_code = $_REQUEST["approval_code"];
      $eci = $_REQUEST["eci"];
      $transaction_datetime = $_REQUEST["transaction_datetime"];
      $payment_channel = $_REQUEST["payment_channel"];
      $payment_status = $_REQUEST["payment_status"];
      $channel_response_code = $_REQUEST["channel_response_code"];
      $channel_response_desc = $_REQUEST["channel_response_desc"];
      $masked_pan = $_REQUEST["masked_pan"];
      $stored_card_unique_id = $_REQUEST["stored_card_unique_id"];
      $backend_invoice = $_REQUEST["backend_invoice"];
      $paid_channel = $_REQUEST["paid_channel"];
      $recurring_unique_id = $_REQUEST["recurring_unique_id"];
      $paid_agent = $_REQUEST["paid_agent"];
      $payment_scheme = $_REQUEST["payment_scheme"];
      $user_defined_1 = $_REQUEST["user_defined_1"];
      $user_defined_2 = $_REQUEST["user_defined_2"];
      $user_defined_3 = $_REQUEST["user_defined_3"];
      $user_defined_4 = $_REQUEST["user_defined_4"];
      $user_defined_5 = $_REQUEST["user_defined_5"];
      $browser_info = $_REQUEST["browser_info"];
      $ippPeriod = $_REQUEST["ippPeriod"];
      $ippInterestType = $_REQUEST["ippInterestType"];
      $ippInterestRate = $_REQUEST["ippInterestRate"];
      $ippMerchantAbsorbRate = $_REQUEST["ippMerchantAbsorbRate"];
      $payment_scheme = $_REQUEST["payment_scheme"];
      $process_by = $_REQUEST["process_by"];
      $sub_merchant_list = $_REQUEST["sub_merchant_list"];
      $hash_value = $_REQUEST["hash_value"];

      $checkHashStr = $version . $request_timestamp . $merchant_id . $order_id .
      $invoice_no . $currency . $amount . $transaction_ref . $approval_code .
      $eci . $transaction_datetime . $payment_channel . $payment_status .
      $channel_response_code . $channel_response_desc . $masked_pan .
      $stored_card_unique_id . $backend_invoice . $paid_channel . $paid_agent .
      $recurring_unique_id . $user_defined_1 . $user_defined_2 . $user_defined_3 .
      $user_defined_4 . $user_defined_5 . $browser_info . $ippPeriod .
      $ippInterestType . $ippInterestRate . $ippMerchantAbsorbRate . $payment_scheme .
      $process_by . $sub_merchant_list;

      $chk_order = DB::select('select order_id from transactions where order_id = ?', [$request->order_id]);
      $cnt_order = count($chk_order);

      $SECRETKEY = env('2C2P_SECRET_KEY');
      $checkHash = hash_hmac('sha256',$checkHashStr, $SECRETKEY,false);
      if($cnt_order > 0){
      //update
          DB::table('transactions')->where('order_id', '=', $request->order_id)
          ->update(array(
            'request_timestamp' => $request->request_timestamp,
            'transaction_datetime' => $request->transaction_datetime,
            'payment_channel' => $request->payment_channel,
            'payment_status' => $request->payment_status,
            'channel_response_code' => $request->channel_response_code,
            'channel_response_desc' => $request->channel_response_desc,
            'updated_at' => date('Y-m-d H:i:s')
          ));

          if($request->channel_response_code == '00'){
			      return redirect( env('APP_URL_TOPUP') . '/' . app()->getLocale() )->with('success' , 'Payment process successful');
          }else{
            $order = Order::where('order_id',$request->order_id)->first();
            if($order){
              if($order->promotion_code_list_id != ''){
                PromotionCodeList::where('id',$order->promotion_code_list_id)->update(['status' => 0]);
              }
            }
			      return redirect( env('APP_URL_TOPUP') . '/' . app()->getLocale()  )->withErrors(['msg', 'Payment process fail']);
          }

       }else {
         //create
        if(strcmp(strtolower($hash_value), strtolower($checkHash))==0){
          $tran = new Transaction();
          $tran->transaction_ref = $request->transaction_ref;
          $tran->order_id = $request->order_id;
          $tran->merchant_id = $request->merchant_id;
          $tran->request_timestamp = $request->request_timestamp;
          $tran->currency = $request->currency;
          $tran->amount = $request->amount;
          $tran->transaction_datetime = $request->transaction_datetime;
          $tran->payment_channel = $request->payment_channel;
          $tran->payment_status = $request->payment_status;
          $tran->channel_response_code = $request->channel_response_code;
          $tran->channel_response_desc = $request->channel_response_desc;
          $tran->masked_pan = $request->masked_pan;
          $tran->save();
          $res = DB::select('select email, name from orders where order_id = ?', [$tran->order_id]);
          $mail = $res[0]->email;
          $amount_sub = number_format(substr($request->amount, 0 ,-2));

          $mail_data = [
            'name' => $res[0]->name,
            'order_id' => $request->order_id,
            'transaction_datetime' => $request->transaction_datetime,
            'amount' => $amount_sub
          ];

          if($request->channel_response_code == '00'){ //success

            $order = Order::where('orders.order_id',$order_id)
              ->join('customers','customers.email','orders.email')
              ->select('customers.virta_id','orders.amount','orders.discount','orders.topup_amount','orders.bonus','orders.free_topup')
              ->first();
            $virta_id = $order->virta_id;
            $amount_order = $order->amount;
            $discount = $order->discount;
            $topup_amount = $order->topup_amount;
            $virta_refund = $this->virta_refund($virta_id, $amount_order, $order_id, $request_timestamp, $discount, $topup_amount, $order->bonus, $order->free_topup);

            if($virta_refund){
              Order::where('order_id',$order_id)->update(['status' => 1]);
            }

            Mail::to($mail)->send(new OrderMail($mail_data));
			      return redirect( env('APP_URL_TOPUP') . '/' . app()->getLocale()  )->with('success' , 'Payment process successful');

          }else{
            $order = Order::where('order_id',$request->order_id)->first();
            if($order){
              if($order->promotion_code_list_id != ''){
                PromotionCodeList::where('id',$order->promotion_code_list_id)->update(['status' => 0]);
              }
            }
            return redirect( env('APP_URL_TOPUP') . '/' . app()->getLocale()  )->withErrors(['msg', 'Payment process fail']);
          }

        }else{
          $order = Order::where('order_id',$request->order_id)->first();
          if($order){
            if($order->promotion_code_list_id != ''){
              PromotionCodeList::where('id',$order->promotion_code_list_id)->update(['status' => 0]);
            }
          }
		        return redirect( env('APP_URL_TOPUP') . '/' . app()->getLocale()  )->withErrors(['msg', 'Payment process fail']);
        }

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
        return "show";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return "edit";
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
        return "update";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return "destroy";
    }
}
