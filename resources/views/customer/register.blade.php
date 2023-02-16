@extends('layouts.app3')

@section('content')
<link rel="stylesheet" href="./../jquery.Thailand.js/dist/jquery.Thailand.min.css">

<link href="./../boostrap-datepicker/css/datepicker.css" rel="stylesheet" media="screen">

<!-- ///////////////////////////////////////////////////////////////// -->
<style media="screen">
[class^="icon-"],
[class*=" icon-"] {
  display: inline-block;
  width: 14px;
  height: 14px;
  margin-top: 1px;
  *margin-right: .3em;
  line-height: 14px;
  vertical-align: text-top;
  background-image: url("./../images/glyphicons-halflings.png");
  background-position: 14px 14px;
  background-repeat: no-repeat;
}

.icon-arrow-left{
  background-position: -240px -96px;
}
.icon-arrow-right {
    background-position: -264px -96px;
}
</style>
<style type="text/css">
	h1 {
		font-size: 30px;
	}

	.form-control[readonly] {
		background: none;
	}

	@media(max-width: 414px) {
		.form-control {
			font-size: 12px;
		}
	}

	@media(max-width: 360px) {
		.form-control {
			font-size: 10px;
			padding: 0 5px;
		}
	}

	@media(max-width: 320px) {
		.col-xs-6 {
			padding: 0 5px;
		}
	}
</style>
<section class="section-preview container">
  <div class="row" style="">
    <section class="col-md-2 col-sm-5 col-12" style="background-color: white;">
      <article class="section-left topup">

      </article>
    </section>

    <section class="col-md-8 col-sm-7 col-12" style="">

      <div class="box-ml box-ml-r">
        <a href="/th/customer-register">
          <span class="ml_th">TH</span>
        </a>
        <span>|</span>
        <a href="/en/customer-register">
            <span class="ml_en">EN</span>
        </a>
      </div>
      <div class="col-md-11 section-right-r">
        <div class="topup-inner-r" style="">
            <img src="{{ asset('images/logo_evolt.svg') }}"  />
        </div>
          <article class="information-box-r-r p1" style="">
            <div class="row no-gutters">
              <form name="form_p1" id="form_p1" method="post" action="">
                @csrf
                <div class="row">
                  <div class="col-12">
                    <h2 class="box-title">{{ __('customer.title')}}</h2>
                    <div class="box-title-number">
                      <span><img src="{{ asset('images/UserRectangle.png') }}" style="position: absolute;"></span>
                      <span class="title-p">{{ __('customer.info')}}</span>
                      <span class="next-p">1/3</span>
                    </div>
                  </div>
                </div>
                <div class="row">

                  <div class="col-md-6 col-12">
                    <div class="form-group">
                      <label class="label-h" >{{ __('customer.f_name')}}</label>
                      <input type="text" class="form-control form-input reg-input" id="f_name" name="f_name"  placeholder="{{ __('customer.f_name')}}" autocomplete="off">
                      <p class="reg_txt_error" id="reg_txt_error"></p>
                    </div>
                  </div>

                  <div class="col-md-6 col-12">
                    <div class="form-group">
                      <label class="label-h" >{{ __('customer.l_name')}}</label>
                      <input type="text" class="form-control form-input reg-input" id="l_name" name="l_name"  placeholder="{{ __('customer.l_name')}}" autocomplete="off">
                      <p class="reg_txt_error" id="reg_txt_error"></p>
                    </div>
                  </div>

                  <div class="col-md-6 col-12">
                    <div class="form-group">
                      <label class="label-h" >{{ __('customer.birthday')}}</label>
                      <input type="hidden" name="birthday_locale" value="{{ app()->getLocale() }}">
                      <input type="text" class="form-control form-input reg-input date_roll" id="rolldate" name="birthday" placeholder="yyyy/mm/dd" autocomplete="off" data-date-language="{{app()->getLocale()}}" readonly>
                      <input type="text" class="form-control form-input reg-input datepicker date_calendar" name="birthday_r" placeholder="yyyy/mm/dd" autocomplete="off" data-date-language="{{ (app()->getLocale() == 'th') ? 'th-th' : '' }}" style="padding-left: 12px;" readonly>
                    </div>
                  </div>

                  <div class="col-md-6 col-12">
                    <div class="form-group">
                      <label class="label-h">{{ __('customer.sex')}}</label>
                      <select class="form-control form-input reg-select" name="sex" id="sex">
                        <option value="" disabled selected>{{ __('customer.sex')}}</option>
                        <option value="male">{{ __('customer.male')}}</option>
                        <option value="female">{{ __('customer.female')}}</option>
                      </select>
                      <p class="reg_txt_error" id="reg_txt_error"></p>
                    </div>
                  </div>

                  <div class="col-md-6 col-12">
                    <div class="form-group">
                      <label class="label-h" >{{ __('customer.tel')}}</label>
                      <input type="text" class="form-control form-input numberonly reg-input" maxlength="10" id="tel" name="tel" placeholder="0901234567">
                      <p class="reg_txt_error" id="reg_txt_error"></p>
                    </div>
                  </div>

                  <div class="col-md-6 col-12">
                    <div class="form-group">
                      <label class="label-h" >{{ __('customer.email')}}</label>
                      <input type="text" class="form-control form-input reg-input" id="email" name="email" placeholder="example@gmail.com">
                      <p class="reg_txt_error" id="reg_txt_error"></p>
                    </div>
                  </div>

                </div>
              </form>

            </div>
          </article>
          <nav class="btn-sumit-box" style="">
            <button type="button" class="btn btn-secondary next-btn p1" onclick="next_p(1)" style="">
              {{ __('customer.btn_next')}}
            </button>

          </nav>
          <article class="information-box-r-r p2" style="display:none">
            <div class="row no-gutters" style="display:block">
              <form name="form_p2" id="form_p2" method="post" action="">
                <input type="hidden" name="province" id="form_province">
                <input type="hidden" name="district" id="form_district">
                <input type="hidden" name="sub_district" id="form_sub_district">
                <input type="hidden" name="zipcode" id="form_zipcode">
                @csrf
                <div class="row">
                  <div class="col-12">
                    <h2 class="box-title">{{ __('customer.title')}}</h2>
                    <div class="box-title-number">
                      <span><img src="{{ asset('images/HouseLine.png') }}" style="position: absolute;"></span>
                      <span class="title-p">{{ __('customer.title2')}}</span>
                      <span class="next-p">2/3</span>
                    </div>
                  </div>
                </div>

              </form>

              <div class="uk-container uk-padding">
                <!--<div class="border-line"></div>-->
                <div class="row">
                  <div class="col-12" style="border-bottom: 1px solid #E1E5EC; margin-bottom: 15px;">
                    <form id="demo2" class="demo" style="display:none;" autocomplete="off">
                      <div class="form-group box-tooltip-search">
                        <label class="label-h tooltip-search" style="color:#6E6D7A;padding-bottom: 10px;">{{ __('customer.address_search_label')}}</label>
                        <span class="icon-search"><img src="{{ asset('images/MagnifyingGlass.png') }}"></span>
                        <input name="search" class="uk-input uk-width-1-1 form-control form-input reg-input" type="text" placeholder="{{ __('customer.address_search')}}" style="padding-left: 40px;background-color: rgba(240,244,248,1);">
                        <div id="demo2-output" class="uk-margin"></div>
                      </div>
                    </form>
                  </div>

				  <form name="form_p2_addr" id="form_p2_addr" method="post" action="" style="width: 100%;">
                  <div class="col-12" >
                    <div class="form-group address-mobile" >
                      <label class="label-h" >{{ __('customer.address')}}</label>
                      <input type="text" class="form-control form-input reg-input" id="address" name="address" placeholder="{{ __('customer.address')}}" autocomplete="off">
                      <span class="reg_txt_error" id="addrs_txt_error"></span>
                      <span id="count_addr" style="float: right; font-size: 12px; color: #bdbcc5">0/100</span>
                    </div>
                  </div>
				  </form>

                  <div class="col-md-6 col-12">
                    <form id="form_addr" class="demo" style="display:none;" autocomplete="off" uk-grid >
                      <div class="form-group">
                        <label class="label-h" >{{ __('customer.province')}}</label>
                        <input name="province" id="province" class="uk-input uk-width-1-1 form-control form-input reg-input" type="text" placeholder="{{ __('customer.select_province') }}">
                        <p class="reg_txt_error" id="reg_txt_error"></p>
                        <div class="uk-width-1-2@m"></div>
                      </div>
                    </form>
                  </div>

                  <div class="col-md-6 col-12">
                    <form id="form_addr" class="demo" style="display:none;" autocomplete="off" uk-grid >
                      <div class="form-group">
                        <label class="label-h" >{{ __('customer.district')}}</label>
                        <input name="district" id="district" class="uk-input uk-width-1-1 form-control form-input reg-input" type="text" placeholder="{{ __('customer.select_amphur') }}">
                        <p class="reg_txt_error" id="reg_txt_error"></p>
                        <div class="uk-width-1-2@m"></div>
                      </div>
                    </form>
                  </div>

                  <div class="col-md-6 col-12">
                    <form id="form_addr" class="demo" style="display:none;" autocomplete="off" uk-grid >
                      <div class="form-group">
                        <label class="label-h" >{{ __('customer.sub_district')}}</label>
                        <input name="sub_district" id="sub_district" class="uk-input uk-width-1-1 form-control form-input reg-input" type="text" placeholder="{{ __('customer.select_district') }}">
                        <p class="reg_txt_error" id="reg_txt_error"></p>
                        <div class="uk-width-1-2@m"></div>
                      </div>
                    </form>
                  </div>

                  <div class="col-md-6 col-12">
                    <form id="form_addr" class="demo" style="display:none;" autocomplete="off" uk-grid >
                      <div class="form-group">
                        <label class="label-h" >{{ __('customer.zipcode')}}</label>
                        <input name="zipcode" id="zipcode" class="uk-input uk-width-1-1 form-control form-input reg-input" type="text" placeholder="{{ __('customer.zipcode') }}">
                        <p class="reg_txt_error" id="reg_txt_error"></p>
                        <div class="uk-width-1-2@m"></div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </article>
          <nav class="btn-sumit-box p2" style="display:none">
            <button type="button" class="btn btn-secondary next-btn p2" onclick="next_p(2)" style="">{{ __('customer.btn_next')}}</button>
          </nav>
          <article class="information-box-r-r p3" style="height: 468px; display:none">
            <div class="row no-gutters" style="display:block">
              <form name="form_p3" id="form_p3" method="post" action="">
                @csrf
                <div class="row">
                  <div class="col-12">
                    <h2 class="box-title">{{ __('customer.title')}}</h2>
                    <div class="box-title-number">
                      <span><img src="{{ asset('images/Car.png') }}" style="position: absolute;"></span>
                      <span class="title-p">{{ __('customer.title2')}}</span>
                      <span class="next-p">3/3</span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label class="label-h" >{{ __('customer.info_car')}}</label>
                      <select class="form-control form-input reg-select" name="car_brand" id="car_brand">
                        <option value="" disabled selected>{{ __('customer.choose_car_band')}}</option>
                        @foreach($vehicles as $value)
                        <option value="{{ $value->brand }}" >{{ $value->brand }}</option>
                        @endforeach
                      </select>
                      <p class="reg_txt_error" id="reg_txt_error"></p>
                    </div>
                  </div>

                  <div class="col-12 vin-no">
                    <div class="form-group">
                      <label class="label-h" >VIN No.(Optional)</label>
                      <input type="text" class="form-control form-input reg-select" id="vin_no" name="vin_no"  placeholder="VIN No." >
                      <p class="reg_txt_error" id="reg_txt_error"></p>
                      <p style="color:#9FACBD; font-size: 11px;" id="">{{ __('customer.receive_special')}}</p>
                    </div>
                  </div>

              </div>
              </form>
            </div>
          </article>
          <div class="row">
            <div class="col-md-6 col-12">
              <nav class="btn-sumit-box">
                <button type="button" class="btn btn-secondary next-btn p3" onclick="register_topup(1)" style="display:none">
                  {{ __('customer.btn_register')}}
                </button>
              </nav>
            </div>
            <div class="col-md-6 col-12">
              <nav class="btn-sumit-box" style="">
                <button type="button" class="btn btn-secondary next-btn p3" onclick="register_topup(2)" style="display:none">
                  {{ __('customer.btn_register_topup')}}
                </button>
              </nav>
            </div>
          </div>
      </div>
    </section>
  <div>
