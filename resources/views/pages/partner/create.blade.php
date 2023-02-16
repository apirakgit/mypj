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
                <h3 class="mb-0">Create Partner</h3>
              </div>
            </div>
            <div class="" style="margin: 14px 20px 0px 50px;">
            <form action="/en/partner" method="POST">
              <input type="hidden" name="status" value="0">
              {{ csrf_field() }}
              <div class="row">

                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Partner ID</label>
                    <input type="text" class="form-control form-input reg-input" name="partner_id" value="{{ $partner_id ?? '' }}" autocomplete="off" required readonly>
                    <p class="reg_txt_error" id="reg_txt_error"></p>
                  </div>
                </div>

                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Partner Name</label>
                    <input type="text" class="form-control form-input reg-input" name="partner_name" autocomplete="off" required>
                    <p class="reg_txt_error" id="reg_txt_error"></p>
                  </div>
                </div>

                <div class="col-6">
                  <div class="form-group">
                    <label class="label-h">Secret Key</label>
                    <input type="text" class="form-control form-input reg-input" name="secret_key" id="secret_key" value="{{ $secret_key ?? '' }}" autocomplete="off" required readonly>
                  </div>
                </div>
                <div class="col-6" style="top: 34px;">
                  <div class="form-group">
                    <input type="button" class="btn btn-outline-warning" value="reset" onclick="generate_secret_key()">
                  </div>
                </div>

                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Action</label>
                    <select class="form-control form-input reg-input" name="status">
                      <option value="1">Enable</option>
                      <option value="0">Disable</option>
                    </select>
                    <p class="reg_txt_error" id="reg_txt_error"></p>
                  </div>
                </div>

              </div>

              <div class="form-group row">
                <div class="col-sm-6">
                  <button type="submit" class="btn btn-warning">Save</button>
                  <a href="{{ url('en/partner') }}" class="btn btn-secondary">Cancel</a>
                </div>
              </div>
            </form>

            </div>

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
