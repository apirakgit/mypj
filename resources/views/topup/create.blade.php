@extends('layouts.app2')

@section('content')
<section class="section-preview container">
  <div class="row" style="">

    <section class="col-md-4 col-sm-5 col-12" style="background-color: white;">
      <article class="section-left topup">
          <div class="topup-inner">
              <img src="{{ asset('images/logo_evolt.svg') }}"  />
              <h1>EVolt Top up </h1>
              <h3>{{ __('message.m_head')}}</h3>
              <ol>
                <li>{{ __('message.m1')}} <a href="http://register.evolt.co.th">http://register.evolt.co.th</a> {{ __('message.m1_1')}}</li>
                <li>{{ __('message.m2')}}</li>
                <li>{{ __('message.m3')}}</li>
                <li>{{ __('message.m4')}}</li>
                <li>{{ __('message.m5')}}</li>
              </ol>
          </div>
      </article>
    </section>

    <section class="col-md-8 col-sm-7 col-12" style="background: transparent linear-gradient(110deg, #F68F1E 3%, #F37323 42%, #EF4E2B 100%) 0% 0% no-repeat padding-box;">
      <div class="box-ml">
        <a href="/th">
          <span class="ml_th">TH</span>
        </a>
        <span>|</span>
        <a href="/en">
            <span class="ml_en">EN</span>
        </a>
      </div>
      <div class="col-md-11 section-right">
      <article class="information-box-r" style="">
        <div class="row no-gutters">
          <form>
            <div class="row title-form">
              <div class="col-12">
                <h3 class="">{{ __('message.frm_head')}}</h3>
              </div>

            </div>
            <div class="row">

              <!--<div class="col-12">
                <div class="form-group">
                  <label for="exampleInputEmail1">{{ __('message.frm_name')}}</label>
                  <input type="text" class="form-control form-input" id="form_name" name="form_name" aria-describedby="emailHelp" placeholder="{{ __('message.frm_name')}}"
                  style="background-color: rgba(240,244,248,1); border: 0px; " value="{{request()->get('form_name')}}">
                  <p class="txt_error" id="txt_error_name"></p>
                </div>
              </div>-->

              <div class="col-md-6 col-12">
                <div class="form-group">
                  <label for="exampleInputEmail1">{{ __('message.frm_email')}}</label>
                  <input type="text" class="form-control form-input" id="form_email" name="form_email" placeholder="example@gmail.com"
                  style="background-color: rgba(240,244,248,1); border: 0px;" value="{{request()->get('form_email') ?? 'ffssdp23dpfo1@gmail.com'}}">
                  <p class="txt_error" id="txt_error_email"></p>
                </div>
              </div>

              <div class="col-md-6 col-12">
                <div class="form-group">
                  <label for="exampleInputEmail1">{{ __('message.frm_tel')}}</label>
                  <input type="tel" class="form-control form-input" maxlength="10" id="form_tel" name="form_tel" placeholder="0901234567"
                  style="background-color: rgba(240,244,248,1); border: 0px;" value="{{request()->get('form_tel') ?? '0650034204'}}">
                  <p class="txt_error" id="txt_error_tel"></p>
                </div>
              </div>

              <div class="col-8">
                <div class="form-group">
                  <label>Promotion Code</label>
                  <input type="text" class="form-control form-input" id="form_promotion_code" name="form_promotion_code" placeholder="Promotion Code"
                  style="background-color: rgba(240,244,248,1); border: 0px; " value="{{request()->get('form_promotion_code')}}">
                  <img src="{{ asset('images/Vector.png') }}" id="vector" style="position: absolute;top: 44px;right: 28px;display:none">
                  <p class="" id="txt_promotion_code" style="color:#2BC016; display:none">ชื่อ Bonus เพิ่ม 10%</p>
                  <p class="" id="txt_promotion_code_fail" style="color:red; display:none">ไม่พบโปรโมชั่นโค้ดนี้</p>
                  <p class="txt_error" id="txt_error_promotion_code"></p>
                </div>
              </div>

              <div class="col-4">
                <div class="form-group">
                  <label>&nbsp</label>
                  <input type="button" class="form-control btn" value="Apply" onclick="check_promotion_code()" style="box-shadow: 0px 5px 15px rgba(170, 89, 0, 0.15);border-radius: 10px;">
                </div>
              </div>
          </div>
          </form>
        </div>
      </article>

      <article class="amount-box-r" style="">
        <div class="row title-form">
          <div class="col-12">
            <h3>{{ __('message.frm_amount')}}</h3>
          </div>
        </div>

        <div class="form-group amount-btn">
          <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <div class="row ctrl-pad">
                <div class="col-md-6 col-6">
                  <label class="btn btn-secondary btn-amount">
                    <input type="radio" name="form_amount" id="option1" value="000000030000" autocomplete="off"> 300
                  </label>
                </div>
                <div class="col-md-6 col-6">
                  <label class="btn btn-secondary btn-amount">
                    <input type="radio" name="form_amount" id="option2" value="000000050000" autocomplete="off"> 500
                  </label>
                </div>
                <div class="col-md-6 col-6">
                  <label class="btn btn-secondary btn-amount">
                    <input type="radio" name="form_amount" id="option3" value="000000100000" autocomplete="off"> 1,000
                  </label>
                </div>
                <div class="col-md-6 col-6">
                  <label class="btn btn-secondary btn-amount">
                    <input type="radio" name="form_amount" id="option4" value="000000200000" autocomplete="off"> 2,000
                  </label>
                </div>
                <p class="txt_error text-center" id="txt_error_amount" style="width:100%"></p>
            </div>
          </div>
      </div>

      </article>

      <nav class="btn-sumit-box" style="">
        <button type="button" class="btn btn-primary bnt-submit" onclick="validate_form()">
          {{ __('message.frm_submit')}}
        </button>
      </nav>
      </div>
      </section>

    </div>
  </div>
