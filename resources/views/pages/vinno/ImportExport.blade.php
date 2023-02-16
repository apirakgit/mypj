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
          @if (session('dup'))
              <div class="alert alert-danger">
                <h1>Vin no ซ้ำ</h1>
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
          <div class="card">
            <div class="card-header border-0">
              <div class="col-md-5">
                <h3 class="mb-0">Vin no master import</h3>
              </div>
            </div>
            <div class="" style="margin: 14px 20px 0px 50px;">

            <form action="{{ url('en/file-import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group mb-4" style="max-width: 500px; margin: 0 auto;">
                  <label class="label-h">Brand</label>
                  <select class="form-control form-input reg-input" name="brand_id" required>
                    <option value="" selected disabled></option>
                    @foreach($brand as $value)
                    <option value="{{ $value->id }}" >{{ $value->brand }}</option>
                    @endforeach
                  </select>
                  <p class="reg_txt_error" id="reg_txt_error"></p>
                </div>

                <div class="form-group mb-4" style="max-width: 500px; margin: 0 auto;">
                    <div class="custom-file text-left">
                        <input type="file" name="file" class="custom-file-input" id="customFile" required>
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                </div>

                <div class="form-group row">
                  <div class="col-sm-6">
                    <button type="submit" class="btn btn-primary">Import data</button>
                    <a href="{{ url('en/vinno') }}" class="btn btn-secondary">Cancel</a>
                  </div>
                </div>

            </form>
            <script>
              $('#customFile').on('change',function(){
                  //get the file name
                  var fileName = $(this).val();
                  //replace the "Choose a file" label
                  $(this).next('.custom-file-label').html(fileName);
              })
            </script>
            </div>

          </div>
        </div>
      </div>
    </div>
@endsection
