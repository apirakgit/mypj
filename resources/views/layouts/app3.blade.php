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
    <title>Register</title>

    <!-- Favicon -->
    <link href="{{ asset('argon') }}/img/brand/favicon.png" rel="icon" type="image/png">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;500&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!--<link href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css'rel='stylesheet'>-->
    <link href="{{ asset('css/mystyle2.css?v=1a0400649548') }}" rel="stylesheet">

    <!-- Script -->


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="//getbootstrap.com/2.3.2/assets/js/jquery.js"></script>
    <script src="//getbootstrap.com/2.3.2/assets/js/google-code-prettify/prettify.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" ></script>



</head>
<body>
    <div id="app">
      <main class="main-topup">
          @yield('content')
      </main>
    </div>
    <script type="text/javascript">
      $(function(){
        //$("#success-topup").modal();
        //$("#fail-vinno").modal({backdrop: 'static', keyboard: false});

      });
    </script>
    <script>

      var error_flg = 0;

      $(function(){

        $('#address').keyup( function(e){
            var cnt_addr = $(this).val().length;
            $('#count_addr').html( cnt_addr + '/100');
            if( cnt_addr > 100 ){
              $('#count_addr').css({'color' : 'red'});
            }else{
              $('#count_addr').css({'color' : '#bdbcc5'});
            }
        });

        $('.numberonly').keypress(function (e) {
            var charCode = (e.which) ? e.which : event.keyCode;
            if (String.fromCharCode(charCode).match(/[^0-9]/g))
                return false;
        });

        $('#car_brand').change(function(){
          if($(this).val() == 'BMW'){ // or this.value == 'volvo'
            //$('.vin-no').show();
          }else{
            //$('.vin-no').hide();
          }
        });

      });

      function check_dup_email(email,tel){
        var cnt;
        $.ajax({
          method: "GET",
          async: false,

          //url: "{{ url('customer/check_dup_email') }}",
		      url: "{{ env('APP_URL_REGIS') . '/customer/check_dup_email' }}",

          data: {
            email : email,
            tel : tel
          }
        }).done(function( res ) {
          console.log(res);
          if(res['count'] > 0){

            if(res['email_count'] > 0){
              $("#fail-topup").modal();
              return false;
            }else if(res['tel_count'] > 0){
              $("#fail-topup2").modal();
              return false;
            }else{
              alert('check duplicate error');
            }
            /*if(res['vin_no'] == '' || res['vin_no'] === null){
              $("#registed_no_vehicle").modal();
              $('#f_name').val(res['f_name']);
              $('#l_name').val(res['l_name']);
              $('#tel').val(res['tel']);
              p_hide_show( $('.p1'), $('.p3') );
              if( res['car_brand'] != '' ){
                $('#car_brand').find('option[value="'+ res['car_brand'] + '"]').attr('selected','selected');
              }

            }else{

              if(res['email_count'] > 0){
                $("#fail-topup").modal();
                return false;
              }else if(res['tel_count'] > 0){
                $("#fail-topup2").modal();
                return false;
              }else{
                alert('check duplicate error');
              }

            }*/

          }else{
            p_hide_show( $('.p1'), $('.p2') ); //hide page1 show page2
          }

        });

      }

      function register_topup(regis_type){

        error_flg = 0;
        var webmail = '';
        var car_brand = $('#car_brand');
        var vin_no = $('#vin_no');
        var email = $('#email').val();
        var f_name = $('#f_name').val();
        var l_name = $('#l_name').val();
        var tel = $('#tel').val();

        var mailArr = email.split('@');
        var mail_type = mailArr[1];

        var arr_mail = [ "gmail.com", "outlook.com", "hotmail.com", "yahoo.com" ];

        ( car_brand.val() == null ) ? validate_action(car_brand) : clear_validate(car_brand);
        if( vin_no.val() != ''){
          if(vin_no.val().length != 17){
            validate_action(vin_no,'รูปแบบ Vin no. ไม่ถูกต้อง กรุณาระบุข้อมูลอีกครั้ง');
          }
          if( car_brand.val() == 'BMW'){
            //( vin_no.val() == '' ) ? validate_action(vin_no) : clear_validate(vin_no);
          }
        }

        if( error_flg == 0 ){

          //$('button[class="btn btn-secondary next-btn p3"]').attr('disabled', 'disabled');

          var data_p1 = $("#form_p1, #form_p2, #form_p2_addr, #form_p3").serialize();
          $.ajax({
            method: "POST",

            //url: "{{ url( '/en/customer-register' ) }}",
            url: "{{ env('APP_URL_REGIS') . '/en/customer-register' }}",

            data: data_p1
          }).done(function( res ) {
            //alert( res['status'] + res['errors']);
            console.log( res );
            if( res['status'] == 1 ){
              if(res['promotion_name'] != ''){
                $('#promotion_name').html(res['promotion_name']);
                $("#success-topup3").modal({backdrop: 'static', keyboard: false});
              }
              /*else if( res['is_update_vin'] == 1 ){
                $("#success-topup2").modal({backdrop: 'static', keyboard: false});

              }*/
              else{
                $("#success-topup").modal({backdrop: 'static', keyboard: false});
              }

              if(regis_type == 2){
                var url = "{{ env('APP_URL_TOPUP') . '/' . app()->getLocale() }}";
                setTimeout(function(){location.href= url + '?form_name=' + f_name + ' ' + l_name + '&form_email=' + email + '&form_tel=' + tel} , 3000);
              }
              if(regis_type == 1){

                if(jQuery.inArray(mail_type, arr_mail) !== -1){

                  if(mail_type == 'gmail.com'){
                    webmail = "https://gmail.com";
                  }
                  if(mail_type == 'hotmail.com'){
                    webmail = "https://login.live.com/login.srf";
                  }
                  if(mail_type == 'outlook.com'){
                    webmail = "https://login.live.com/login.srf";
                  }
                  if(mail_type == 'yahoo.com'){
                    webmail = "https://login.yahoo.com/?username=" + email;
                  }

                  setTimeout(function(){
                    location.href= webmail;
                  }, 3000);

                }else{
                  var url = "{{ env('APP_URL_TOPUP') . '/' . app()->getLocale() }}";
                  setTimeout(function(){location.href = url + '?form_name=' + f_name + ' ' + l_name + '&form_email=' + email + '&form_tel=' + tel} , 3000);
                }

              }

            }else if( res['status'] == 2 ){
              $("#fail-vinno").modal();
              /*var url = '{{ url(app()->getLocale()) }}' + '/customer-register';
              setTimeout(function(){ location.href = url } , 3000);*/
            }else if( res['status'] == 3 ){
              $("#fail-vinno-dup").modal();

            }else{
              alert( 'Error code : ' + res['errors_code'] + ' : ' + res['errors'] );
              var url = "{{ env('APP_URL_REGIS') . '/' . app()->getLocale() . '/customer-register' }}";
              location.href = url;
            }
          });
        }
      }

      function res_success(){
        var f_name = $('#f_name').val();
        var l_name = $('#l_name').val();
        var email = $('#email').val();
        var tel = $('#tel').val();
        var url = "{{ env('APP_URL_REGIS') . '/' . app()->getLocale() . '/customer-register' }}";
        location.href = url + '?form_name=' + f_name + ' ' + l_name + '&form_email=' + email + '&form_tel=' + tel
      }

      function IsEmail(email) {
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if(!regex.test(email)) {
          return false;
        }else{
          return true;
        }
      }

      function validate_action(id, msg = "{{ __('customer.error_msg')}}", type = 0){

        (type == 1) ? id.parent().next().html(msg) : id.next().html(msg);

        id.effect( "highlight", {color:"#f05928"}, 700 );
        error_flg++;
        return false;
      }

      function clear_validate(id, type = 0){
        (type == 1) ? id.parent().next().html('') : id.next().html('');
      }

      function p_hide_show(a, b){
        a.hide();
        b.show();
      }

      function next_p(num){

        if( num == 1 ){ //กดปุ่มถัดไปหน้า 1/3

          error_flg = 0;
          var f_name = $('#f_name');
          var l_name = $('#l_name');
          var sex = $('#sex');
          var tel = $('#tel');
          var email = $('#email');

          ( f_name.val() == '' ) ? validate_action(f_name) : clear_validate(f_name);
          ( l_name.val() == '' ) ? validate_action(l_name) : clear_validate(l_name);
          ( sex.val() == null ) ? validate_action(sex) : clear_validate(sex);
          if( tel.val() == '' ){
            validate_action(tel);
          }else if( tel.val().length != 10 ){
            validate_action(tel, 'ท่านกรอกเบอรโทรไม่ถูกต้อง');
          }else{
            clear_validate(tel);
          }
          if( email.val() == '' ){
            validate_action(email);
          }else if( !IsEmail(email.val()) ){
            validate_action(email, 'ท่านกรอกอีเมลไม่ถูกต้อง');
          }else{
            clear_validate(email);
          }
          if( error_flg == 0 ){
            check_dup_email(email.val(),tel.val());

          }
        }

        if( num == 2 ){ //กดปุ่มถัดไปหน้า 2/3
          error_flg = 0;
          var address = $('#address');
          var province = $('#province');
          var district = $('#district');
          var sub_district = $('#sub_district');
          var zipcode = $('#zipcode');
          var msg = "{{ __('customer.error_msg')}}";
          var count_addr = address.val().length;
          console.log(count_addr);

          if( address.val() == ''){
            validate_action(address,msg);
          }else if( count_addr > 100 ){
            validate_action(address,'กรุณาระบุข้อมูล ไม่เกิน 100 ตัวอักษร');
          }else{
            clear_validate(address,0);
          }
          //( address.val() == '' ) ? validate_action(address,msg) : clear_validate(address,0);
          //( count_addr > 100 ) ? validate_action(address,'กรุณาระบุข้อมูล ไม่เกิน 100 ตัวอักษร') : clear_validate(address,0);
          ( province.val() == '' ) ? validate_action(province,msg,1) : clear_validate(province,1);
          ( district.val() == '' ) ? validate_action(district,msg,1) : clear_validate(district,1);
          ( sub_district.val() == '' ) ? validate_action(sub_district,msg,1) : clear_validate(sub_district,1);
          ( zipcode.val() == '' ) ? validate_action(zipcode,msg,1) : clear_validate(zipcode,1);

          if( error_flg == 0 ){
            p_hide_show( $('.p2'), $('.p3') ); //hide page2 show page3
          }
        }

      }
    </script>
</body>
</html>
