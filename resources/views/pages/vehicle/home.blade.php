@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')

      <div class="container-fluid">
        <div class="row" style="margin-top: 20px;margin-bottom: 30px;">
            <!-- search -->
            <div class="card" style="width: 100%;">
              <form class="" id="frm_search" action="/en/vehicle" method="get">
              <div class="card-header border-0">

                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="mb-0">Search / Export</h2>
                    </div>
                </div>
                <hr class="my-3">
              </div>


              <div class="row" style="margin-left: 40px;">
                <div class="form-group" style="margin-right: 10px;">
                    <label for="order-id" class="h6">BRAND</label>
                    <input class="form-control" type="text" name="brand" value="{{ Request::get('brand', '') }}" autocomplete="off">
                </div>
                <div class="form-group" style="margin-right: 10px;">
                    <label for="name" class="h6">STATUS</label>
                    <select class="form-control" name="status">
                      <option value=""></option>
                      <option value="1" {{ Request::get('status') == '1' ? 'selected' : '' }}>Enable</option>
                      <option value="0" {{ Request::get('status') == '0' ? 'selected' : '' }}>Disable</option>
                    </select>
                </div>
              </div>

              <div class="row" style="margin-left: 40px;">
                <div class="form-group" style="margin-right: 10px;">
                  <button type="submit" class="btn btn-warning">Search</button>
                </div>
                <div class="form-group" style="margin-right: 10px;">
                  <a href="/en/vehicle/create" class="btn btn-primary">Add</a>
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
                              <h3 class="mb-0">Vehicle Brand List</h3>
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
    					            <th>Status</th>
    					            <th>Create Date</th>
    					            <th></th>
                        </tr>
                      </thead>
                      <tbody class="list" id="myTable">
                        @foreach ($datas as $key => $data)
                        <tr>
    					              <td>{{$data->brand}}</td>
    					              <td>{{ $data->status == 1 ? 'Enable' : 'Disable' }}</td>
    					              <td>{{ $data->created_at }}</td>
                            <td>
                              <!--@if($data->customer_cnt == 0)
                              <a href="/en/vehicle/{{$data->id}}/edit" class="btn btn-sm btn-primary">Edit</a>
                              @endif-->
                              <a href="/en/vehicle/{{$data->id}}/edit" class="btn btn-sm btn-primary">Edit</a>
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