</section>

<div class="modal fade modal-fail" id="fail-topup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog topup-modal-inner" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-12">
              <img src="{{ asset('images/sad-squint.png') }}" alt="">
              <p>ลงทะเบียนไม่สำเร็จ</p>
              <p>เนื่องจากอีเมลมีการลงทะเบียนแล้ว</p>
              <p>กรุณาติดต่อคอลเซ็นเตอร์</p>
              <p>021147343</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<div class="modal fade modal-fail" id="registed_no_vehicle" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog topup-modal-inner" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-12">
              <img src="{{ asset('images/sad-squint.png') }}" alt="">
              <p>คุณได้ลงทะเบียนไปแล้ว</p>
              <p>กรุณาอัพเดทข้อมูลรถของท่าน</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<div class="modal fade modal-fail" id="fail-topup2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog topup-modal-inner" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-12">
              <img src="{{ asset('images/sad-squint.png') }}" alt="">
              <p>ลงทะเบียนไม่สำเร็จ</p>
              <p>เนื่องจากเบอร์โทรนี้มีการลงทะเบียนแล้ว</p>
              <p>กรุณาติดต่อคอลเซ็นเตอร์</p>
              <p>021147343</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<div class="modal fade modal-fail" id="fail-vinno" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog topup-modal-inner" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true"></span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-12">
              <img src="{{ asset('images/sad-squint.png') }}" alt="">
              <p>ลงทะเบียนไม่สำเร็จ</p>
              <p>Vin no. ถูกลงทะเบียนแล้ว กรุณาระบุข้อมูลใหม่อีกครั้ง</p>
              <p>หากมีข้อสงสัยโปรดติดต่อ Call center 021147343</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<div class="modal fade modal-fail" id="fail-vinno-dup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog topup-modal-inner" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true"></span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-12">
              <img src="{{ asset('images/sad-squint.png') }}" alt="">
              <p>ลงทะเบียนไม่สำเร็จ</p>
              <p>เนื่องจากไม่มีข้อมูล Vin No.</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<div class="modal fade modal-success" id="success-topup" tabindex="-1" role="dialog" aria-labelledby="success-topup" aria-hidden="true">
  <div class="modal-dialog topup-modal-inner" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true"></span>
        </button>
      </div>
      <div class="modal-body" style="padding-bottom: 22px;">
        <div class="row">
          <div class="col-12">
              <img src="{{ asset('images/pass.png') }}" alt="">
                <p>{{ __('customer.regis_success01')}}</p>
                <p>{{ __('customer.regis_success02')}}</p>
                <p>{{ __('customer.regis_success03')}}</p>
          </div>
        </div>
      </div>
      <!--
      <div class="modal-footer">
        <button type="button" class="btn btn-primary bnt-submit" data-dismiss="modal" aria-label="Close" onclick="res_success()">ตกลง</button>
      </div>-->
    </div>
  </div>
