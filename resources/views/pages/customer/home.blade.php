@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')

      <div class="container-fluid">
        <div class="row" style="margin-top: 20px;margin-bottom: 30px;">
            <!-- search -->
            <div class="card" style="width: 100%;">
              <form class="" id="frm_search" action="/en/customers" method="get">
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
                    <label for="order-id" class="h6">NAME - SURNAME</label>
                    <input class="form-control" type="text" name="name" value="{{ Request::get('name', '') }}">
                </div>
                <div class="form-group" style="margin-right: 10px;">
                    <label for="name" class="h6">VEHICLE BRAND</label>
                    <select class="form-control" name="vehicle">
                      <option value="" selected disabled></option>
                      @foreach($vehicles as $value)
                      <option value="{{ $value->brand }}" {{ Request::get('vehicle') == $value->brand ? 'selected' : ''}} >{{ $value->brand }}</option>
                      @endforeach
                    </select>

                </div>
                <div class="form-group" style="margin-right: 10px;">
                    <label for="email" class="h6">VIN NO.</label>
                    <input class="form-control" type="text" name="vin_no" value="{{ Request::get('vin_no', '') }}">
                </div>
                <div class="form-group" style="margin-right: 10px;">
                    <label for="phone" class="h6">E-MAIL</label>
                    <input class="form-control" type="text" name="email" value="{{ Request::get('email', '') }}">
                </div>
                <div class="form-group" style="margin-right: 10px;">
                    <label for="name_admin" class="h6">PHONE NO</label>
                    <input class="form-control" type="text" name="tel" value="{{ Request::get('tel', '') }}">
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
              </div>

              <div class="row" style="margin-left: 40px;">
                <div class="form-group" style="margin-right: 10px;">
                  <button type="submit" class="btn btn-warning">Search</button>
                </div>
                <div class="form-group" style="margin-right: 10px;">
                  <a href="/en/customers/create" class="btn btn-primary">Add</a>
                </div>
                <div class="form-group" style="margin-right: 10px;">
                  <button type="button" class="btn btn-secondary reset">Clear</button>
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
                              <h3 class="mb-0">Customer List</h3>
                          </div>
                      </div>
                  </div>
                  <!-- Light table -->
                  <div class="table-responsive" style="overflow-y: hidden;">
                    <table class="table table-striped" id="myTable2">
                      <thead class="thead-light">
                        <tr>
    					            <th>Status</th>
                          <th>Register Date</th>
    					            <th>Name - Surname</th>
                          <th>E-mail</th>
                          <th>Phone No</th>
    					            <!--<th>Address</th>
    					            <th>District</th>
    					            <th>Sub-District</th>
    					            <th>Province</th>
    					            <th>Post Code</th>
    					            <th>Country</th>
    					            <th>Birth Date</th>
    					            <th>Gender</th>-->
    					            <th>Vehicle Brand</th>
    					            <th>Vin No.</th>
    					            <th></th>
                        </tr>
                      </thead>
                      <tbody class="list" id="myTable">
                        @foreach ($customers as $key => $data)
                        <tr>
    					              <!--<td class="{{ ( $data->status_code != '' ) ? 'text-red' : 'text-green' }}">{{ ( $data->status_code != '' ) ? $data->status_code : 'Approved' }}</td>-->
                            @if( $data->status_code != '' && $data->virta_id == '')
                            <td class="text-red">{{ $data->status_code }}</td>
                            @else
                            <td class="text-green">Approved</td>
                            @endif
                            <td>{{ $data->register_date }}</td>
                            <td>{{ $data->name }}</td>
                            <td>{{ $data->email }}</td>
    					              <td>{{ str_pad($data->tel,10,"0",STR_PAD_LEFT) }}</td>
    					              <!--<td>{{ $data->address }}</td>
    					              <td>{{ $data->district }}</td>
    					              <td>{{ $data->sub_district }}</td>
    					              <td>{{ $data->province }}</td>
    					              <td>{{ $data->zipcode }}</td>
    					              <td>THAILAND</td>
    					              <td>{{ $data->birthday }}</td>
    					              <td>{{ $data->sex }}</td>-->
    					              <td>{{ $data->car_brand }}</td>
    					              <td>{{ $data->vin_no }}</td>
                            <td class="text-right">
                              <a href="{{ url()->current() . '/' . $data->id . '/edit' }}">
                                <button type="button" class="btn btn-sm btn-primary mt-2 me-2">Edit</button>
                              </a>
                            </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                    <div class="card-footer py-4 row no-gutters">
                        <div class="col">
                          {{ $customers->appends($_GET)->links( "pagination::bootstrap-4" ) }}
                        </div>
                        <div class="col text-right">
                          <h5>Showing {{ $customers->currentPage() }} to {{ $customers->count() }} of {{ $customers->total() }} results</h5>
                        </div>
                    </div>
                  </div>
                </div>
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
@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush
