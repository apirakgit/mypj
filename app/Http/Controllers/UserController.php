<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\User  $model
     * @return \Illuminate\View\View
     */

    public function index(User $model)
    {
        $role = Auth::user()->role;
        if($role != 'manager'){
          return redirect('/admin');
        }

        $query = DB::table('users')->select('users.id','users.name','users.email','users.role','users.created_at',
          DB::raw('CASE WHEN users.status = 1 THEN "Enable" WHEN users.status = 0 THEN "Disable" ELSE "" END status')
        );

        $users = $query->get();

        return view('pages.users')->with('users', $users);
    }

    public function search(Request $request)
    {
      $role = Auth::user()->role;
      if($role != 'manager'){
        return redirect('/admin');
      }

      $query = DB::table('users')->select('users.id','users.name','users.email','users.role','users.created_at',
        DB::raw('CASE WHEN users.status = 1 THEN "Enable" WHEN users.status = 0 THEN "Disable" ELSE "" END status')
      );

      if($request->name != ''){
        $query->where('users.name','like','%'.$request->name.'%');
      }

      if($request->email != ''){
        $query->where('users.email','like','%'.$request->email.'%');
      }

      $users = $query->get();

      return view('pages.users')->with('users', $users);
    }

    public function disable_user(Request $request)
    {
        $role = Auth::user()->role;
        if($role != 'manager'){
          return redirect('/admin');
        }

        $user = User::find($request->id);
        $user->status = 0;
        $user->save();

        return redirect()->back();
    }

    public function edit(Request $request){

      $role = Auth::user()->role;
      if($role != 'manager'){
        return redirect('/admin');
      }
      $id = $request->route()->parameter('id');

      $user = User::find($id);
      return view('pages.user_edit')->with('user',$user);
    }

    public function update(Request $request){

      $role = Auth::user()->role;
      if($role != 'manager'){
        return redirect('/admin');
      }
      $request->validate([
          'id' => 'required',
          'name' => 'required',
          'status' => 'required',
      ]);

      $user = User::find($request->id);
      $user->name = $request->name;
      $user->status = $request->status;
      $user->save();

      return redirect()->route('users','en');
    }

}
