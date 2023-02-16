@extends('layouts.app')

@section('content')
<section id="table-search">

  <section class="section-preview">
    <div class="container-fluid">
      <h2 class="section-heading mb-4">
        Transaction List
      </h2>
      <input class="form-control mb-4" id="tableSearch" type="text"
        placeholder="Type something to search list items">

      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Transaction ID</th>
            <th>Timestamp Transaction</th>
            <th>ชื่อ นามสกุล</th>
            <th>Email</th>
            <th>เบอร์โทร</th>
            <th>จำนวนเงิน</th>
            <th>Email Admin</th>
            <th>Timestamp Admin Process</th>
            <th>Status</th>
            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody id="myTable">
          @foreach ($trans as $key => $tran)
          <tr>
            <td>{{ $tran->transaction_ref }}</td>
            <td>{{ $tran->transaction_datetime }}</td>
            <td>{{ $tran->name }}</td>
            <td>{{ $tran->email }}</td>
            <td>{{ $tran->tel }}</td>
            <td>{{ number_format(Str::substr($tran->amount, 0 ,-2)) }} บาท</td>
            <td>{{ $tran->admin_email }}</td>
            <td>{{ $tran->approve_date }}</td>
            <td>{{ $tran->status }}</td>
            <!-- <td><input data-id="{{$tran->order_id}}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" {{ $tran->status ? 'checked' : '' }}></td> -->

            @if($tran->status != '')
            <td><button type="button" class="btn btn-primary" disabled>Approve</button></td>
            <td><button type="button" class="btn btn-danger" disabled>Reject</button></td>
            @else
            <td><a class="btn btn-primary" href="{{ route( 'changeStatus','en', [ 'order_id'=>$tran->order_id, 'status'=>'1' ] ) }}" onclick='return confirm("Confirm approve Transaction ID {{ $tran->transaction_ref }} ")'>Approve</a></td>
            <td><a class="btn btn-danger" href="{{ route( 'changeStatus','en', [ 'order_id'=>$tran->order_id, 'status'=>'9' ] ) }}" onclick='return confirm("Confirm Reject Transaction ID {{ $tran->transaction_ref }} ")'>Reject</a></td>
            @endif
        </tr>
          @endforeach

        </tbody>
      </table>
    </div>
  </section>
</section>
@endsection
