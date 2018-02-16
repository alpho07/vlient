<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use App\User;
use Illuminate\Http\Request;

class LoginController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles authenticating users for the application and
      | redirecting them to your home screen. The controller uses a trait
      | to conveniently provide its functionality to your applications.
      |
     */

use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
   // protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest')->except('logout');
    }

    function getLogin(Request $r) {
        $user = User::whereEmail($r->email)
                ->wherePassword(md5('#*seCrEt!@-*%'.$r->password))
                ->first();    

        if ($user) {
            Auth::login($user);
            return redirect('home');
        }else{
             $errors = ['message' => trans('auth.failed')];
             return redirect('login')->with($errors);
        }

        return $user;
    }

}
