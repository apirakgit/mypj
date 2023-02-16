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
                <h3 class="mb-0">Edit Vin No</h3>
              </div>
            </div>
            <div class="" style="margin: 14px 20px 0px 50px;">
            <form action="/en/vinno/{{$data->id}}" method="POST">
              {{ csrf_field() }}
              {{ method_field('PUT') }}
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Vin No</label>
                    <input type="text" class="form-control form-input reg-input" name="vin_no" minlength="17" maxlength="17" autocomplete="off" value="{{ $data->vin_no }}" required {{ ($data->cc > 0) ? 'disabled' : '' }}>
                    <p class="reg_txt_error" id="reg_txt_error"></p>
                  </div>
                </div>

                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Action</label>
                    <select class="form-control form-input reg-input" name="action">
                      <option value="" disabled></option>
                      <option value="1" {{ $data->action == 1 ? 'selected' : '' }}>Enable</option>
                      <option value="0" {{ $data->action == 0 ? 'selected' : '' }}>Disable</option>
                    </select>
                    <p class="reg_txt_error" id="reg_txt_error"></p>
                  </div>
                </div>

                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Import Time</label>
                    <input type="text" class="form-control" name="import_time" value="{{ $data->import_time }}" readonly>
                  </div>
                </div>

                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Bound Time</label>
                    <input type="text" class="form-control" name="bound_time" value="{{ $data->bound_time }}" readonly>
                  </div>
                </div>

                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Status</label>
                    <input type="text" class="form-control" value="{{ $data->status == 1 ? 'Bound' : 'Store' }}" readonly>
                  </div>
                </div>

              </div>

              <div class="form-group row">
                  <div class="col">
                    <button type="submit" class="btn btn-warning">Save</button>
                    <a href="{{ url('en/vinno') }}" class="btn btn-secondary">Cancel</a>
                  </div>
                  @if($data->status != '1')
                  <div class="col text-right">
                    <button type="button" class="btn btn-danger text-right del">Delete</button>
                  </div>
                  @endif
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
            <form method="POST" id="delete_form" action="/en/vinno/{{ $data->id }}">
              @method('DELETE')
              @csrf
              <button type="submit" class="btn bg-gradient-primary">Delete</button>
            </form>
          </div>
        </div>
      </div>
    </div>

@endsection
