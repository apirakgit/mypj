@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
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
          <div class="card">
            <div class="card-header border-0">
              <div class="col-md-5">
                <h3 class="mb-0">Create Promotion</h3>
              </div>
            </div>
            <div class="" style="margin: 14px 20px 0px 50px;">
            <form action="/en/promotion" method="POST">
              <input type="hidden" name="status" value="0">
              {{ csrf_field() }}
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Name TH</label>
                    <input type="text" class="form-control form-input reg-input" name="name" autocomplete="off" required>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Name EN</label>
                    <input type="text" class="form-control form-input reg-input" name="name_en" autocomplete="off" required>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Vehicle Brand</label>
                    <select class="form-control" name="vehicle_id" required>
                      <option value="" selected disabled></option>
                      @foreach($vehicles as $value)
                      <option value="{{ $value->id }}">{{ $value->brand }}</option>
                      @endforeach
                    </select>

                  </div>
                </div>

                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Discount %</label>
                    <input type="number" class="form-control form-input reg-input" name="discount" autocomplete="off" required>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Start Date</label>
                    <input type="text" class="form-control" id="start_date" name="start_date" autocomplete="off" required>

                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">End Date</label>
                    <input type="text" class="form-control" id="end_date" name="end_date" autocomplete="off" required>
                  </div>
                </div>

                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Status</label>
                    <select class="form-control form-input reg-input" name="status" autocomplete="off" required>
                      <option value="" disabled selected></option>
                      <option value="1">Enable</option>
                      <option value="0">Disable</option>
                    </select>

                  </div>
                </div>
                <div class="col-12" style="left: 28px">
                  <div class="form-group">
                    <input class="custom-control-input" name="is_vin" id="customCheck1" type="checkbox" value="1">
                    <label class="custom-control-label" for="customCheck1">Check Vin Master</label>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-6">
                  <button type="submit" class="btn btn-warning">Save</button>
                  <a href="{{ url('en/promotion') }}" class="btn btn-secondary">Cancel</a>
                </div>
              </div>
            </form>

            </div>

          </div>
        </div>
      </div>
    </div>

    <link rel="stylesheet" type="text/css" href="./../../datetimepicker/css/jquery.datetimepicker.min.css"/>
    <script type="text/javascript">
      $(function(){
        $('#start_date').datetimepicker({
          format:'Y-m-d H:i'
        });

        $('#end_date').datetimepicker({
          format:'Y-m-d H:i'
        });
      });
    </script>
    <script src="./../../datetimepicker/js/jquery.min.js"></script>
    <script src="./../../datetimepicker/js/jquery.datetimepicker.js"></script>

@endsection
