<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta property="og:url" content="https://topup.evolt.co.th" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="EvoltTopup" />
    <!-- <meta property="og:description" content="" /> -->
    <meta property="og:image" content="https://topup.evolt.co.th/images/logo_evolt.png" />
    <meta property="og:image:width" content="200" />
    <meta property="og:image:height" content="200" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <title>{{ config('app.name', 'EVolt Top up') }}</title>

    <!-- Favicon -->
    <link href="{{ asset('argon') }}/img/brand/favicon.png" rel="icon" type="image/png">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;500&display=swap" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/mystyle.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">

        <main class="main-topup">
            @yield('content')
        </main>
    </div>
    <script>

    $(function(){

      //$('#promotion-fail').modal('show');
      $('.cf_sum').on('click', function(){
        $(this).css('pointer-events','none');
      });

      $('#select_prom').on('change', function() {

        var type = this.value.substring(0,1);
        var discount = this.value.substring(1);
        var l_amount = $('#l_amount').html(); //จำนวนเติม

        if(type == 'B'){
          $('#promotion_code').val();
          $('#bonus').val();
          var total = $('#l_amount').html();
          $('#l_amount2').html(total);
          total = parseInt(total.replace(/\,/g, ''), 10);
          var show_discount = total * discount / 100;
          total = total - (total * discount / 100);
          $('#l_total').html(total);
          $('#l_promotion').html($(this).find("option:selected").text());
          $('#l_discount').html('<span style="color:red">-</span> ' + show_discount);

        }else{

          var total = $('#l_amount').html();
          $('#l_total').html(total);
          total = parseInt(total.replace(/\,/g, ''), 10);
          var show_discount = total * discount / 100;
          total = total + (total * discount / 100);
          $('#l_amount2').html(total);
          $('#l_promotion').html('');
          $('#l_discount').html('');
          $('#l_promotion_bonus').html($(this).find("option:selected").text());
          $('#l_discount_bonus').html(show_discount);
        }

      });

    });

    function check_promotion_code(){

      $('#txt_promotion_code').css('display','none');
      $('#txt_promotion_code_fail').css('display','none');
      $('#txt_promotion_code_fail').html('');
      $('#txt_promotion_code').html('');
      $('#vector').css('display','none');
      var locale = '{{app()->getLocale()}}';

	  var url = "{{ env('APP_URL_TOPUP') . '/customer/check_promotion_code' }}";
	  console.log('url',url);
      $.ajax({
        method: "POST",
        async: false,
        url: url,
        data: {
          code : $('#form_promotion_code').val(),
          email : $('#form_email').val(),
          "_token": "{{ csrf_token() }}",
        }
      }).done(function( res ) {
        console.log('check_promotion_manual', res);
        if(res['data'] > 0){
          $('#txt_promotion_code').css('display','block');
          $('#vector').css('display','block');
          if(locale == 'en'){
            $('#txt_promotion_code').html(res['res']['name_en']);
          }else{
            $('#txt_promotion_code').html(res['res']['name']);
          }

        }else{
          if(res['msg'] != ''){
            $('#txt_promotion_code_fail').html(res['msg']);
            $('#txt_promotion_code_fail').css('display','block');
          }else{
            $('#txt_promotion_code_fail').html('ไม่พบโปรโมชั่นโค้ดนี้');
            $('#txt_promotion_code_fail').css('display','block');
          }

        }

      });
    }

    function check_promotion_code_submit(){
      var promotion_code;
	  var url = "{{ env('APP_URL_TOPUP') . '/customer/check_promotion_code' }}";
      $.ajax({
        method: "POST",
        async: false,
        url: url,
        data: {
          code : $('#form_promotion_code').val(),
          email : $('#form_email').val(),
          "_token": "{{ csrf_token() }}",
        }
      }).done(function( res ) {
        console.log('check_promotion_code',res);
        promotion_code = res;
      });

      return promotion_code;
    }

    function check_dup_email(email){
      var cnt;
      var flg = false;
      $.ajax({
        method: "GET",
        async: false,
        //url: "{{ url('customer/check_dup_email') }}",
        url: "{{ env('APP_URL_TOPUP') . '/customer/check_dup_email_topup' }}",
        data: {
          email : email
        }
      }).done(function( res ) {
          if(res['count'] == 0){
            $("#modal-empty-email").modal();
            flg = false;
          }else{
            flg = true;
          }

      });

      return flg;

    }

    function check_promotion(email){
      var cnt;
      var discount;
      var promotion_name;
      var locale = '{{app()->getLocale()}}';
      const res_promotion = [];

      $.ajax({
        method: "GET",
        async: false,
        url: "{{ env('APP_URL_TOPUP') . '/customer/check_promotion' }}",
        data: {
          email : email,
          locale : locale
        }
      }).done(function( res ) {
          console.log('check_promotion',res);
          res_promotion['discount'] = res['discount'];
          res_promotion['promotion_name'] = res['promotion_name'];
      });
      return res_promotion;

    }

    function getName(email){

      $.ajax({
        method: "GET",
        async: false,
        url: "{{ env('APP_URL_TOPUP') . '/customer/getname' }}",
        data: {
          getname : email
        }
      }).done(function( res ){
        console.log('customer', res);
        $('#name').val(res['name']);
        $('#l_name').html(res['name']);
        $('#name').val(res['name']);
        $('#customer_id').val(res['id']);
      });
    }

    function cf_sum_sell(){

      var select_pro = $('#select_prom').val();
      var type = select_pro.substring(0,1);
      $('#select_prom').css('display','none');
      if(type == 'B'){
        $('.sum_sell').css('display','flex');
        $('#bonus').val(0);
        $('#promotion_name').val('');
        $('#promotion_code').val('');
        $('#promotion_code_id').val('');
      }

      if(type == 'C'){
        $('.sum_bonus').css('display','flex');
        $('#discount').val(0);
      }

      $('.cf_sum_sell').css('display','none');
      $('.cf_sum').css('display','block');

    }

    function validate_form(){

        $('#l_discount').html('');
        $('#l_promotion').html('');
        $('#l_discount_bonus').html('');
        $('#l_promotion_bonus').html('');

        $('#select_prom').css('display','none');
        var locale = '{{app()->getLocale()}}';
        //get data form
        var name = $('#form_name');
        var email = $('#form_email');
        var tel = $('#form_tel');
        var amount = $("input[name='form_amount']:checked").val();

        //fotmat validate
        var regName = /^[a-zA-Zก-๏\s]+$/;
        var regex_mail = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        var regex_tel = /([0-9]{10})|(\([0-9]{3}\)\s+[0-9]{3}\-[0-9]{4})/;
        var res_mail = regex_mail.test(email.val());
        var res_tel = regex_tel.test(tel.val());

        if(res_mail == false){
          email.focus();
          $('#txt_error_email').html('{{ __('message.frm_email_validate')}}');
          $('#form_email').css('background-color','#FDF2F3');
          return false;
        }else{
          $('#txt_error_email').html('');
          $('#form_email').css('background-color','#f0f4f8');
        }

        if(res_tel == false || tel.val().substr(0, 1) != 0){
          tel.focus();
          $('#txt_error_tel').html('{{ __('message.frm_tel_validate')}}');
          $('#form_tel').css('background-color','#FDF2F3');
          return false;
        }else{
          $('#txt_error_tel').html('');
          $('#form_tel').css('background-color','#f0f4f8');
        }

        getName(email.val());

        var promotion_code_bonus = 0;
        var promotion_code_free = 0;
        var free_topup_flg = true;
        var promotion_code_name = '';
        chk = check_dup_email(email.val());
        if(chk == false){
          return false;
        }

		    $('.sum_bonus').css('display','none');
		    $('.sum_sell').css('display','none');

        //promotion code
        var getPromotionCode = check_promotion_code_submit();

        if(getPromotionCode['data'] == 1){

          if(getPromotionCode['res']['type'] == 'bonus_code'){
            promotion_code_bonus = 1;
          }
          if(getPromotionCode['res']['type'] == 'free_topup'){
            promotion_code_free = 1;
            free_topup_flg = false;
          }

          if( locale == 'en'){
            promotion_code_name = getPromotionCode['res']['name_en'];
          }else{
            promotion_code_name = getPromotionCode['res']['name'];
          }
        }
        if(getPromotionCode['data'] == 0 && $('#form_promotion_code').val() != ''){
          $('#promotion-fail').modal('show');
          return false;
        }

        if(free_topup_flg){
          if (!amount){
            $('#txt_error_amount').html('{{ __('message.frm_amount_validate')}}');
            return false;
          }else{
            $('#txt_error_amount').html('');
          }
        }

        //input data to popup
        chk = check_dup_email(email.val());

        if(chk == true){

          var amount_cal = 0;

          if(typeof amount != "undefined"){
            amount_cal = parseInt(amount.substring(10, 0)); //0000000100000
          }

          var amount_payment = amount_cal; //1000
          var amount_topup = amount_cal; //1000
          var promotion_bran = 0;


          //hidden
          $('#email').val(email.val());
          $('#tel').val(tel.val());

          //show
          $('#l_name').html(name.val());
          $('#l_email').html(email.val());
          $('#l_tel').html(tel.val());

          //promotion brand
          var res_promotion = check_promotion(email.val());
          var promotion_name = res_promotion['promotion_name'];

          if(promotion_name != ''){
            promotion_bran = 1;
			promotion_code_name = promotion_name;
          }

          /////////////////////////////////////////////////////////////////////////
          var discount = res_promotion['discount'];
          var discount_cal = discount / 100 * amount_cal;
          var amount_bonus = 0;
          var amount_free_topup = 0;
		      var bonus_in_select = '';
		      var discount_in_select = '';
    		  if(discount_cal > 0){
    			  bonus_in_select = '-' + discount_cal;
    		  }

          if( promotion_bran == 1 && getPromotionCode['data'] == 0 ){ // promotion brand อย่างเดียว
            $('#l_discount').html('<span style="color:red">-</span> '+discount_cal);
            $('#l_promotion').html(promotion_name);
            $('.sum_bonus').css('display','none');
            $('.sum_sell').css('display','flex');
            amount_payment = amount_payment - discount_cal;
          }
          ///////////////////////////////////////////////////////////////////////////
          if( promotion_bran == 1 && promotion_code_bonus == 1 ){ // promotion brand + bonus

            $('#select_prom').find('option').remove().end();
            $('.sum_sell').css('display','none'); //ส่วนลด mark1
            $('.sum_bonus').css('display','none'); //ส่วนลด mark1
            $('#select_prom').css('display','block'); //select เลือก promotion
            $('.cf_sum_sell').css('display','block'); //ปุ่มตกลง
            $('.cf_sum').css('display','none');

			      amount_bonus = amount_cal * getPromotionCode['res']['price'] / 100;
            amount_topup = amount_cal + amount_bonus;
      			if( amount_bonus > 0){
      				discount_in_select = amount_bonus;
      			}
            $("#select_prom").append(new Option(promotion_code_name + ' ( ' + discount_in_select + ' )', 'C'+ getPromotionCode['res']['price']));
            $("#select_prom").append(new Option(promotion_name + ' ( ' + bonus_in_select + ' ) ', 'B' + discount));

            $('#l_discount_bonus').html(amount_bonus);
            $('#l_promotion_bonus').html(promotion_code_name);
          }
          /////////////////////////////////////////////////////////////////////////////
          if( promotion_bran == 1 && promotion_code_free == 1 ){ // promotion brand + free topup

            $('.sum_sell').css('display','flex');
            $('.sum_bonus').css('display','flex');

            $('#l_discount').html('<span style="color:red">-</span> '+discount_cal);
            $('#l_promotion').html(promotion_name);
            amount_payment = amount_payment - discount_cal;

            amount_free_topup = getPromotionCode['res']['price'];
            $('#l_discount_bonus').html(amount_free_topup);
            $('#l_promotion_bonus').html(promotion_code_name);
            amount_free_topup = getPromotionCode['res']['price'];
            amount_topup = amount_cal + amount_free_topup;
          }
          /////////////////////////////////////////////////////////////////////////////
          if( promotion_bran == 0 && promotion_code_bonus == 1 ){ // promotion bonus

            amount_bonus = amount_cal *  getPromotionCode['res']['price'] / 100;
            amount_topup =  amount_cal + amount_bonus;
            $('#l_discount_bonus').html(amount_bonus);
            $('#l_promotion_bonus').html(promotion_code_name);
            $('.sum_sell').css('display','none');
            $('.sum_bonus').css('display','flex');
          }
          /////////////////////////////////////////////////////////////////////////////
          if( promotion_bran == 0 && promotion_code_free == 1 ){ // promotion free topup

            amount_free_topup = getPromotionCode['res']['price'];
            amount_topup = amount_cal + amount_free_topup;
            $('#l_discount_bonus').html(amount_free_topup);
            $('#l_promotion_bonus').html(promotion_code_name);
            $('.sum_sell').css('display','none');
            $('.sum_bonus').css('display','flex');
          }

          //alert( promotion_bran + ' ' + promotion_code_bonus + ' ' + promotion_code_free);
			console.log(promotion_bran + ' ' + promotion_code_bonus + ' ' + promotion_code_free);
          var amount_pad = ('0000000000' + amount_payment).slice(-10) + '00';
          amount_topup_pad = ('0000000000' + amount_topup).slice(-10) + '00';
          $('#amount').val(amount_pad);
          $('#discount').val(discount_cal);
          $('#topup_amount').val(amount_topup_pad);
          $('#bonus').val(amount_bonus);
          $('#free_topup').val(amount_free_topup);
          $('#promotion_name').val(promotion_code_name);
          $('#promotion_code').val(getPromotionCode['res']['code']);
          $('#promotion_code_id').val(getPromotionCode['res']['p_code_id']);

          $('#l_amount').html(numberWithCommas(amount_cal)); //เติมจำนวน (บาท)
          $('#l_amount2').html(numberWithCommas(amount_topup)); //จำนวนเงินที่ใช้ได้จริง (บาท)
          $('#l_total').html(numberWithCommas(amount_payment)); //จำนวนเงินที่ต้องชำระ (บาท)

          $('.cf_sum').css('pointer-events','all');
          $('#comfirm-topup').modal('show');
        }

    }

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    </script>
</body>
</html>
