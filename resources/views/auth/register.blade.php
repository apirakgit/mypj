@extends('layouts.app', ['class' => 'bg-default'])

@section('content')
    @include('layouts.headers.guest')

    <div class="container mt--8 pb-5">
        <!-- Table -->
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card bg-white shadow border-0">
                  <div class="card-header bg-transparent">
                      <div class="text-muted text-center mt-2 mb-3"><h1 style="color:#676767">Register admin</h1></div>
                  </div>
                    <div class="card-body px-lg-5 py-lg-5">

                        <form role="form" method="POST" action="{{ route('register','en') }}">
                            @csrf
                            <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                <div class="input-group input-group-alternative mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-single-02"></i></span>
                                    </div>
                                    <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" type="text" name="name" value="{{ old('name') }}" required autofocus>
                                </div>
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                <div class="input-group input-group-alternative mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                    </div>
                                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email') }}" type="email" name="email" value="{{ old('email') }}" required>
                                </div>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary mt-4">{{ __('Create account') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="">
	  <div class="col-md-4">
		  <div class="modal fade" id="modal-default" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
			  <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
				  <div class="modal-content">
					  <div class="modal-header">
						  <!-- <h6 class="modal-title" id="modal-title-default">Type your modal title</h6> -->
						  <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							  <span aria-hidden="true">×</span>
						  </button> -->
					  </div>
					  <div class="modal-body">
						  <h2>คุณได้เพิ่มผู้ดูแลระบบเรียบร้อยแล้ว</h2>
					  </div>
					  <div class="modal-footer">
						  <a href="{{ route('users','en') }}"class="btn btn-primary text-white">OK</a>
						  <!-- <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">Close</button> -->
					  </div>

				  </div>
			  </div>
		  </div>
	  </div>
	</div>
<script>
  $(function(){
    let searchParams = new URLSearchParams(window.location.search);
    let param = searchParams.get('add')
    if(param == 'successful'){
      $('#modal-default').modal('show');
    }
  });
</script>
@endsection
