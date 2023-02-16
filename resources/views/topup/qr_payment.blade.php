@extends('layouts.app2')

@section('content')

<section class="screen-center" style="background-color: white;">
  <div class="col-md-4 col-12" style="padding-top: 20px;max-width: 335px;">

    <div class="" style="position: relative;">
      <h3>Promptpay QR Code</h3> <img id="icon_refresh" src="{{ asset('images/qr_payment/refresh.png') }}" alt="" style="width: 22px;position: absolute;top: 0px;right:5px;display:none">
    </div>

    <div id="mycanvas" style="max-width: 335px;border: 1px solid #C4C4C4;border-radius: 0px 0px 20px 20px;margin-bottom: 14px;background-color: white;">

      <div class="" style="width: 100.8%;background: rgb(17, 53, 102);margin: -1px 0px 0px -1px;">
        <img id="pm_logo" src="{{ asset('images/qr_payment/thai_qr_pm_logo.png') }}" alt="" width="108">
      </div>
      <div class="">
        <img src="{{ asset('images/qr_payment/promptpay_logo.png') }}" alt="" width="63">
      </div>
      <div class="">
        <img src="{{ $data['data'] ?? ''}}" alt="" width="228" height="228">
      </div>
      <div class="">
        <p class="detail-qr-button">Evolt</p>
        <p class="detail-qr-button">{{ Request::get('order_id') }}</p>
        <p class="detail-qr-button" style="font-size: 21px;margin-bottom: 12px;">{{ number_format((int) substr(Request::get('amount'),0,-2)) }} บ.</p>

        <img class="save-img" src="{{ asset('images/qr_payment/download.png') }}" alt="" style="border: 1px solid #C4C4C4;border-radius: 6px;padding: 4px;">
        <p id="aass" class="detail-qr-button msgsave-img" style="font-size: 11px;color: #0027DB;">Save</p>
        <p class="detail-qr-button" id="time" style="margin: 10px 0px 14px 0px;">This QR Code will expire in {05:00} minutes</p>
      </div>

      <form id="frm_callback" class="" action="/th/qr-payment-callback" method="post">
        @csrf
        <input type="hidden" name="order_id" value="{{Request::get('order_id')}}">
        <input type="hidden" name="amount" value="{{Request::get('amount')}}">
        <input type="hidden" name="paymentToken" id="paymentToken" value="{{ $data['paymentToken'] ?? ''}}">
        <input type="hidden" name="expiryDescription" id="expiryDescription" value="{{ $data['expiryDescription'] ?? ''}}">
        <input type="hidden" name="channelCode" id="channelCode" value="">
        <input type="hidden" name="invoiceNo" id="invoiceNo" value="">
        <input type="hidden" name="qr_img" id="qr_img" value="{{ $data['data'] ?? ''}}">
      </form>

    </div>


    <div class="box-qr-detail">

      <label class="box-qr_detail-title">วิธีการชำระเงิน</label>
      <label class="box-qr_detail-title2 arrow-down" style="color:#0027DB">ดูรายละเอียด</label> <img class="arrow-down" src="{{ asset('images/qr_payment/vector_down.png') }}" alt="" width="14">
      <div class="box-qr-detail-ex" style="display: none;">

        <img src="{{ asset('images/qr_payment/mobile.png') }}" alt="" width="24"><label class="box-qr-detail-info">ทำรายการผ่านมือถือ</label>
        <ol style="padding: 0px 15px 0px 15px;">
          <li>กด "บันทึก" รูป QR พร้อมเพย์ด้านบนลงใน โทรศัพท์ของคุณ</li>
          <li>เปิดแอปพลิเคชันธนาคารที่คุณมี เพื่อเติมเงิน</li>
          <li>ไปยังเมนู "สแกน" หรือ "สแกนจ่าย" จากนั้น กดที่ปุ่ม "รูปภาพ" ในหน้าสแกนเพื่อเลือกรูป QR ในมือถือของคุณ</li>
          <li>ใส่จำนวนเงินที่ต้องการเติม และทำการเติมเงิน ตามปกติ</li>
        </ol>

        <img src="{{ asset('images/qr_payment/DesktopTower.png') }}" alt="" width="24"><label class="box-qr-detail-info">ทำรายการผ่านคอมพิวเตอร์</label>
        <ol style="padding: 0px 15px 0px 15px;">
          <li>กด "บันทึก" รูป QR พร้อมเพย์ด้านบนลงเครื่อง คอมพิวเตอร์ของคุณหรือทำการเปิดหน้านี้ค้างไว้</li>
          <li>เปิดแอปพลิเคชันธนาคารที่คุณมี เพื่อเติมเงิน</li>
          <li>ไปยังเมนู "สแกน" หรือ "สแกนจ่าย" จากนั้น กดที่ปุ่ม "รูปภาพ" ในหน้าสแกนเพื่อเลือกรูป QR ในมือถือของคุณ</li>
          <li>ใส่จำนวนเงินที่ต้องการเติม และทำการเติมเงิน ตามปกติ</li>
        </ol>
      </div>
    </div>
  </div>

  <div class="col-md-4 col-12" style="padding-top: 20px;max-width: 335px;">
    <div id="content_page_data" >
      <image id="theimage"></image>
    </div>
    <canvas id="myCanvas" width="335" height="412" style="border:1px solid #C4C4C4;clip-path: inset(0 100% 0 0);">
  </div>

  </div>
