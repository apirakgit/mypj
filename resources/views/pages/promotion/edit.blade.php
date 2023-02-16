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
            <form action="/en/promotion/{{$data->id}}" method="POST">
              {{ csrf_field() }}
              {{ method_field('PUT') }}
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Name TH</label>
                    <input type="text" class="form-control form-input reg-input" name="name" autocomplete="off" value="{{ $data->name }}" required>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Name EN</label>
                    <input type="text" class="form-control form-input reg-input" name="name_en" autocomplete="off" value="{{ $data->name_en }}" required>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Vehicle Brand</label>
                    <select class="form-control" name="vehicle_id" required>
                      <option value="" selected disabled></option>
                      @foreach($vehicles as $value)
                      <option value="{{ $value->id }}" {{ $data->vehicle_id == $value->id ? 'selected' : ''}}>{{ $value->brand }}</option>
                      @endforeach
                    </select>

                  </div>
                </div>

                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Discount %</label>
                    <input type="number" class="form-control form-input reg-input" name="discount" autocomplete="off" value="{{ $data->discount }}" required>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Start Date</label>
                    <input type="text" class="form-control" id="start_date" name="start_date" autocomplete="off" value="{{ $data->start_date }}" required>

                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">End Date</label>
                    <input type="text" class="form-control" id="end_date" name="end_date" autocomplete="off" value="{{ $data->end_date }}" required>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Status</label>
                    <select class="form-control form-input reg-input" name="status" autocomplete="off" required>
                      <option value="" disabled selected></option>
                      <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>Enable</option>
                      <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>Disable</option>
                    </select>

                  </div>
                </div>
                <div class="col-12" style="left: 28px">
                  <div class="form-group">
                    <input class="custom-control-input" name="is_vin" id="customCheck1" type="checkbox" value="1" {{ $data->is_vin > 0 ? 'Checked' : ''}}>
                    <label class="custom-control-label" for="customCheck1">Check Vin Master</label>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-6">
                  <button type="submit" class="btn btn-warning">Save</button>
                  <a href="{{ url('en/promotion') }}" class="btn btn-secondary">Cancel</a>
                </div>
                <div class="col text-right">
                  <button type="button" class="btn btn-danger text-right del">Delete</button>
                </div>
              </div>
            </form>

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
            <form method="POST" id="delete_form" action="/en/promotion/{{ $data->id }}">
              @method('DELETE')
              @csrf
              <button type="submit" class="btn bg-gradient-primary">Delete</button>
            </form>

          </div>
        </div>
      </div>
    </div>

    <link rel="stylesheet" type="text/css" href="./../../../datetimepicker/css/jquery.datetimepicker.min.css"/>
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
    <script src="./../../../datetimepicker/js/jquery.min.js"></script>
    <script src="./../../../datetimepicker/js/jquery.datetimepicker.js"></script>

@endsection