</div>
<div class="modal fade modal-success" id="success-topup2" tabindex="-1" role="dialog" aria-labelledby="success-topup" aria-hidden="true">
  <div class="modal-dialog topup-modal-inner" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true"></span>
        </button>
      </div>
      <div class="modal-body" style="padding-bottom: 22px;">
        <div class="row">
          <div class="col-12">
              <img src="{{ asset('images/pass.png') }}" alt="">
                <p>ลงทะเบียนเรียบร้อยแล้ว</p>
                <p>และได้สิทธิ์ส่วนลดพิเศษ</p>
          </div>
        </div>
      </div>
      <!--
      <div class="modal-footer">
        <button type="button" class="btn btn-primary bnt-submit" data-dismiss="modal" aria-label="Close" onclick="res_success()">ตกลง</button>
      </div>-->
    </div>
  </div>
</div>
<div class="modal fade modal-success" id="success-topup3" tabindex="-1" role="dialog" aria-labelledby="success-topup" aria-hidden="true">
  <div class="modal-dialog topup-modal-inner" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true"></span>
        </button>
      </div>
      <div class="modal-body" style="padding-bottom: 22px;">
        <div class="row">
          <div class="col-12">
              <img src="{{ asset('images/pass.png') }}" alt="">
                <p>ลงทะเบียนสำเร็จ</p>
                <p>คุณได้รับสิทธิ์พิเศษ</p>
                <p id="promotion_name">“ชื่อโปรโมชั่นมาจากหลังบ้าน”</p>
                <p>กรุณาตรวจสอบอีเมล</p>
                <p>และยืนยันการสมัคร</p>
                <p>ในอีเมลของคุณ</p>
          </div>
        </div>
      </div>
      <!--
      <div class="modal-footer">
        <button type="button" class="btn btn-primary bnt-submit" data-dismiss="modal" aria-label="Close" onclick="res_success()">ตกลง</button>
      </div>-->
    </div>
  </div>
