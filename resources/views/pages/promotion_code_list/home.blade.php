@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')

      <div class="container-fluid">
        <div class="row" style="margin-top: 20px;margin-bottom: 30px;">
            <div class="col">
            <!-- search -->
            @if (session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
            @if (session('dup'))
                <div class="alert alert-danger">
                  <h1>Promotion Code ซ้ำ</h1>
                    @foreach (session('dup') as $value)
                        <p>{{ $value }}</p>
                    @endforeach
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            </div>
            <div class="card" style="width: 100%;">
              <form class="" id="frm_search" action="/en/promotion_code_list" method="POST" enctype="multipart/form-data">
                @csrf
              <div class="card-header border-0">

                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="mb-0"></h2>
                    </div>
                    <!--<div class="col text-right">
                        <button type="submit" class="btn btn-primary" name="exportExcel">Export Excel</button>
                    </div>-->
                </div>
              </div>

              <div class="row" style="margin-left: 40px;">

                <div class="form-group" style="margin-right: 10px;">
                  <a href="/en/promotion_code?pmcp_id={{Request::get('pmcp_id','')}}" class="btn btn-secondary">Back</a>
                </div>
              </div>

              </form>
            </div>

        </div>

        <div class="row" style="margin-top: 20px;margin-bottom: 30px;">

                <div class="card" style="width: 100%;">
                  <!-- Card header -->
                  <div class="card-header border-0">
                      <div class="row align-items-center">
                          <div class="col">
                              <h3 class="mb-0">Promotion Code List ( {{ $promotioncode->code }} )</h3>
                          </div>
                      </div>
                  </div>
                  <!-- Light table -->
                  <div class="table-responsive" style="overflow-y: hidden;">
                    <table class="table table-striped" id="myTable2">
                      <thead class="thead-light">
                        <tr>
    					            <th>No.</th>
    					            <th>Customer Name</th>
    					            <th>Phone Number</th>
    					            <th>Email</th>
    					            <th>Date Create</th>
    					            <th></th>
                        </tr>
                      </thead>
                      <tbody class="list" id="myTable">
                        @foreach ($datas as $key => $data)
                        <tr>
                            <td>
                              <?php
                                $num_row = 0;
                                if($datas->currentPage() == 1){
                                  $num_row = $key + 1;
                                }else{
                                  $num_row = $datas->currentPage() . ($key + 1);
                                }
                                echo $num_row;
                              ?>
                            </td>
    					              <td>{{ $data->f_name . ' ' . $data->l_name }}</td>
    					              <td>{{ $data->tel }}</td>
    					              <td>{{ $data->email }}</td>
    					              <td>{{ $data->created_at }}</td>
                            <td>
                              <a href="#" class="btn btn-sm btn-danger" onclick="popup_delete({{$data->id}},{{$num_row}})">Delete</a>
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

          <script type="text/javascript">
            function popup_delete(id,num_row){
              $('#delete_form').attr('action', '/en/promotion_code_list/'+id);
              $('#DeleteModal').modal();
              $('#msg_delete').html('Confirm delete No.'+num_row);
              $('#num_row').val(num_row);
            }
          </script>
          <!-- Modal -->
          <div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header" style="border-bottom: 1px solid #dee2e6;">
                  <h5 class="modal-title" id="exampleModalLabel">DELETE</h5>
                </div>
                <div class="modal-body" style="border-bottom: 1px solid #dee2e6;" id="msg_delete">
                  Confirm delete.
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn bg-gradient-secondary" data-dismiss="modal">Close</button>
                  <form method="POST" id="delete_form" action="">
                    <input type="hidden" name="num_row" id="num_row" value="">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn bg-gradient-primary">Delete</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush
