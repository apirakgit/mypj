<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaction;
use App\Models\qr_payment_log;
use App\Models\Order;
use App\Models\RefundLog;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderMail;

class QRPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /*$testts = '{"payload":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjYXJkTm8iOiIiLCJjYXJkVG9rZW4iOiIiLCJsb3lhbHR5UG9pbnRzIjpudWxsLCJtZXJjaGFudElEIjoiNzY0NzY0MDAwMDA0MDk4IiwiaW52b2ljZU5vIjoiMjE2NTM1MjAyMDIzMjAwNjE1MDEiLCJhbW91bnQiOjEwMDAuMCwibW9udGhseVBheW1lbnQiOm51bGwsInVzZXJEZWZpbmVkMSI6IiIsInVzZXJEZWZpbmVkMiI6IiIsInVzZXJEZWZpbmVkMyI6IiIsInVzZXJEZWZpbmVkNCI6IiIsInVzZXJEZWZpbmVkNSI6IiIsImN1cnJlbmN5Q29kZSI6IlRIQiIsInJlY3VycmluZ1VuaXF1ZUlEIjoiIiwidHJhblJlZiI6IjIyMzE5MDU0NCIsInJlZmVyZW5jZU5vIjoiMTcxOTYxNzczIiwiYXBwcm92YWxDb2RlIjoiMDAwMUdSIiwiZWNpIjoieHgiLCJ0cmFuc2FjdGlvbkRhdGVUaW1lIjoiMjAyMzAxMTUyMDA2NDEiLCJhZ2VudENvZGUiOiJFTVZRUiIsImNoYW5uZWxDb2RlIjoiRVRRIiwiaXNzdWVyQ291bnRyeSI6IiIsImlzc3VlckJhbmsiOiIiLCJpbnN0YWxsbWVudE1lcmNoYW50QWJzb3JiUmF0ZSI6bnVsbCwiaWRlbXBvdGVuY3lJRCI6IiIsInBheW1lbnRTY2hlbWUiOiJFUSIsInJlc3BDb2RlIjoiMDAwMCIsInJlc3BEZXNjIjoiU3VjY2VzcyJ9.caIS-bQAYhtUpvl2hXCmnc2iXRAL7B6RjiIb6XPTxc0"}';
        $str_json = json_decode($testts);
        $payload = $str_json->payload;
        $secretkey = "AAEE3001B9BB2C84A74C834E1410E7D619F78850EBFB9526B335D02E7348C615";
        $decodedPayload = JWT::decode($payload, new Key($secretkey, 'HS256'));
        dd($decodedPayload);*/

        //return view( 'topup.qr_payment'); // skill call 2c2p for test UI
        $newDateTime = Carbon::now()->addMinutes(5);
        $expire_qr = $newDateTime->format('Y-m-d H:i:s');

        $order_id = $request->order_id ?? '';
        $amount = $request->amount;
        $amount = substr((int)$amount,0,-2) . '.00';

        //$secretkey = "CD229682D3297390B9F66FF4020B758F4A5E625AF4992E5D75D311D6458B38E2";
        $secretkey = "AAEE3001B9BB2C84A74C834E1410E7D619F78850EBFB9526B335D02E7348C615";
        $invoiceNo = time();
        //$merchantID = "JT04";
        $merchantID = "764764000004098";
        $payload = array(
          "merchantID" => $merchantID,
          "invoiceNo" => $order_id,
          "description" => "Evolt Topup",
          "amount" => $amount,
          "currencyCode" => "THB",
          "paymentChannel" => ["QR"],
          "paymentExpiry" => $expire_qr,
          //"backendReturnUrl" => "https://topup.evolt.co.th/api/qr-payment-response"
          "backendReturnUrl" => env('APP_URL_TOPUP') . '/api/qr-payment-response'
        );
        $jwt = JWT::encode($payload, $secretkey, 'HS256');
        $data = [ 'payload' => $jwt ];
        $response = $this->http("paymentToken",$data);

        //DECODE
        $decoded = json_decode($response,true);
        $payloadResponse = $decoded['payload'];

        //$decodedPayload = JWT::decode($payloadResponse, $secretkey, array('HS256'));
        $decodedPayload = JWT::decode($payloadResponse, new Key($secretkey, 'HS256'));
        $decoded_array = (array) $decodedPayload;

        $paymentToken = $decoded_array['paymentToken'];
        //do payment
        $data = [
    			'responseReturnUrl' => "https://evolt-top.codegears.co.th/en/qr-payment-response/",
    			'payment' => [
    				'code' => [
    					'channelCode' => "PPQR"
    				],
    				'data' => [
    					'name' => "Terrance Tay",
    					'email' => "terrance.tay@2c2p.com",
    					'qrType' => "BASE64",
    					'paymentExpiry' => "2022-12-13 12:00:00",
    				]
    			],
    			'clientIP' => "175.144.230.170",
    			'paymentToken' => $paymentToken,
    			'locale' => "en",
    			'clientID' => "30c7cf51-75c4-4265-a70a-effddfbbb0ff"
    		];

        $response = $this->http("payment",$data);

        $response = json_decode($response,true);
        $response['paymentToken'] = $paymentToken;
        $response['invoiceNo'] = $invoiceNo;
        $response['merchantID'] = $merchantID;
        $response['order_id'] = $order_id;
        $response['amount'] = $amount;
        //$qr_url = $response['data'];
        return view( 'topup.qr_payment', ['data' => $response] );

    }

    public function qr_callback(Request $request){

      return redirect( env('APP_URL_TOPUP') . '/' . app()->getLocale() )->with('success' , 'Payment process successful');

      /*$today = date('Y-m-d H:i:s');

      $tran = new Transaction();
      $tran->transaction_ref = '-';
      $tran->invoice_no = $request->invoiceNo;;
      $tran->order_id = $request->order_id;
      $tran->merchant_id = 'JT04';
      $tran->request_timestamp = $today;
      $tran->currency = '764';
      $tran->amount = $request->amount;
      $tran->transaction_datetime = $today;
      $tran->payment_channel = $request->channelCode;
      $tran->payment_status = '000';
      $tran->channel_response_code = '00';
      $tran->channel_response_desc = 'Approved';
      $tran->masked_pan = '-';

      $tran->save();

      if($tran){
        return redirect( env('APP_URL_TOPUP') . '/' . app()->getLocale() )->with('success' , 'Payment process successful');
      }

      return redirect( env('APP_URL_TOPUP') . '/' . app()->getLocale()  )->withErrors(['msg', 'Payment process fail']);
      */

    }

    public function qr_response(Request $request){

      $str_json = json_encode($request->all());
      $data = [
        'response' => $str_json
      ];

      qr_payment_log::create($data);

      $payload = $request->payload ?? '';

      $secretkey = "AAEE3001B9BB2C84A74C834E1410E7D619F78850EBFB9526B335D02E7348C615";

      if($payload != ''){

        $decodedPayload = JWT::decode($payload, new Key($secretkey, 'HS256'));

        if($decodedPayload->respCode == '0000'){

          $amount = str_pad($decodedPayload->amount . '00', 12, "0", STR_PAD_LEFT);
          //return response()->json($amount);
          $today = date('Y-m-d H:i:s');
  		  $order_id = $decodedPayload->invoiceNo;

		  $count_order_id = Transaction::where('order_id', $order_id)->count();

		  if($count_order_id == 0){

			$tran = new Transaction();
			$tran->transaction_ref = $decodedPayload->referenceNo;
			$tran->order_id = $order_id;
			$tran->merchant_id = $decodedPayload->merchantID;
			$tran->request_timestamp = $today;
			$tran->currency = $decodedPayload->currencyCode;
			$tran->amount = $amount;
			$tran->transaction_datetime = $today;
			$tran->payment_channel = 'PPQR';
			$tran->payment_status = '000';
			$tran->channel_response_code = '00';
			$tran->channel_response_desc = 'Approved';
			$tran->masked_pan = '-';

			$tran->save();

      		$order = Order::where('orders.order_id',$order_id)
      		  ->join('customers','customers.email','orders.email')
      		  ->select('customers.virta_id','orders.amount','orders.discount','orders.topup_amount','orders.bonus','orders.free_topup','orders.email','orders.name')
      		  ->first();

      		$mail = $order->email;
      		$amount_sub = number_format(substr($order->amount, 0 ,-2));
      		$mail_data = [
      			'name' => $order->name,
      			'order_id' => $order_id,
      			'transaction_datetime' => $today,
      			'amount' => $amount_sub
      		];

      		$virta_id = $order->virta_id;
      		$amount_order = $order->amount;
      		$discount = $order->discount;
      		$topup_amount = $order->topup_amount;

      		$virta_refund = $this->virta_refund($virta_id, $amount_order, $order_id, $today, $discount, $topup_amount, $order->bonus, $order->free_topup);

      		if($virta_refund){
      		  Order::where('order_id',$order_id)->update(['status' => 1]);
      		}

      		Mail::to($mail)->send(new OrderMail($mail_data));

		  }

        }

      }

    }

    public function get_transaction(Request $request){

      $validator = Validator::make($request->all(), [
          'paymentToken' => 'required',
      ]);

      if($validator->fails()){
        return response()->json([
          'status' => false,
          'errors' => $validator->errors()
        ]);
      }

      $data = [
        'paymentToken' => $request->paymentToken,
        'additionalInfo' => true
      ];
      $response = $this->http("transactionStatus",$data);
      $response = json_decode($response,true);

      return response()->json([
        'status' => true,
        'response' => $response,
      ]);

    }

    public function http($path, $data){

      //$end_point = "https://sandbox-pgw.2c2p.com/payment/4.1/"; //dev
      $end_point = "https://pgw.2c2p.com/payment/4.1/"; //prod
      $url = $end_point . $path;
	    $response = Http::post( $url, $data );

      return $response->body();

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