</div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-beta.20/js/uikit.min.js"></script>
<script type="text/javascript" src="./../jquery.Thailand.js/dependencies/zip.js/zip.js"></script>
<script type="text/javascript" src="./../jquery.Thailand.js/dependencies/JQL.min.js"></script>
<script type="text/javascript" src="./../jquery.Thailand.js/dependencies/typeahead.bundle.js"></script>
<script type="text/javascript" src="./../jquery.Thailand.js/dist/jquery.Thailand-{{app()->getLocale()}}.js"></script>

<script src="./../boostrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="./../boostrap-datepicker/js/bootstrap-datepicker-thai.js"></script>
<script src="./../boostrap-datepicker/js/locales/bootstrap-datepicker.th.js"></script>

<script>

		window.onload = function() {
      var today = new Date();
      var lang = '{{app()->getLocale()}}';
      if(lang == 'th'){
        var current_old = ( today.getFullYear() + 543 ) - 80;
        var current_date = today.getFullYear() + 543;
        var txt_title = 'เลือกวันเกิด';
        var txt_cancel = 'ยกเลิก';
        var txt_confirm = 'ยืนยัน';
      }else{
        var current_old = today.getFullYear() - 80;
        var current_date = today.getFullYear();
        var txt_title = 'Select A Date';
        var txt_cancel = 'Cancel';
        var txt_confirm = 'Confirm';
      }


      console.log(today.getFullYear());
			new Rolldate({
				el: '#rolldate',
        beginYear: current_old,
        endYear: current_date,
				lang: {
				  title: txt_title,
				  cancel: txt_cancel,
				  confirm: txt_confirm,
				  year: '',
				  month: '',
				  day: '',
				  hour: '',
				  min: '',
				  sec: ''
				}
			})
		}

</script>
<script type="text/javascript" src="./../rolldate/rolldate.min.js"></script></body>
<script type="text/javascript">

  $('.datepicker').datepicker({
    format: 'yyyy-mm-dd'
  });

    $.Thailand({
        database: './../jquery.Thailand.js/database/db_{{app()->getLocale()}}.json',

        $district: $('#form_addr [name="sub_district"]'),
        $amphoe: $('#form_addr [name="district"]'),
        $province: $('#form_addr [name="province"]'),
        $zipcode: $('#form_addr [name="zipcode"]'),

        onDataFill: function(data){
            console.info('Data Filled', data);
        },

        onLoad: function(){
            console.info('Autocomplete is ready!');
            $('#loader, .demo').toggle();
        }
    });

    $('#form_addr [name="sub_district"]').change(function(){
        console.log('ตำบล', this.value);
        $('#form_sub_district').val(this.value);
    });
    $('#form_addr [name="district"]').change(function(){
        console.log('อำเภอ', this.value);
        $('#form_district').val(this.value);
    });
    $('#form_addr [name="province"]').change(function(){
        console.log('จังหวัด', this.value);
        $('#form_province').val(this.value);
    });
    $('#form_addr [name="zipcode"]').change(function(){
        console.log('รหัสไปรษณีย์', this.value);
        $('#form_zipcode').val(this.value);
    });

    $.Thailand({
        database: './../jquery.Thailand.js/database/db_{{app()->getLocale()}}.json',
        $search: $('#demo2 [name="search"]'),

        onDataFill: function(data){
            console.log(data)
            //var html = '<b>ที่อยู่:</b> ตำบล' + data.sub_district + ' อำเภอ' + data.district + ' จังหวัด' + data.province + ' ' + data.zipcode;
            //$('#demo2-output').prepend('<div class="uk-alert-warning" uk-alert><a class="uk-alert-close" uk-close></a>' + html + '</div>');
            $('#province, #form_province').val(data.province);
            $('#district, #form_district').val(data.amphoe);
            $('#sub_district, #form_sub_district').val(data.district);
            $('#zipcode, #form_zipcode').val(data.zipcode);

        }

    });
</script>
@endsection
