@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')

      <div class="container-fluid">

        <div class="row" style="margin-top: 20px;margin-bottom: 30px;">
          <div class="col">
            <div class="card">
              <div class="card-header border-0">
                <div class="col-md-5">
                  <h3 class="mb-0">Search</h3>
                </div>
              </div>
              <form class="" id="frm_search" action="{{ route ('user_search','en')}}" method="get">

              <div class="row" style="margin-left: 40px;">
                <div class="form-group" style="margin-right: 10px;">
                    <label for="name" class="h6">NAME</label>
                    <input class="form-control" type="text" name="name" id="name" value="{{ Request::get('name', '') }}">
                </div>
                <div class="form-group" style="margin-right: 10px;">
                    <label for="email" class="h6">EMAIL</label>
                    <input class="form-control" type="text" name="email" id="email" value="{{ Request::get('email', '') }}">
                </div>
              </div>
              <div class="row" style="margin-left: 40px;">
                <div class="form-group" style="margin-right: 10px;">
                  <button type="submit" class="btn btn-warning">Search</button>
                </div>
                <div class="form-group" style="margin-right: 10px;">
                  <button type="button" class="btn btn-secondary reset">Clear</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <div class="card">
              <!-- Card header -->
              <div class="card-header border-0">
                  <div class="row align-items-center">
                      <div class="col">
                          <h3 class="mb-0">User Management</h3>
                      </div>
                      <div class="col text-right">
                          <a href="{{ route('register','en')}}" class="btn btn-sm btn-primary">Add user</a>
                      </div>
                  </div>
              </div>
              </form>
              <!-- Light table -->
              <div class="table-responsive">
                <table class="table table-striped" id="myTable2">
                  <thead class="thead-light">
                    <tr>
                      <th scope="col" class="sort" data-sort="tran_id"><b>NAME</b></th>
                      <th scope="col" class="sort" data-sort="tran_stamp"><b>EMAIL</b></th>
                      <th scope="col" class="sort" data-sort="tran_stamp"><b>CREATION DATE</b></th>
                      <th scope="col" class="sort" data-sort="tran_stamp"><b>ROLE</b></th>
                      <th scope="col" class="sort" data-sort="tran_stamp"><b>STATUS</b></th>
                      <th scope="col"></th>
                    </tr>
                  </thead>
                  <tbody class="list" id="myTable">
                    @foreach ($users as $key => $user)
                    <tr>
                      <td class="">
                        {{ $user->name }}
                      </td>
                      <td class="">
                        {{ $user->email }}
                      </td>
                      <td class="">
                        {{ $user->created_at }}
                      </td>
                      <td>{{ $user->role }}</td>
                      <td>{{ $user->status }}</td>
                      <td class="text-right">
		                    <a class="btn btn-sm btn-secondary"  href="{{ URL::to('en/user-edit/' . $user->id ) }}">Edit</a>
                        @if($user->status != 'Disable')
                        <!-- <a class="btn btn-sm btn-danger" onclick="return confirm('Confirm Disable Admin {{ $user->name }}')" href="{{ route('disuser',['en','id'=>$user->id]) }}">Disable</a> -->
                        @else
                        <!-- <button type="button" class="btn btn-sm btn-danger" disabled="">Disable</button> -->
                        @endif
                      </td>
                    </tr>
                    @endforeach

                  </tbody>
                </table>
              </div>
              <!-- Card footer -->
              <div class="card-footer py-4">
              </div>
            </div>
          </div>
        </div>
      </div>

      <script type="text/javascript">
           // Filter table
          $(document).ready(function(){
            $(".reset").click(function() {
                $(this).closest('form').find("input[type=text], textarea, select").val("");
            });
          });

      </script>

@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush
