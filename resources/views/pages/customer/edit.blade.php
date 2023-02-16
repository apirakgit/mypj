@extends('layouts.app')

@section('content')
  @include('layouts.headers.cards')
<link rel="stylesheet" href="./../../../jquery.Thailand.js/dist/jquery.Thailand.min.css">

<!-- ///////////////////////////////////////////////////////////////// -->
<div class="container-fluid">
  <div class="row" style="padding-top: 30px;">
    <div class="col-12">
      @if (session('message'))
          <div class="alert alert-success">
              {{ session('message') }}
          </div>
      @endif
      @if ($errors->any())
          <div class="alert alert-danger">
              @foreach ($errors->all() as $error)
                  <p>{{ $error }}</p>
              @endforeach
          </div>
      @endif
      <div class="card" style="padding-bottom: 120px;">
        <div class="card-header border-0">
          <div class="col-md-5">
            <h3 class="mb-0">Create Customer</h3>
          </div>
        </div>
        <div class="" style="margin: 14px 20px 0px 50px;">
          <script type="text/javascript">
            function checkform(){
              var pv = $('#province').val();
              var ds = $('#district').val();
              var sd = $('#sub_district').val();
              var zc = $('#zipcode').val();

              if(pv == ''){ $( '#province' ).tooltip('show'); return false;}
              if(ds == ''){ $( '#district' ).tooltip('show'); return false;}
              if(sd == ''){ $( '#sub_district' ).tooltip('show'); return false;}
              if(zc == ''){ $( '#zipcode' ).tooltip('show'); return false;}

              return true;
            }
          </script>
        <form action="{{ URL::to('/en/customers/' . $data->id) }}" method="POST" onsubmit="return checkform()">
            {{ method_field('PUT') }}
            {{ csrf_field() }}
          <div class="row">
            <div class="col-md-6 col-12">
              <div class="form-group">
                <label class="label-h">Name</label>
                <input type="text" class="form-control form-input reg-input" name="f_name" autocomplete="off" value="{{ $data->f_name ?? '' }}" required>
              </div>
            </div>

            <div class="col-md-6 col-12">
              <div class="form-group">
                <label class="label-h">Surname</label>
                <input type="text" class="form-control form-input reg-input" name="l_name" autocomplete="off" value="{{ $data->l_name ?? '' }}" required>
              </div>
            </div>

            <div class="col-md-6 col-12">
              <div class="form-group">
                <label class="label-h">Birthday</label>
                <input class="form-control" id="datepicker" type="text" name="birthday" value="{{ $data->birthday ?? '' }}" readonly required>
              </div>
            </div>

            <div class="col-md-6 col-12">
              <div class="form-group">
                <label class="label-h">Gender</label>
                <select class="form-control form-input reg-select" name="sex" required>
                  <option value="" disabled="" selected="">Gender</option>
                  <option value="male" {{ $data->sex == 'male' ? 'selected' : '' }} >Male</option>
                  <option value="female" {{ $data->sex == 'female' ? 'selected' : '' }}>Female</option>
                </select>
              </div>
            </div>

            <div class="col-md-6 col-12">
              <div class="form-group">
                <label class="label-h">Phone number</label>
                <input type="text" class="form-control form-input numberonly reg-input" maxlength="10" name="tel" autocomplete="off" value="{{ $data->tel ?? '' }}" required>
              </div>
            </div>

            <div class="col-md-6 col-12">
              <div class="form-group">
                <label class="label-h">Email</label>
                <input type="email" class="form-control form-input reg-input" name="email" autocomplete="off" value="{{ $data->email ?? '' }}" required>
              </div>
            </div>

                <input type="hidden" id="form_province" name="province" value="{{ $data->province ?? '' }}">
                <input type="hidden" id="form_district" name="district" value="{{ $data->district ?? '' }}">
                <input type="hidden" id="form_sub_district" name="sub_district" value="{{ $data->sub_district ?? '' }}">
                <input type="hidden" id="form_zipcode" name="zipcode" value="{{ $data->zipcode ?? '' }}">

            <div class="col-md-6 col-12">
              <div class="form-group">
                <label class="label-h">Vehicle Brand</label>
                <select class="form-control" name="car_brand" required>
                  <option value="" selected disabled></option>
                  @foreach($vehicles as $value)
                  <option value="{{ $value->brand }}" {{ $data->car_brand == $value->brand ? 'selected' : '' }}>{{ $value->brand }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-md-6 col-12">
              <div class="form-group">
                <label class="label-h">Vin no</label>
                <input type="text" class="form-control form-input reg-input" id="vin_no" name="vin_no" minlength="17" maxlength="17" autocomplete="off" value="{{ $data->vin_no }}">
                <p style="font-size: 14px;color: #b1b4bb;">VIN: [WBS] [WD935] [4] [9P] [Y43903]</p>
              </div>
            </div>

            <div class="col-12">
              <div class="form-group">
                <label class="label-h">Address</label>
                <input type="text" class="form-control form-input reg-input" name="address" autocomplete="off" value="{{ $data->address ?? '' }}" required>
              </div>
            </div>

          </div>

          <div class="row">
            <div class="col-md-6 col-12">
              <div class="form-group focused" style="position: absolute;bottom: -300px;">
                <button type="submit" class="btn btn-warning">Save</button>
                <a href="{{ url('en/customers') }}" class="btn btn-secondary">Cancel</a>
              </div>
            </div>

            <div class="col-md-6 col-12">
              <div class="form-group focused" style="position: absolute;bottom: -300px;right: 0px;">
                <div class="col text-right">
                  <button type="button" class="btn btn-danger text-right del">Delete</button>
                </div>
              </div>
            </div>
          </div>
        </form>

        <div class="uk-container uk-padding">
          <div class="row">
            <div class="col-md-6 col-12">
              <form id="form_addr" class="demo" style="display:none;" autocomplete="off" uk-grid >
                <div class="form-group">
                  <label class="label-h" >{{ __('customer.province')}}</label>
                  <input name="province" id="province" class="uk-input uk-width-1-1 form-control form-input reg-input" type="text" value="{{ $data->province ?? '' }}" title="Please fill out this fields.">
                  <p class="reg_txt_error" id="reg_txt_error"></p>
                  <div class="uk-width-1-2@m"></div>
                </div>
              </form>
            </div>

            <div class="col-md-6 col-12">
              <form id="form_addr" class="demo" style="display:none;" autocomplete="off" uk-grid >
                <div class="form-group">
                  <label class="label-h" >{{ __('customer.district')}}</label>
                  <input name="district" id="district" class="uk-input uk-width-1-1 form-control form-input reg-input" type="text" value="{{ $data->district ?? '' }}" title="Please fill out this fields.">
                  <p class="reg_txt_error" id="reg_txt_error"></p>
                  <div class="uk-width-1-2@m"></div>
                </div>
              </form>
            </div>

            <div class="col-md-6 col-12">
              <form id="form_addr" class="demo" style="display:none;" autocomplete="off" uk-grid >
                <div class="form-group">
                  <label class="label-h" >{{ __('customer.sub_district')}}</label>
                  <input name="sub_district" id="sub_district" class="uk-input uk-width-1-1 form-control form-input reg-input" type="text" value="{{ $data->sub_district ?? '' }}" title="Please fill out this fields.">
                  <p class="reg_txt_error" id="reg_txt_error"></p>
                  <div class="uk-width-1-2@m"></div>
                </div>
              </form>
            </div>

            <div class="col-md-6 col-12">
              <form id="form_addr" class="demo" style="display:none;" autocomplete="off" uk-grid >
                <div class="form-group">
                  <label class="label-h" >{{ __('customer.zipcode')}}</label>
                  <input name="zipcode" id="zipcode" class="uk-input uk-width-1-1 form-control form-input reg-input" type="text" value="{{ $data->zipcode ?? '' }}" title="Please fill out this fields.">
                  <p class="reg_txt_error" id="reg_txt_error"></p>
                  <div class="uk-width-1-2@m"></div>
                </div>
              </form>
            </div>
          </div>
        </div>
        </div>

      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $('.del').on('click', function(){ $('#DeleteModal').modal(); });
</script>
<!-- Modal -->
<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header" style="border-bottom: 1px solid #dee2e6;">
        <h5 class="modal-title" id="exampleModalLabel">DELETE</h5>
      </div>
      <div class="modal-body" style="border-bottom: 1px solid #dee2e6;">
        Confirm delete.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn bg-gradient-secondary" data-dismiss="modal">Close</button>
        <form method="POST" id="delete_form" action="/en/customers/{{ $data->id }}">
          @method('DELETE')
          @csrf
          <button type="submit" class="btn bg-gradient-primary">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-beta.20/js/uikit.min.js"></script>
<script type="text/javascript" src="./../../../jquery.Thailand.js/dependencies/zip.js/zip.js"></script>
<script type="text/javascript" src="./../../../jquery.Thailand.js/dependencies/JQL.min.js"></script>
<script type="text/javascript" src="./../../../jquery.Thailand.js/dependencies/typeahead.bundle.js"></script>

<script type="text/javascript" src="./../../../jquery.Thailand.js/dist/jquery.Thailand-th.js"></script>
<script type="text/javascript">

    $.Thailand({
        database: './../../../jquery.Thailand.js/database/db_th.json',

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
        database: './../../../jquery.Thailand.js/database/db_th.json',
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
