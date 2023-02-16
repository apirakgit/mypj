<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('dashboard');
    }

    public function transaction_list()
    {
      $trans = DB::table('transactions')
        ->select('transactions.*','o.*','u.email AS admin_email',DB::raw('CASE WHEN o.status = 1 THEN "Approved" WHEN o.status = 9 THEN "Reject" ELSE "" END status'))
        ->join('orders AS o', 'o.order_id', '=', 'transactions.order_id')
        ->leftjoin('users AS u', 'u.id', '=', 'o.approve_by')
        ->orderByDesc('transactions.id')
        ->paginate(10);
      return view('pages.transaction', ['trans' => $trans]);
    }
}
