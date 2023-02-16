@extends('layouts.app', ['class' => 'bg-default'])

@section('content')
    @include('layouts.headers.guest')

    <div class="container mt--8 pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card bg-white shadow border-0" style="box-shadow: 0px 10px 30px 8px rgba(170,89,0,0.1)">
                  <div class="card-header bg-transparent">
                      <div class="text-muted text-center mt-2 mb-3"><h1 style="color:#676767">Sign in</h1></div>
                  </div>
                    <div class="card-body px-lg-5 py-lg-4">
                        <form role="form" method="POST" action="{{ route('login','en') }}">
                            @csrf
                            @error('msg')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }} mb-3">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                    </div>
                                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email') }}" type="email" name="email" value="{{ old('email') }}" value="admin@argon.com" required autofocus>
                                </div>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                    </div>
                                    <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{ __('Password') }}" type="password" value="" required>
                                </div>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" style="display: block;" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-warning my-4">{{ __('Sign in') }}</button>
                            </div>
                        </form>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request','en') }}" style="color:#676767">
                                <small style="display: block; margin-top: 20px;">
                                  {{ __('Forgot password?') }}
                                </small>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-6">

																							  
																		   
								
							  
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
