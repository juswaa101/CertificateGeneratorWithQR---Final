<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function index()
    {
        return view('admin.forgot_password');
    }

    public function password(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->get('email'))->get();
        if($validate->fails())
        {
            return redirect()->back()->withErrors($validate)->withInput();
        }
        else{
            if($user->count() > 0)
            {
                $mail_data = [
                    'fromEmail' => "developers.laravel@example.com",
                    'subject' => "Reset Password",
                    'body' => 'Here is the link for your password reset!',
                    'url' => 'http://127.0.0.1:8000/admin/forgot_password/reset/form',
                ];
                if($this->isOnline()){
                    Mail::to($request->get('email'))->send(new ResetPassword($mail_data));
                    return redirect()->back()->with('success', 'Reset password link was sent to your email!');
                }
                else{
                    return redirect()->back()->with('internet_error', 'Unable to send message, please check your internet connection');
                }
            }      
            else
            {
                return redirect()->back()->with('error', 'Please provide admin email that is provided!');
            } 
        }
    }
    public function isOnline($site = "https://youtube.com")
    {
        if(@fopen($site,"r")){
            return true;
        }else{
            return false;
        }
    }

    public function resetPass()
    {
        return view('admin.reset_password_form');
    }

    public function resetPassword(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'new_password' => 'required|min:6',
            'new_confirm_password' => 'required|min:6|same:new_password',
        ]);

        if($validate->fails()){
            return redirect()->back()->withErrors($validate)->withInput();
        }else
        {
            $user = User::where('id', 1)->first();
            $user->password = Hash::make($request->get('new_confirm_password'));
            $user->save();
            return redirect()->back()->with('success', 'Reset password success!');
        }
    }
}
