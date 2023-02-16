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
                  <h1>Code ซ้ำ</h1>
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
              <form class="" id="frm_search" action="/en/promotion_code_im" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="pmcp_id" value="{{ $pmcp->id }}">
              <div class="card-header border-0">

                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="mb-0">Search / Import / Export</h2>
                    </div>
                    <!--<div class="col text-right">
                        <button type="submit" class="btn btn-primary" name="exportExcel">Export Excel</button>
                    </div>-->
                </div>
                <hr class="my-3">
              </div>

              <div class="row" style="margin-left: 40px;">
                <div class="form-group" style="margin-right: 10px;">
                    <label for="order-id" class="h6">PROMOTION CODE</label>
                    <input class="form-control" type="text" name="code" value="{{ Request::get('code', '') }}" autocomplete="off">
                </div>
                <div class="form-group" style="margin-right: 10px;">
                    <label for="order-id" class="h6">IMPORT PROMOTION CODE</label>
                    <div class="form-group" style="margin-right: 10px;">
                        <div class="custom-file text-left">
                            <input type="file" name="file" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                    </div>
                </div>
                <script>
                  $('#customFile').on('change',function(){
                      var fileName = $(this).val();
                      $(this).next('.custom-file-label').html(fileName);
                  })
                </script>

              </div>

              <div class="row" style="margin-left: 40px;">
                <div class="form-group" style="margin-right: 10px;">
                  <button type="submit" class="btn btn-warning">Search</button>
                </div>
                <div class="form-group" style="margin-right: 10px;">
                  <a href="/en/promotion_code/create?pmcp_id={{ $pmcp->id }}" class="btn btn-primary">Add</a>
                </div>
                <div class="form-group" style="margin-right: 10px;">
                  <button type="submit" class="btn btn-info" name="importExcel">Import</button>
                </div>
				        <div class="form-group" style="margin-right: 10px;">
                  <a href="/en/export-promotioncode/{{ Request('pmcp_id')}}" class="btn btn-success">Export</a>
                </div>
                <div class="form-group" style="margin-right: 10px;">
                  <a href="/en/promotion_campaign" class="btn btn-secondary">Cancel</a>
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
                              <h3 class="mb-0">Promotion Code List : ( {{ $pmcp->name . ' / ' . $pmcp->name_en }}   )</h3>
                          </div>
                          <div class="col text-right">
                              <h5>Showing {{ $datas->currentPage() }} to {{ $datas->count() }} of {{ $datas->total() }} results</h5>
                          </div>
                      </div>
                  </div>
                  <!-- Light table -->
                  <div class="table-responsive" style="overflow-y: hidden;">
                    <table class="table table-striped" id="myTable2">
                      <thead class="thead-light">
                        <tr>
    					            <th>Code</th>
    					            <th>Amount</th>
    					            <th>Status</th>
    					            <th>User Active</th>
    					            <th></th>
                        </tr>
                      </thead>
                      <tbody class="list" id="myTable">
                        @foreach ($datas as $key => $data)
                        <tr>
    					              <td>{{ $data->code }}</td>
    					              <td>{{ $data->amount }}</td>
    					              <td>{{ $data->status }}</td>
    					              <td>
                              @if( $data->active > 0)
                                <a href="/en/promotion_code_list/{{ $data->id }}?pmcp_id={{$pmcp->id}}">{{ $data->active }}</a>
                              @else
                                0
                              @endif

                            </td>
    					              <td>
                              <!--<a href="/en/promotion_code/{{$data->id}}/edit"><button type="button" class="btn btn-sm btn-primary" {{ $data->active > 0 ? 'disabled' : '' }} >Edit</button></a>-->
                              <a href="/en/promotion_code/{{$data->id}}/edit"><button type="button" class="btn btn-sm btn-primary" >Edit</button></a>
                            </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                    <div class="card-footer py-4 row no-gutters">
                        <div class="col">
                          {{ $datas->appends($_GET)->links( "pagination::bootstrap-4" ) }}
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
