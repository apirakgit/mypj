@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')

      <div class="container-fluid">
        <div class="row" style="margin-top: 20px;margin-bottom: 30px;">
            <!-- search -->
            <div class="card" style="width: 100%;">
              <form class="" id="frm_search" action="/en/vinno" method="get">
              <div class="card-header border-0">

                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="mb-0">Search / Export</h2>
                    </div>
                    <div class="col text-right">
                        <button type="submit" class="btn btn-primary" name="exportExcel">Export Excel</button>
                    </div>
                </div>
                <hr class="my-3">
              </div>

              <div class="row" style="margin-left: 40px;">
                <div class="form-group" style="margin-right: 10px;">
                    <label for="order-id" class="h6">VIN NO.</label>
                    <input class="form-control" type="text" name="vin_no" value="{{ Request::get('vin_no', '') }}" autocomplete="off">
                </div>
                <div class="form-group" style="margin-right: 10px;">
                    <label for="name" class="h6">STATUS</label>
                    <select class="form-control" name="status">
                      <option value=""></option>
                      <option value="1" {{ Request::get('status') == '1' ? 'selected' : '' }}>Bound</option>
                      <option value="0" {{ Request::get('status') == '0' ? 'selected' : '' }}>Store</option>
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
                  <a href="/en/vinno/create" class="btn btn-primary">Add</a>
                </div>
                <div class="form-group" style="margin-right: 10px;">
                  <a href="/en/file-import-export/vinno" class="btn btn-primary">Import</a>
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
                              <h3 class="mb-0">VIN No List</h3>
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
    					            <th>Brand</th>
    					            <th>Vin No</th>
    					            <th>Import Time</th>
    					            <th>Bound Time</th>
    					            <th>Status</th>
    					            <th>Admin</th>
    					            <th>User_Bound</th>
    					            <th>Action</th>
    					            <th>Create Date</th>
    					            <th></th>
                        </tr>
                      </thead>
                      <tbody class="list" id="myTable">
                        @foreach ($vinno as $key => $data)
                        <tr>
    					              <td>{{$data->brand}}</td>
    					              <td>{{$data->vin_no}}</td>
    					              <td>{{$data->import_time}}</td>
    					              <td>{{$data->bound_time}}</td>
                            <td>{{ $data->status }}</td>
                            <td>{{$data->admin}}</td>
                            <td>{{$data->f_name . ' ' . $data->l_name}}</td>
                            <td>{{ $data->action }}</td>
                            <td>{{ $data->create_date }}</td>
                            <td><a href="/en/vinno/{{$data->id}}/edit" class="btn btn-sm btn-primary">Edit</a></td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                    <div class="card-footer py-4 row no-gutters">
                        <div class="col">
                          {{ $vinno->appends($_GET)->links( "pagination::bootstrap-4" ) }}
                        </div>
                        <div class="col text-right">
                          <h5>Showing {{ $vinno->currentPage() }} to {{ $vinno->count() }} of {{ $vinno->total() }} results</h5>
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
