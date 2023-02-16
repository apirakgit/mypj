@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')

      <div class="container-fluid">
        <!-- <div class="row" style="margin-top: 20px;margin-bottom: 30px;">
          <div class="col">
            <div class="card">

            </div>
          </div>
        </div> -->

        <div class="row" style="padding-top: 30px;">
          <div class="col-12">
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
                  <h3 class="mb-0">Edit user</h3>
                </div>
              </div>

              <div class="" style="margin: 14px 20px 0px 50px;">

                <?php
                //var_dump($user);
                 ?>
              <form action="{{ Route('update','en') }}" method="POST" onsubmit="return validate_form()">
                @csrf
                <input type="hidden" name="id" value="{{$user->id}}">
                <div class="form-group row">
                  <label for="staticEmail" class="col-sm-2 col-form-label">Create date</label>
                  <div class="col-sm-10">
                    <input type="text" readonly class="form-control-plaintext" id="staticEmail" value="{{ $user->created_at }}">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="name" class="col-sm-2 col-form-label">Name</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
                    <p class="txt_error text-red text-right" id="txt_error_name"></p>
                  </div>

                </div>
                <fieldset disabled>
                <div class="form-group row">
                  <label for="inputPassword3" class="col-sm-2 col-form-label">Email</label>
                  <div class="col-sm-6">
                    <input type="text" id="disabledTextInput" class="form-control" value="{{ $user->email }}">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputPassword3" class="col-sm-2 col-form-label">Role</label>
                  <div class="col-sm-6">
                    <input type="text" id="disabledTextInput" class="form-control" value="{{ $user->role }}">
                  </div>
                </div>
                </fieldset>
                <fieldset class="form-group">
                  <div class="row">
                    <legend class="col-form-label col-sm-2 pt-0">Status</legend>
                    <div class="col-sm-6">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="gridRadios1" value="1"
                        {{ ( $user->status == '1' ) ? 'checked' : '' }} >
                        <label class="form-check-label" for="gridRadios1">
                          Enable
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="gridRadios2" value="0"
                        {{ ( $user->status == '0' ) ? 'checked' : '' }} >
                        <label class="form-check-label" for="gridRadios2">
                          Disable
                        </label>
                      </div>
                    </div>
                  </div>
                </fieldset>
                <div class="form-group row">
                  <div class="col-sm-6">
                    <button type="submit" class="btn btn-warning">Save</button>
                    <a href="{{ route('users','en')}}" class="btn btn-secondary">Cancel</a>
                  </div>
                </div>
              </form>

              </div>

            </div>
          </div>
        </div>
      </div>
      <script>
      function validate_form(){
        var name = $('#name').val();
        var regName = /^[a-zA-Zก-๏\s]+$/;
        if(!regName.test(name) || name.length < 3){
          //alert('กรุณากรอกข้อมูลให้ถูกต้อง');
          $('#txt_error_name').html('กรุณากรอกข้อมูลให้ถูกต้อง');
          $('#name').focus();
          return false;
        }else{
          return confirm('ยืนยันการแก้ไขข้อมูล');
        }
      }
      </script>
@endsection
