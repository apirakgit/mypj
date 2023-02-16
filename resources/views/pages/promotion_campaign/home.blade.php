@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')

      <div class="container-fluid">
        <div class="row" style="margin-top: 20px;margin-bottom: 30px;">
            <!-- search -->
            <div class="card" style="width: 100%;">
              <form class="" id="frm_search" action="/en/promotion_campaign" method="get">
              <div class="card-header border-0">

                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="mb-0">Search</h2>
                    </div>
                    <!--<div class="col text-right">
                        <button type="submit" class="btn btn-primary" name="exportExcel">Export Excel</button>
                    </div>-->
                </div>
                <hr class="my-3">
              </div>

              <div class="row" style="margin-left: 40px;">
                <div class="form-group" style="margin-right: 10px;">
                    <label for="order-id" class="h6">PROMOTION CAMPAIGN NAME</label>
                    <input class="form-control" type="text" name="name" value="{{ Request::get('name', '') }}" autocomplete="off">
                </div>
                <div class="form-group" style="margin-right: 10px;">
                    <label for="order-id" class="h6">TYPE</label>
                    <select class="form-control" name="type">
                      <option value=""></option>
                      <option value="bonus_code" {{ Request::get('type') == 'bonus_code' ? 'selected' : '' }}>Bonus Code</option>
                      <option value="free_topup" {{ Request::get('type') == 'free_topup' ? 'selected' : '' }}>Free Topup</option>
                    </select>
                </div>
                <div class="form-group" style="margin-right: 10px;">
                    <label for="order-id" class="h6">REPEAT</label>
                    <select class="form-control" name="repeat">
                      <option value=""></option>
                      <option value="1" {{ Request::get('repeat') == '1' ? 'selected' : '' }}>Yes</option>
                      <option value="0" {{ Request::get('repeat') == '0' ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                <div class="form-group" style="margin-right: 10px;">
                    <label for="order-id" class="h6">STATUS</label>
                    <select class="form-control" name="status">
                      <option value=""></option>
                      <option value="1" {{ Request::get('status') == '1' ? 'selected' : '' }}>Enable</option>
                      <option value="0" {{ Request::get('status') == '0' ? 'selected' : '' }}>Disable</option>
                    </select>
                </div>
                <div class="form-group" style="margin-right: 10px;">
                    <label for="example-text-input" class="h6">DATE</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                        </div>
                        <input class="date form-control" type="text" name="dates" size="22" value="{{ Request::get('order_id', '') }}" autocomplete="off">
                    </div>
                </div>
                <script type="text/javascript">
                  $('.date').daterangepicker({
                    "dateFormat": "yyyy-mm-dd",
                    "maxDate": new Date(),
                    "autoApply": true,
                    "maxSpan": {
                        "days": 180
                    },
                    locale: {
                     format: 'YYYY/MM/DD'
                    },
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'Last 180 Days': [moment().subtract(180, 'days'), moment()]
                    }
                  }, function(start, end, label) {
                    //console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
                  }).val('{{ Request::get('dates', '') }}');
                </script>

              </div>

              <div class="row" style="margin-left: 40px;">
                <div class="form-group" style="margin-right: 10px;">
                  <button type="submit" class="btn btn-warning">Search</button>
                </div>
                <div class="form-group" style="margin-right: 10px;">
                  <a href="/en/promotion_campaign/create" class="btn btn-primary">Add</a>
                </div>
                <div class="form-group" style="margin-right: 10px;">
                  <button type="button" class="btn btn-secondary reset" onclick="">Clear</button>
                </div>
                <script type="text/javascript">
                  $(".reset").click(function() {
                    $(this).closest('form').find("input[type=text], textarea, select").val("");
                  });
                </script>
              </div>
              </form>
            </div>
        </div>

        <div class="row" style="margin-top: 20px;margin-bottom: 30px;">
          <div class="col">
            <div class="row">
              <div class="col">
                @if (session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif
                <div class="card">
                  <!-- Card header -->
                  <div class="card-header border-0">
                      <div class="row align-items-center">
                          <div class="col">
                              <h3 class="mb-0">Promotion Campaign List</h3>
                          </div>
                          <div class="col text-right">
                          </div>
                      </div>
                  </div>
                  <!-- Light table -->
                  <div class="table-responsive" style="overflow-y: hidden;">
                    <table class="table table-striped" id="myTable2">
                      <thead class="thead-light">
                        <tr>
    					            <th>Promotion Campaign Name</th>
    					            <th>Type</th>
    					            <th>Start Date</th>
    					            <th>End Date</th>
    					            <th>Repeat Status</th>
    					            <th>Status</th>
                          <th>Create by</th>
                          <th>Create Date</th>
    					            <th>Promotion Code</th>
    					            <th></th>
                        </tr>
                      </thead>
                      <tbody class="list" id="myTable">
                        @foreach ($datas as $key => $data)
                        <tr>
    					              <td>{{ $data->name }} / {{ $data->name_en }}</td>
    					              <td>{{ $data->type }}</td>
    					              <td>{{ $data->start_date }}</td>
    					              <td>{{ $data->end_date }}</td>
    					              <td>{{ $data->repeat_status }}</td>
    					              <td>{{ $data->status }}</td>
    					              <td>{{ $data->create_by }}</td>
    					              <td>{{ $data->created_at }}</td>
    					              <td>
                              <a href="/en/promotion_code?pmcp_id={{$data->id}}" class="btn btn-sm btn-success">Code list</a>
                            </td>
                            <td>
                              <a href="/en/promotion_campaign/{{$data->id}}/edit" class="btn btn-sm btn-primary">Edit</a>
                            </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                    <div class="card-footer py-4 row no-gutters">
                        <div class="col">
                          {{ $datas->appends($_GET)->links( "pagination::bootstrap-4" ) }}
                        </div>
                        <div class="col text-right">
                          <h5>Showing {{ $datas->currentPage() }} to {{ $datas->count() }} of {{ $datas->total() }} results</h5>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush
