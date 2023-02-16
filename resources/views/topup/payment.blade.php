<style>
	.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>

<div class="loader" style="position: absolute; top: 50%; left: 50%; margin: -50px 0 0 -50px;"></div>

<?php

	$order_id = app('request')->input('order_id');
	$amount = app('request')->input('amount');

	//Merchant's account information
	//$merchant_id = "764764000004098";		//Get MerchantID when opening account with 2C2P
	$merchant_id = env('2C2P_MERCHANT_ID');		//Get MerchantID when opening account with 2C2P
	//$secret_key = "AAEE3001B9BB2C84A74C834E1410E7D619F78850EBFB9526B335D02E7348C615";	//Get SecretKey from 2C2P PGW Dashboard
	$secret_key = env('2C2P_SECRET_KEY');		//Get SecretKey from 2C2P PGW Dashboard

	//Transaction information
	$payment_description  = 'Evolt Topup';
	$currency = env('2C2P_CURRENTCY');

	//Payment Options
	$payment_option = "CC";	//Customer Payment Options

	$invoice_no = "";
	$customer_email = "";
	$pay_category_id = "";
	$promotion = "";
	$user_defined_1 = "";
	$user_defined_2 = "";
	$user_defined_3 = "";
	$user_defined_4 = "";
	$user_defined_5 = "";
	$result_url_2 = "";
	$enable_store_card = "";
	$stored_card_unique_id = "";
	$request_3ds = "";
	$recurring = "";
	$order_prefix = "";
	$recurring_amount = "";
	$allow_accumulate = "";
	$max_accumulate_amount = "";
	$recurring_interval = "";
	$recurring_count = "";
	$charge_next_date = "";
	$charge_on_date = "";
	$ipp_interest_type = "";
	$payment_expiry = "";
	$default_lang = "";
	$statement_descriptor = "";
	$use_storedcard_only = "";
	$tokenize_without_authorization = "";
	$product = "";
	$ipp_period_filter = "";
	$sub_merchant_list = "";
	$qr_type = "";
	$custom_route_id = "";
	$airline_transaction = "";
	$airline_passenger_list = "";
	$address_list = "";

	//Request information
	$version = "8.5";
	//$payment_url = "https://t.2c2p.com/RedirectV3/payment";
	$payment_url = env('2C2P_URL');
	$result_url_1 = env('APP_URL_TOPUP') . "/" . app()->getLocale() . "/redirect2c2p";

	//Construct signature string
	$params = $version . $merchant_id . $payment_description . $order_id . $invoice_no .
	$currency . $amount . $customer_email . $pay_category_id . $promotion . $user_defined_1 .
	$user_defined_2 . $user_defined_3 . $user_defined_4 . $user_defined_5 . $result_url_1 .
	$result_url_2 . $enable_store_card . $stored_card_unique_id . $request_3ds . $recurring .
	$order_prefix . $recurring_amount . $allow_accumulate . $max_accumulate_amount .
	$recurring_interval . $recurring_count . $charge_next_date. $charge_on_date . $payment_option .
	$ipp_interest_type . $payment_expiry . $default_lang . $statement_descriptor . $use_storedcard_only .
	$tokenize_without_authorization . $product . $ipp_period_filter . $sub_merchant_list . $qr_type .
	$custom_route_id . $airline_transaction . $airline_passenger_list . $address_list;

	$hash_value = hash_hmac('sha256',$params, $secret_key,false);	//Compute hash value
	echo '<html>
	<body>
	<div style="visibility: hidden;">
	<form id="myform" method="post" action="'.$payment_url.'">
		<input type="hidden" name="version" value="'.$version.'"/>
		<input type="hidden" name="merchant_id" value="'.$merchant_id.'"/>
		<input type="hidden" name="currency" value="'.$currency.'"/>
		<input type="hidden" name="result_url_1" value="'.$result_url_1.'"/>
		<input type="hidden" name="enable_store_card" value="'.$enable_store_card.'"/>
		<input type="hidden" name="request_3ds" value="'.$request_3ds.'"/>
		<input type="hidden" name="payment_option" value="'.$payment_option.'"/>
		<input type="hidden" name="hash_value" value="'.$hash_value.'"/>
		PRODUCT INFO : <input type="text" name="payment_description" value="'.$payment_description.'"  readonly/><br/>
		ORDER NO : <input type="text" name="order_id" value="'.$order_id.'"  readonly/><br/>
		AMOUNT: <input type="text" name="amount" value="'.$amount.'" readonly/><br/>
		<input type="submit" value="Confirm" />
	</form>
	</div>
	<script type="text/javascript">
		document.forms.myform.submit();
	</script>
	</body>
	</html>';
?>
