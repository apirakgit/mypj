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
                <h3 class="mb-0">Create Vehicle Brand</h3>
              </div>
            </div>
            <div class="" style="margin: 14px 20px 0px 50px;">
            <form action="/en/vehicle" method="POST">
              <input type="hidden" name="status" value="0">
              {{ csrf_field() }}
              <div class="row">
                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Brand</label>
                    <input type="text" class="form-control form-input reg-input" name="brand" autocomplete="off" required>
                    <p class="reg_txt_error" id="reg_txt_error"></p>
                  </div>
                </div>

                <div class="col-12">
                  <div class="form-group">
                    <label class="label-h">Action</label>
                    <select class="form-control form-input reg-input" name="status" required>
                      <option value=""disabled selected></option>
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
