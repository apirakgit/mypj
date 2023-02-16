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
                <h3 class="mb-0">Create Promotion Campaign</h3>
              </div>
            </div>
            <div class="" style="margin: 14px 20px 0px 50px;">
            <form action="/en/promotion_code" method="POST">
              {{ csrf_field() }}
              <input type="hidden" name="promotion_campaign_id" value="{{ Request::get('pmcp_id') }}">
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Promotion Code</label>
                    <input type="text" class="form-control form-input reg-input" name="code" autocomplete="off" required>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Amount</label>
                    <input type="number" class="form-control form-input reg-input" name="amount" autocomplete="off"  required>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Status</label>
                    <select class="form-control" name="status" required>
                      <option value="1">Enable</option>
                      <option value="0">Disable</option>
                    </select>
                  </div>
                </div>

              </div>

              <div class="form-group row">
                <div class="col-sm-6">
                  <button type="submit" class="btn btn-warning">Save</button>
                  <a href="{{ url('en/promotion_code?pmcp_id=') . Request::get('pmcp_id')  }}" class="btn btn-secondary">Cancel</a>
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