</section>
@if ($errors->any())
  <script>
    $(function(){
      $('#fail-topup').modal('show');
    });
  </script>
@endif

@if(session()->has('success'))
  <script>
    $(function(){
      $('#success-topup').modal('show');
    });
  </script>
@endif
<!-- Modal -->
<div class="modal fade modal-confirm-detail" id="comfirm-topup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog topup-modal-inner" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
          {{ __('message.pop_header')}}
          @if( app()->getLocale() == 'en')
          <span class="popup_newline">{{ __('message.pop_header_2')}}</span>
          @endif
        </h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ env('APP_URL_TOPUP') .'/'. app()->getLocale() .'/topup' }}" method="POST">
        @csrf
        <input type="hidden" name="name" id="name">
        <input type="hidden" name="email" id="email">
        <input type="hidden" name="tel" id="tel">
        <input type="hidden" name="amount" id="amount">
        <input type="hidden" name="discount" id="discount">
        <input type="hidden" name="topup_amount" id="topup_amount">
        <input type="hidden" name="bonus" id="bonus">
        <input type="hidden" name="free_topup" id="free_topup">
        <input type="hidden" name="promotion_name" id="promotion_name">
        <input type="hidden" name="promotion_code" id="promotion_code">
        <input type="hidden" name="promotion_code_id" id="promotion_code_id">
        <input type="hidden" name="customer_id" id="customer_id">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-5 col-5">
                {{ __('message.pop_name')}}
            </div>
            <div class="col-md-7 col-7">
                <label for="" id="l_name"></label>
            </div>
          </div>
          <div class="row">
            <div class="col-md-5 col-5 ">
                {{ __('message.pop_email')}}
            </div>
            <div class="col-md-7 col-7">
                <label for="" id="l_email"></label>
            </div>
          </div>
          <div class="row">
            <div class="col-md-5 col-5">
                {{ __('message.pop_tel')}}
            </div>
            <div class="col-md-7 col-7">
                <label for="" id="l_tel"></label>
            </div>
          </div>
          <div class="row">
            <div class="col-md-5 col-5">
                {{ __('message.pop_amount')}}
            </div>
            <div class="col-md-7 col-7">
                <label for="" id="l_amount"></label>
            </div>
          </div>

          <div class="row" style="padding-bottom: 10px;">
            <div class="col">
              <select class="form-control" id="select_prom" style="background-color: #F0F4FA; border: 0px;">
              </select>
            </div>

          </div>

          <div class="row sum_sell">
            <div class="col-md-5 col-5">
                ส่วนลด (บาท)
            </div>
            <div class="col-md-7 col-7">
                <label for="" id="l_discount">0</label>
            </div>
          </div>

          <div class="row sum_sell">
            <div class="col-md-12 col-12">
              <li style="padding: 2px 0px 5px 8px;"><span id="l_promotion"></span></li>
            </div>
          </div>

          <div class="row sum_bonus">
            <div class="col-md-5 col-5">
                โบนัส
            </div>
            <div class="col-md-7 col-7">
                <label for="" id="l_discount_bonus">0</label>
            </div>
          </div>

          <div class="row sum_bonus">
            <div class="col-md-12 col-12">
              <li style="padding: 2px 0px 5px 8px;"><span id="l_promotion_bonus"></span></li>
            </div>
          </div>

          <div class="row" style="padding-bottom: 10px;">
            <div class="col-md-8 col-8" style="text-align: left;">
                จำนวนเงินที่ใช้ได้จริง (บาท)
            </div>
            <div class="col-md-4 col-4" style="text-align: right;">
                <label for="" id="l_amount2"></label>
            </div>
          </div>

          <div class="row" style="background-color: #F0F4FA;padding: 8px 0px 16px 2px;border-radius: 18px;margin: 0;">
            <div class="col-md-8 col-8" style="margin-top: 10px;">
                จำนวนเงินที่ต้องชำระ (บาท)
            </div>
            <div class="col-md-4 col-4" style="text-align: right;">
                <label for="" id="l_total" style="color: #F79020;margin: 1px 6px 0px 0px;font-size: 21px;"></label>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-amount cf_sum_sell" onclick="cf_sum_sell()" style="display:none">ตกลง</button>
          <button type="submit" class="btn btn-primary btn-amount cf_sum">{{ __('message.pop_submit')}}</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade modal-success" id="success-topup" tabindex="-1" role="dialog" aria-labelledby="success-topup" aria-hidden="true">
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
              <img src="{{ asset('images/usd-circle.png') }}" alt="">
              @if( app()->getLocale() == 'th')

                <p>{{ __('message.alert_success1') }}</p>
                <p>{{ __('message.alert_success2') }}</p>
                <p>{{ __('message.alert_success2_2') }}</p>

              @else
                <p>{{ __('message.alert_success1') }}</p>
                <p>{{ __('message.alert_success1_2') }}</p>
                <p>{{ __('message.alert_success2') }}</p>
                <p>{{ __('message.alert_success2_2') }}</p>

              @endif
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary bnt-submit" data-dismiss="modal" aria-label="Close">{{ __('message.pop_close')}}</button>
      </div>
    </div>
  </div>
