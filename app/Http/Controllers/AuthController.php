<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }
    public function register()
    {
        return view('register');
    }
    public function postLogin(Request $request)
    {
        $login=$request->validate([
            'username'=>'required',
            'password'=>'required',
        ]);
        if (Auth::attempt($login))
        {
            Session::put('user_id', auth()->user()->id);
            Session::put('name', auth()->user()->name);
            return redirect()->intended('/gallery');
        }
        return back()->withErrors([
            'errors'=>'Username Password Salah'
        ]);
    }
    public function postRegister(Request $request)
    {
        $register=$request->validate([
            'name'=>'required',
            'username'=>'required',
            'email'=>'required',
            'password'=>'required',
            'repassword'=>'required|same:password',
            'terms'=>'required',
        ]);
        if ($request->password==$request->repassword)
        {
            $ins=User::create([
                'name'=>$request->name,
                'username'=>$request->username,
                'email'=>$request->email,
                'password'=>bcrypt($request->password),
            ]);
            $login=$request->validate([
                'username'=>'required',
                'password'=>'required',
            ]);
            if (Auth::attempt($login))
            {
                Session::put('user_id', auth()->user()->id);
                Session::put('name', auth()->user()->name);
                return redirect()->intended('/gallery');
            }
        }
        return redirect('/');
    }
    public function logout()
    {
        Auth::logout();
        Session::forget('user_id');
        Session::forget('name');
        return redirect('/');
    }
}
