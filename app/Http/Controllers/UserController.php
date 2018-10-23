<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Validator;
use Auth;

class UserController extends Controller
{
    public function getSignup(){
        return view('user.signup');
    }
    public function postSignup(Request $request){
        $rules = [
            'email' => 'email|required|unique:users',
            'password' => 'required|min:4'
        ];

        $this->validate($request, $rules);

        $user = new User([
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);
        $user->save();

        return redirect()->route('products.index');
    }
    public function getSignin(){
        return view('user.signin');
    }
    public function postSignin(Request $request){
        $rules = [
            'email' => 'email|required',
            'password' => 'required|min:4',
            'g-recaptcha-response' => 'required|captcha',
        ];

        $this->validate($request, $rules);

        if(Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')]))
        {
            return redirect()->route('getprofile');
        }
        Session::flash('error', $validator->messages()->first());
        return redirect()->back()->withInput();

    }
    public function getProfile(){
        return view('user.profile');
    }
}
