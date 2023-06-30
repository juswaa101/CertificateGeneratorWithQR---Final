<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }

    public function checkLogin(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required|min:6',
        ]);

        $user_data = array(
            'username' =>  $request->get('username'),
            'password'  =>  $request->get('password'),
        );

        if (Auth::attempt($user_data)) {
            return redirect('/admin/dashboard');
        } else {
            return redirect()->back()->with('error', 'Invalid Username or Password');
        }
    }

    public function successLogin()
    {
        $data = array(
            'list' => DB::table('training')->get()
        );
        return view('admin.dashboard', $data);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
