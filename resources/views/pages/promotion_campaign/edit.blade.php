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
                <h3 class="mb-0">Edit Promotion Campaign</h3>
              </div>
            </div>
            <div class="" style="margin: 14px 20px 0px 50px;">
            <form action="/en/promotion_campaign/{{$data->id}}" method="POST">
              <input type="hidden" name="status" value="0">
              {{ csrf_field() }}
              {{ method_field('PUT') }}
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Promotion Campaign Name TH </label>
                    <input type="text" class="form-control form-input reg-input" name="name" autocomplete="off" value="{{$data->name}}" required>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Promotion Campaign Name EN </label>
                    <input type="text" class="form-control form-input reg-input" name="name_en" autocomplete="off" value="{{$data->name_en}}" required>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Type</label>
                    <select class="form-control" name="type" id="type" required>
                      <option value="bonus_code" {{ $data->type == 'bonus_code' ? 'selected' : '' }}>Bonus Code</option>
                      <option value="free_topup" {{ $data->type == 'free_topup' ? 'selected' : '' }}>Free Topup</option>
                    </select>
                  </div>
                </div>
                <script>
                $('#type').on('change', function() {
                  //alert(this.value);
                  if( this.value == 'bonus_code'){
                    $('#span_type').html('%').show();
                  }else{
                    $('#span_type').hide();
                  }
                });
                </script>
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Price <span id="span_type" >{{ $data->type == 'bonus_code' ? '%' : ''}}</span></label>
                    <input type="number" class="form-control form-input reg-input" name="price" autocomplete="off" value="{{$data->price}}" required>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Start Date</label>
                    <input type="text" class="form-control" id="start_date" name="start_date" autocomplete="off" value="{{$data->start_date}}" required>

                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">End Date</label>
                    <input type="text" class="form-control" id="end_date" name="end_date" autocomplete="off" value="{{$data->end_date}}" required>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Status</label>
                    <select class="form-control" name="status" required>
                      <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>Enable</option>
                      <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>Disable</option>
                    </select>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Repeat Status</label>
                    <select class="form-control" name="repeat_status" required>
                      <option value="1" {{ $data->repeat_status == 1 ? 'selected' : '' }}>Yes</option>
                      <option value="0" {{ $data->repeat_status == 0 ? 'selected' : '' }}>No</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <div class="col-sm-6">
                  <button type="submit" class="btn btn-warning">Save</button>
                  <a href="{{ url('en/promotion_campaign') }}" class="btn btn-secondary">Cancel</a>
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
            <form method="POST" id="delete_form" action="/en/promotion_campaign/{{ $data->id }}">
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
