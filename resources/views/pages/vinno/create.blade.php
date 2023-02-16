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
                <h3 class="mb-0">Create Vin No</h3>
              </div>
            </div>
            <div class="" style="margin: 14px 20px 0px 50px;">
            <form action="/en/vinno" method="POST">
              <input type="hidden" name="status" value="0">
              {{ csrf_field() }}
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Brand</label>
                    <select class="form-control form-input reg-input" name="brand_id" required>
                      <option value="" selected disabled></option>
                      @foreach($brand as $value)
                      <option value="{{ $value->id }}" >{{ $value->brand }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Vin No</label>
                    <input type="text" class="form-control form-input reg-input" name="vin_no" minlength="17" maxlength="17" autocomplete="off" required>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Action</label>
                    <select class="form-control form-input reg-input" name="action">
                      <option value="" disabled></option>
                      <option value="1">Enable</option>
                      <option value="0">Disable</option>
                    </select>
                    <p class="reg_txt_error" id="reg_txt_error"></p>
                  </div>
                </div>

                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Import Time</label>
                    <input type="text" class="form-control" name="import_time" value="{{ date('Y-m-d H:i:s')}}" readonly>
                  </div>
                </div>

              </div>

              <div class="form-group row">
                <div class="col-sm-6">
                  <button type="submit" class="btn btn-warning">Save</button>
                  <a href="{{ url('en/vinno') }}" class="btn btn-secondary">Cancel</a>
                </div>
              </div>
            </form>

            </div>

          </div>
        </div>
      </div>
    </div>
@endsection
