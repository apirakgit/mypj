@extends('layouts.app2')

@section('content')
<section class="section-preview container" style="max-width: none;">
  <div class="row" style="">

    <section class="col-md-12 col-sm-5 col-12" style="background-color: white;text-align: center;">
      <article class="section-left-qr topup" style="height: 152px;">
          <div class="" style="padding-top: 36px;">
              <img src="{{ asset('images/logo_evolt.svg') }}" width="150"  />
          </div>
      </article>
    </section>

    <section class="col-md-12 col-sm-7 col-12" style="background: transparent linear-gradient(110deg, #F68F1E 3%, #F37323 42%, #EF4E2B 100%) 0% 0% no-repeat padding-box; height:100vh;text-align: -webkit-center;">

      <div class="col-md-6" style="margin-top: -40px;">
      <article class="information-box-r" style="padding: 18px 17px 8px 19px; text-align: initial;">
        <div class="row no-gutters">
          <form>
						<div class="row" >
	            <div class="col-md-8 col-8" style="margin-top: 6px;color: #6E6D7A;">
	                จำนวนเงินที่ต้องชำระ (บาท)
	            </div>
	            <div class="col-md-4 col-4" style="text-align: right;">
	                <label for="" id="l_total" style="color: #F79020;font-size: 21px;">
                    <?php
                      echo number_format((int) substr($data['amount'],0,-2));
                     ?>
                  </label>
	            </div>
	          </div>
          </form>
        </div>
      </article>

      <article class="amount-box-r" style="">
        <div class="row title-form">
          <div class="col-12 ">
            <h3 class="title-choose-pay">เลือกช่องทางการชำระ</h3>
            <div id="btn_qr_code" class="col-12 payment" style="text-align: initial;">
              <span class="payment-qr-img"><img src="{{ asset('images/qr_payment/QrCode.png') }}" alt="" width="32"> </span>
              <span class="payment-qr">ชำระด้วย QR Code</span>
            </div>
            <div id="btn_credit_card" class="col-12 payment" style="margin-bottom: 180px; text-align: initial;">
              <span class="payment-qr-img"><img src="{{ asset('images/qr_payment/CreditCard.png') }}" alt="" width="32"></span>
              <span class="payment-qr">บัตรเครดิต / เดบิต</span>
            </div>
          </div>
        </div>
      </article>

      </div>
      </section>

    </div>
  </div>
</section>
<script type="text/javascript">
  $(function(){
    $('#btn_credit_card').click(function(){
      window.location.href = "/th/payment?order_id={{$data['order_id']}}&amount={{$data['amount']}}";
    });
    $('#btn_qr_code').click(function(){
      window.location.href = "/th/qr-payment?order_id={{$data['order_id']}}&amount={{$data['amount']}}";
    });
  });

</script>

@endsection
