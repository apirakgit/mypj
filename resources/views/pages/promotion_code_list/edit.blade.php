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
                <h3 class="mb-0">Edit Promotion Code</h3>
              </div>
            </div>
            <div class="" style="margin: 14px 20px 0px 50px;">
            <form action="/en/promotion_code/{{$data->id}}" method="POST">
              <input type="hidden" name="promotion_campaign_id" value="{{$data->promotion_campaign_id}}">
              {{ csrf_field() }}
              {{ method_field('PUT') }}
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Promotion Code</label>
                    <input type="text" class="form-control form-input reg-input" name="code" autocomplete="off" value="{{$data->code}}" required>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Amount</label>
                    <input type="text" class="form-control form-input reg-input" name="amount" autocomplete="off" value="{{$data->amount}}" required>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Status</label>
                    <select class="form-control" name="status" required>
                      <option value="1" {{ $data->status == '1' ? 'selected' : '' }}>Enable</option>
                      <option value="0" {{ $data->status == '0' ? 'selected' : '' }}>Disable</option>
                    </select>
                  </div>
                </div>

              </div>

              <div class="form-group row">
                <div class="col-sm-6">
                  <button type="submit" class="btn btn-warning">Save</button>
                  <a href="{{ url('en/promotion_code?pmcp_id=') . $data->promotion_campaign_id}}" class="btn btn-secondary">Cancel</a>
                </div>
                <!--
                <div class="col text-right">
                  <button type="button" class="btn btn-danger text-right del">Delete</button>
                </div>-->
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
