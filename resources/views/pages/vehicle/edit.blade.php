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
            <form action="/en/vehicle/{{$data->id}}" method="POST">
              {{ csrf_field() }}
              {{ method_field('PUT') }}
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Brand</label>
                    <input type="text" class="form-control form-input reg-input" name="brand" autocomplete="off" value="{{ $data->brand }}" {{ ($data->cc != 0) ? 'disabled' : '' }}>
                    <p class="reg_txt_error" id="reg_txt_error"></p>
                  </div>
                </div>

                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Action</label>
                    <select class="form-control form-input reg-input" name="status">
                      <option value="" disabled></option>
                      <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>Enable</option>
                      <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>Disable</option>
                    </select>
                    <p class="reg_txt_error" id="reg_txt_error"></p>
                  </div>
                </div>

              </div>

              <div class="form-group row">
                <div class="col-sm-6">
                  <button type="submit" class="btn btn-warning">Save</button>
                  <a href="{{ url('en/vehicle') }}" class="btn btn-secondary">Cancel</a>
                </div>
              </div>
            </form>

            </div>

          </div>
        </div>
      </div>
    </div>
@endsection