</div>

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
              @if( app()->getLocale() == 'th')
                <p>{{ __('message.alert_fail')}}</p>
                <p>{{ __('message.alert_fail_2')}}</p>
                <p>{{ __('message.alert_fail_3')}}</p>
              @else
              <p>{{ __('message.alert_fail')}}</p>
              <p>{{ __('message.alert_fail_2')}}</p>
              @endif
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary bnt-submit" data-dismiss="modal" aria-label="Close">{{ __('message.pop_close')}}</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade modal-fail" id="modal-empty-email" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <p style="margin-bottom: 2px;">ไม่มีอีเมลอยู่ในระบบ</p>
                <p style="margin-bottom: 2px;">โปรดตรวจสอบข้อมูล</p>
                <p style="margin-bottom: 2px;">การลงทะเบียนอีกครั้ง</p>
                <p style="margin-bottom: 2px;">หากมีข้อสงสัยโปรดติดต่อ</p>
                <p style="margin-bottom: 2px;">Call center  021147343</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <a href="{{ env('APP_URL_REGIS') . '/'. app()->getLocale() . '/customer-register' }}" class="btn btn-primary bnt-submit">ไปหน้าลงทะเบียน</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade modal-fail" id="promotion-fail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

                <p>ไม่สามารถใช้โค้ดส่วนลดนี้ได้</p>
                <p>กรุณาลองใหม่อีกครั้ง</p>

          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary bnt-submit" data-dismiss="modal" aria-label="Close">{{ __('message.pop_close')}}</button>
      </div>
    </div>
  </div>
</div>
@endsection
