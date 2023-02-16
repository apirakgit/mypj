@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')

      <div class="container-fluid">
        <div class="row" style="margin-top: 20px;margin-bottom: 30px;">
          <div class="col">
            <!-- search -->
            <div class="card">
              <form class="" id="frm_search" action="{{ route ('search','en')}}" method="get">
              <div class="card-header border-0">

                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="mb-0">Search / Export</h2>
                    </div>
                    <!--@if( Request::get('dates') != '' )
                    <div class="col text-right">
                        <button type="submit" class="btn btn-sm btn-primary" name="exportExcel">Export Excel</button>
                    </div>
                    @endif-->
                    <button type="submit" class="btn btn-primary" name="exportExcel">Export Excel</button>
                </div>
                <hr class="my-3">
              </div>


              <div class="row" style="margin-left: 40px;">
                <div class="form-group" style="margin-right: 10px;">
                    <label for="order-id" class="h6">TRANSACTION ID</label>
                    <input class="form-control" type="text" name="order_id" id="order-id" value="{{ Request::get('order_id', '') }}">
                </div>
                <div class="form-group" style="margin-right: 10px;">
                    <label for="name" class="h6">NAME</label>
                    <input class="form-control" type="text" name="name" id="name" value="{{ Request::get('name', '') }}">
                </div>
                <div class="form-group" style="margin-right: 10px;">
                    <label for="email" class="h6">EMAIL</label>
                    <input class="form-control" type="text" name="email" id="email" value="{{ Request::get('email', '') }}">
                </div>
                <div class="form-group" style="margin-right: 10px;">
                    <label for="phone" class="h6">PHONE</label>
                    <input class="form-control" type="text" name="phone" id="phone" value="{{ Request::get('phone', '') }}">
                </div>
                <div class="form-group" style="margin-right: 10px;">
                    <label for="name_admin" class="h6">ADMIN NAME</label>
                    <input class="form-control" type="text" name="name_admin" id="text-white" value="{{ Request::get('name_admin', '') }}">
                </div>
              </div>
              <div class="row" style="margin-left: 40px;">
                <div class="form-group" style="margin-right: 10px;">
                  <label for="amount" class="h6">AMOUNT</label>
                  <select class="form-control" name="amount" id="amount">
                    <option></option>
                    <option value="000000030000" {{ ( Request::get('amount') == '000000030000' ) ? 'selected' : '' }}>300</option>
                    <option value="000000050000" {{ ( Request::get('amount') == '000000050000' ) ? 'selected' : '' }}>500</option>
                    <option value="000000100000" {{ ( Request::get('amount') == '000000100000' ) ? 'selected' : '' }}>1,000</option>
                    <option value="000000200000" {{ ( Request::get('amount') == '000000200000' ) ? 'selected' : '' }}>2,000</option>
                  </select>
                </div>

                <div class="form-group" style="margin-right: 10px;">
                  <label for="status" class="h6">STATUS</label>
                  <select class="form-control" name="status" id="status">
                    <option value="" ></option>
                    <option value="1" {{ ( Request::get('status') == '1' ) ? 'selected' : '' }} >Approve</option>
                    <option value="9" {{ ( Request::get('status') == '9' ) ? 'selected' : '' }} >Reject</option>
                    <option value="0" {{ ( Request::get('status') == '0' ) ? 'selected' : '' }} >No Action</option>
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
              </div>
              <div class="row" style="margin-left: 40px;">
                <div class="form-group" style="margin-right: 10px;">
                  <button type="submit" class="btn btn-warning">Search</button>
                </div>
                <div class="form-group" style="margin-right: 10px;">
                  <button type="button" class="btn btn-secondary reset">Clear</button>
                </div>
              </div>
              </form>
            </div>
          </div>
        </div>
            <!-- /search -->
        <div class="row">
          <div class="col">
            <div class="card">

              <!-- Card header -->
              <div class="card-header border-0">
                  <div class="row align-items-center">
                      <div class="col">
                          <h3 class="mb-0">Transaction List</h3>
                      </div>

                      <div class="col text-right">
                          <h5>Showing {{ $trans->currentPage() }} to {{ $trans->count() }} of {{ $trans->total() }} results</h5>
                      </div>

                  </div>
              </div>

              <!-- Light table -->
              <div class="table-responsive" style="overflow-y: hidden;">
                <table class="table table-striped" id="myTable2">
                  <thead class="thead-light">
                    <tr>
					  <th scope="col" class="sort" data-sort="admin_timestamp" onclick="sortTable(8)"><b> status </b><i class="fa fa-fw fa-sort"></i></th>
                      <th scope="col" class="sort" data-sort="tran_id" ><b>Transaction ID </b></th>
                      <th scope="col" class="sort" data-sort="tran_stamp" onclick="sortTable(1)"><b> Transaction</br>Timestamp </b><i class="fa fa-fw fa-sort"></i></th>
                      <th scope="col" class="sort" data-sort="channel" ><b> PAYMENT CHANNEL </b></th>
                      <th scope="col" class="sort" data-sort="email" ><b> EMAIL </b></th>
                      <th scope="col" class="sort" data-sort="phone" ><b> PHONE </b></th>
                      <th scope="col" class="sort" data-sort="amount" ><b> CUSTOMER PAID AMOUNT</br>(BAHT) </b></th>
                      <th scope="col" ><b> PROMOTION NAME</b></th>
                      <th scope="col" ><b> CODE</b></th>
                      <th scope="col" class="sort" data-sort="discount" ><b> DISCOUNT</br>(BAHT) </b></th>
                      <th scope="col" ><b> BONUS</br>(BAHT)</b></th>
                      <th scope="col" ><b> FREE TOP-UP</br>(BAHT)</b></th>
                      <th scope="col" class="sort" data-sort="topup_amount" ><b> TOP UP AMOUNT</br>(BAHT) </b></th>
                      <!--<th scope="col" class="sort" data-sort="admin_email" ><b> Admin </b></th>
                      <th scope="col" class="sort" data-sort="admin_timestamp" onclick="sortTable(7)"><b> Admin</br>Timestamp </b><i class="fa fa-fw fa-sort"></i></th>-->
                    </tr>
                  </thead>
                  <tbody class="list" id="myTable">
                    @foreach ($trans as $key => $tran)
                    <tr>
					  <td>
                        <span class="badge badge-dot mr-4">
                          @if($tran->status == 'Approved')
                            <i class="bg-success"></i>
                            <span class="status text-green">{{ $tran->status }}</span>
                          @elseif($tran->status == 'Reject')
                            <i class="bg-danger"></i>
                            <span class="status text-red">{{ $tran->status }}</span>
                          @else

                          @if($tran->channel_response_code == 'Success')
                            @if($tran->status == 'Pending')
                              <a class="btn btn-sm btn-success" onclick="return app_tran()" href="{{ route( 'changeStatus',['en', 'order_id'=>$tran->order_id, 'status'=>'1' ] ) }}">Approve</a>
                              <a class="btn btn-sm btn-danger" onclick="return rej_tran()" href="{{ route( 'changeStatus', ['en', 'order_id'=>$tran->order_id, 'status'=>'9' ] ) }}">Reject</a>
                            @endif
                          @else
                             <h5 class="text-red">{{ $tran->channel_response_code }}</h5>
                          @endif

                          @endif
                        </span>
                      </td>
                      <td class="">
                        {{ $tran->order_id }}
                      </td>
                      <td class="">
                        <?php
                          echo date('Y-m-d', strtotime($tran->transaction_datetime)) . "<br>" . date('H:i:s', strtotime($tran->transaction_datetime));
                         ?>
                      </td>
                      <td class=""> {{ $tran->payment_channel }} </td>
                      <td class=""> {{ $tran->email }} </td>
                      <td class=""> {{ $tran->tel }} </td>
                      <td class="float-right"> {{ number_format(substr((int)($tran->amount),0,-2)) }} </td>
                      <td class=""> {{ $tran->promotion_name ?? '' }} </td>
                      <td class=""> {{ $tran->promotion_code ?? '' }} </td>
                      <td class=""> {{ $tran->discount ?? '' }} </td>
                      <td class=""> {{ $tran->bonus ?? '' }} </td>
                      <td class=""> {{ $tran->free_topup ?? '' }} </td>
                      @if( isset($tran->topup_amount))
                        <td class=""> {{ number_format(substr((int)($tran->topup_amount),0,-2)) }} </td>
                      @else
                        <td></td>
                      @endif

                      <!--<td class=""> {{ $tran->admin_name }} </td>
                      <td class="">
                        <?php
                        if(isset($tran->approve_date)){
                          echo date('Y-m-d', strtotime($tran->approve_date)) . "<br>" . date('H:i:s', strtotime($tran->approve_date));
                        }
                         ?>
                      </td>-->

                    </tr>
                    @endforeach

                  </tbody>
                </table>
              </div>
              <!-- Card footer -->
              <div class="card-footer py-4 row no-gutters">
              <!-- <div class="row align-items-center">   -->
                  <div class="col">
                    {{ $trans->appends($_GET)->links( "pagination::bootstrap-4" ) }}
                  </div>
                  <div class="col text-right">
                    <h5>Showing {{ $trans->currentPage() }} to {{ $trans->count() }} of {{ $trans->total() }} results</h5>
                  </div>

              </div>
            </div>
          </div>
        </div>
      </div>

      <script type="text/javascript">

        function app_tran(){
          if(confirm('คุณยืนยันที่จะทำการ Approve Transaction นี้ใช่หรือไม่')){
            $('#over_lay').css('display','block');
            return true;
          }else {
            return false;
          }
        }

        function rej_tran(){
          if(confirm('คุณยืนยันที่จะทำการ Reject Transaction นี้ใช่หรือไม่')){
            $('#over_lay').css('display','block');
            return true;
          }else {
            return false;
          }
        }

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

           // Filter table

          $(document).ready(function(){

            $(".reset").click(function() {
                $(this).closest('form').find("input[type=text], textarea, select").val("");
            });

            $("#tableSearch").on("keyup", function() {
              var value = $(this).val().toLowerCase();
              $("#myTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
              });
            });

            $('.search-panel .dropdown-menu').find('a').click(function(e) {
          		e.preventDefault();
          		var param = $(this).attr("href").replace("#","");
          		var concept = $(this).text();
          		$('.search-panel span#search_concept').text(concept);
          		$('.input-group #search_param').val(param);
          	});
          });

          function sortTable(n) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.getElementById("myTable2");
            switching = true;
            // Set the sorting direction to ascending:
            dir = "asc";
            /* Make a loop that will continue until
            no switching has been done: */
            while (switching) {
              // Start by saying: no switching is done:
              switching = false;
              rows = table.rows;
              /* Loop through all table rows (except the
              first, which contains table headers): */
              for (i = 1; i < (rows.length - 1); i++) {
                // Start by saying there should be no switching:
                shouldSwitch = false;
                /* Get the two elements you want to compare,
                one from current row and one from the next: */
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];
                /* Check if the two rows should switch place,
                based on the direction, asc or desc: */
                if (dir == "asc") {
                  if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    // If so, mark as a switch and break the loop:
                    shouldSwitch = true;
                    break;
                  }
                } else if (dir == "desc") {
                  if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                    // If so, mark as a switch and break the loop:
                    shouldSwitch = true;
                    break;
                  }
                }
              }
              if (shouldSwitch) {
                /* If a switch has been marked, make the switch
                and mark that a switch has been done: */
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                // Each time a switch is done, increase this count by 1:
                switchcount ++;
              } else {
                /* If no switching has been done AND the direction is "asc",
                set the direction to "desc" and run the while loop again. */
                if (switchcount == 0 && dir == "asc") {
                  dir = "desc";
                  switching = true;
                }
              }
            }
          }

      </script>

@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush
