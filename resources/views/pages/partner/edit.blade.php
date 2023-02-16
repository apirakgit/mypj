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
                <h3 class="mb-0">Edit Partner</h3>
              </div>
            </div>
            <div class="" style="margin: 14px 20px 0px 50px;">
            <form action="/en/partner/{{$data->id}}" method="POST">
              {{ csrf_field() }}
              {{ method_field('PUT') }}
              <div class="row">

                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Partner ID</label>
                    <input type="text" class="form-control form-input reg-input" name="partner_id" value="{{ $data->partner_id }}" autocomplete="off" required readonly>
                    <p class="reg_txt_error" id="reg_txt_error"></p>
                  </div>
                </div>

                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Partner Name</label>
                    <input type="text" class="form-control form-input reg-input" name="partner_name" value="{{ $data->partner_name }}" autocomplete="off" required>
                    <p class="reg_txt_error" id="reg_txt_error"></p>
                  </div>
                </div>

                <div class="col-6">
                  <div class="form-group">
                    <label class="label-h">Secret Key</label>
                    <input type="text" class="form-control form-input reg-input" name="secret_key" id="secret_key" value="{{ $data->secret_key ?? '' }}" autocomplete="off" required readonly>
                  </div>
                </div>
                <div class="col-6" style="top: 34px;">
                  <div class="form-group">
                    <input type="button" class="btn btn-outline-warning" value="reset" onclick="generate_secret_key()">
                  </div>
                </div>

                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Status</label>
                    <select class="form-control form-input reg-input" name="status">
                      <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>Enable</option>
                      <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>Disable</option>
                    </select>
                    <p class="reg_txt_error" id="reg_txt_error"></p>
                  </div>
                </div>

              </div>

              <div class="form-group row">
                <div class="col-sm-12">
                  <button type="submit" class="btn btn-warning">Save</button>
                  <a href="{{ url('en/partner') }}" class="btn btn-secondary">Cancel</a>

                    <button style="float:right" type="button" class="btn btn-danger text-right del">Delete</button>

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
            <form method="POST" id="delete_form" action="/en/partner/{{ $data->id }}">
              @method('DELETE')
              @csrf
              <button type="submit" class="btn bg-gradient-primary">Delete</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript">
      function generate_secret_key(){
        var url = "{{ env('APP_URL_TOPUP') . '/' . app()->getLocale() .'/generate_secret_key'}}";
        $.ajax({
          method: "GET",
          url: url,
          data: { }
        })
        .done(function( data ) {
          $('#secret_key').val(data);
        });
      }
    </script>
@endsection
