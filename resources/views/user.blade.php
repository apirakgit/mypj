@extends('layouts.app')

@section('content')

<div class="container">

            <!-- <div class="card">
                <div class="card-header">{{ __('Search') }}</div>

                <div class="card-body">
                </div>
            </div> -->
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif



            <br>
            <h1>User</h1>
            <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <tr>
                    <th>Email</th>
                    <th>Permission</th>
                    <th></th>
                    <th></th>
                </tr>
                @foreach($users as $user)
                <tr>
                  <td>{{ $user->email }}</td>
                  <td></td>
                  <td><a class="btn btn-primary">Approve</a></td>
                  <td><a class="btn btn-danger">Delete</a></td>
                </tr>
                @endforeach
            </table>
          </div>
</div>
@endsection
