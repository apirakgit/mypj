@extends('layouts.app', ['class' => 'bg-default'])

@section('content')
    @include('layouts.headers.guest')

    <div class="container mt--8 pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card bg-secondary shadow border-0">
                    <div class="card-body px-lg-5 py-lg-5">
                        <div class="text-center text-muted mb-4">
                            <small>{{ __('Reset Password') }}</small>
                        </div>
                        <form role="form" method="POST" action="{{ route('password.update','en') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ Request::segment(4) }}">

                            <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }} mb-3">
                                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email') }}" type="email" name="email" value="{{ $email ?? old('email') }}" required autofocus>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">

                                    <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{ __('Password') }}" type="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                    <input class="form-control" placeholder="{{ __('Confirm Password') }}" type="password" name="password_confirmation" required>

                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary my-4">{{ __('Reset Password') }}</button>
                            </div>
                            @if($errors->first('email') == 'Token ของคุณหมดอายุ กรุณาทำรายการใหม่อีกครั้ง')
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request','en') }}" style="color:#676767">
                                    <small style="display: block; margin-top: 20px;">
                                      {{ __('Back to Send Password Reset Link') }}
                                    </small>
                                </a>
                            @endif
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
