<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use DB,
    Auth;
use App\Mail\Welcome;

class ContactController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Register Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles the registration of new users as well as their
      | validation and creation. By default this controller uses a trait to
      | provide this functionality without requiring any additional code.
      |
     */

use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    protected function reg(Request $data) {
        $validator = Validator::make($data->all(), [
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:nqcl_clients',
                    'phone' => 'required|string|min:9',
        ]);

        if ($validator->fails()) {
            return redirect('newc')
                            ->withErrors($validator)
                            ->withInput();
        } else {

            $user = User::create([
                        'user_id' => Auth::user()->user_id,
                        'name' => $data['name'],
                        'address' => Auth::user()->address,
                        'remember_token' => 'N/A',
                        'parent' => Auth::user()->id,
                        'phone' => $data['phone'],
                        'username' => $data['email'],
                        'email' => $data['email'],
                        'password' => md5('#*seCrEt!@-*%' . 123456), //replaced bcrypt with md5
            ]);

            return redirect('contact_persons');
        }



//        \Mail::to($user)->send(new \App\Mail\Welcome($user));
    }

}