</section>

<script src="https://superal.github.io/canvas2image/canvas2image.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<script type="text/javascript">

  function fill_canvas(img_src,x,y,width,height) {
      let canvas = document.getElementById('myCanvas');
      let img = new Image();
      img.src = img_src;
      // CREATE CANVAS CONTEXT.
      let ctx = canvas.getContext('2d');

      //canvas.width = 20;
      //canvas.height = 20;

      ctx.drawImage(img,x,y,width,height);       // DRAW THE IMAGE TO THE CANVAS.	context.drawImage(img,x,y,width,height);
  }

  $(function(){

    var new_canvas = document.getElementById("myCanvas");
    var ctx = new_canvas.getContext("2d");
    //backgroup
    ctx.fillStyle = "#FFFFFF";
    ctx.fillRect(0, 0, 335, 412);

    //logo
    ctx.fillStyle = "#113566";
    ctx.fillRect(0, 0, 335, 45);

    const getContext = () => document.getElementById('myCanvas').getContext('2d');

    // It's better to use async image loading.
    const loadImage = url => {
      return new Promise((resolve, reject) => {
        const img = new Image();
		img.crossOrigin = "anonymous";
        img.onload = () => resolve(img);
        img.src = url;
      });
    };

    // Here, I created a function to draw image.
    const depict = options => {
      const ctx = getContext();
      // And this is the key to this solution
      // Always remember to make a copy of original object, then it just works :)
      const myOptions = Object.assign({}, options);
      return loadImage(myOptions.uri).then(img => {
        ctx.drawImage(img, myOptions.x, myOptions.y, myOptions.sw, myOptions.sh);
      });
    };

    const imgs = [
      { uri: '{{ env("APP_URL_TOPUP") }}/images/qr_payment/thai_qr_pm_logo.png', x: 110, y:  0, sw: 108, sh: 45 },
      { uri: '{{ env("APP_URL_TOPUP") }}/images/qr_payment/promptpay_logo.png', x: 135, y:  50, sw: 63, sh: 36 },
      { uri: '{{ $data['data'] ?? ''}}', x: 50, y:  80, sw: 228, sh: 228 }
    ];

    imgs.forEach(depict);

    //ctx.clearRect(0, 0, new_canvas.width, new_canvas.height);
    ctx.fillStyle = "#333333";
    ctx.font = "14px Kanit";
    var text = 'Evolt';
    ctx.fillText(text, 148, 320);

    var text2 = '{{ Request::get('order_id') }}';
    ctx.fillText(text2, 85, 340);

    ctx.font = "21px Kanit";
    var text3 = '{{ number_format((int) substr(Request::get('amount'),0,-2)) }}' + ' บ.';
    ctx.fillText(text3, 135, 380);

    $(".save-img").click(function() {

      var canvas = document.getElementById("myCanvas"); // เรียกใช้ canvas ที่ถูกสร้างขึ้น อ้างอิงตาม id ที่กำหนดก่อนหน้า

      var a = document.createElement('a');
      a.href = canvas.toDataURL("image/jpeg").replace("image/jpeg", "image/octet-stream");
      a.download = 'somefilename.jpg';
      a.click();

       /*document.getElementById("theimage").src = canvas.toDataURL();
       Canvas2Image.saveAsPNG(canvas);
       document.getElementById("theimage").src = '';*/

        /*html2canvas(document.querySelector('#mycanvas'), {
          useCORS: true,
          onrendered: function(canvas) {
            return Canvas2Image.saveAsPNG(canvas);
          }
        });*/

    });

    //let counter = {{ $data['expiryTimer'] ?? 0 }};
    let counter = 300000;
    let paymentToken = $('#paymentToken').val();
    //let expiryDescription = "{{ $data['expiryDescription'] ?? 'This QR Code will expire in {0} minutes.' }}";
    let expiryDescription = "This QR Code will expire in {0} minutes.";

    if(counter > 0){

      var interval = setInterval(function() {

          counter = counter - 1000;
          // Display 'counter' wherever you want to display it.
          if(counter % 3 == 0){
            ajax_get_status_transaction(paymentToken);
          }
          if(counter % 5000 == 0){
            $("#icon_refresh").css("display", "block");
          }

          show_counter = millisToMinutesAndSeconds(counter);
          show_counter = expiryDescription.replace("0", show_counter);
          $('#time').text(show_counter);

          if (counter <= 0) {
              $('#time').html('This QR Code will expire in {00:00} minutes.');
           		clearInterval(interval);
          }

      }, 1000);
    }

    $(".arrow-down").click(function() {
      return (this.tog = !this.tog) ? a1() : b1();
    });

    $("#icon_refresh").click(function() {
      location.reload();
    });

    /*$(".save-img").click(function() {
      //let image_url = "'" + $('#qr_img').val() + "'";
      //downloadImage($('#qr_img').val());
    });*/

  });

  async function downloadImage(imageSrc) {
    const image = await fetch(imageSrc)
    const imageBlog = await image.blob()
    const imageURL = URL.createObjectURL(imageBlog)

    const link = document.createElement('a')
    link.href = imageURL
    link.download = 'my_qr_code'
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
  }

  function ajax_get_status_transaction(paymentToken){

    let respCode = 0;
    let payment_code = 0;

    $.ajax({
      method: "POST",
      url: "/api/qr-payment-getstatus",
      data: { paymentToken: paymentToken }
    })
    .done(function( res ) {
      respCode = res['response']['respCode'];
      payment_code = res['response']['additionalInfo']['paymentResultDetails']['code'];

      if(payment_code == '00'){
        //console.log(res['response']['respCode']);
        $('#channelCode').val(res['response']['channelCode']);
        $('#invoiceNo').val(res['response']['invoiceNo']);
        $( "#frm_callback" ).submit();
      }
    });
  }

  function millisToMinutesAndSeconds(millis) {
    var minutes = Math.floor(millis / 60000);
    var seconds = ((millis % 60000) / 1000).toFixed(0);
    return minutes + ":" + (seconds < 10 ? '0' : '') + seconds;
  }

  function a1(){
    $('.box-qr-detail-ex').css('display','block');
    $('.arrow-down').attr('src','/images/qr_payment/vector_up.png');
  }

  function b1(){
    $('.box-qr-detail-ex').css('display','none');
    $('.arrow-down').attr('src','/images/qr_payment/vector_down.png');
  }

</script>
@endsection
