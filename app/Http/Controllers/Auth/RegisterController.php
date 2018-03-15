<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use DB;
use App\Mail\Welcome;

class RegisterController extends Controller {
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
    private $name='';
    private $email='';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        return Validator::make($data, [
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:nqcl_clients',
                    'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data) {
        // str_replace(array('(',')',',','-'), '_', $data['name'])
        //dd($data);
        $client_id = 'C' . rand(0, 100);
        $this->email =$data['email'];
        $this->name =$data['name'];
        $user = User::create([
                    'user_id' => $client_id,
                    'name' => $data['name'],
                    'address' => $data['address'],
                    'remember_token' => 'N/A',
                    'parent' => 0,
                    'phone' => $data['phone'],
                    'username' => $data['email'],
                    'email' => $data['email'],
                    'password' => md5('#*seCrEt!@-*%' . $data['password']), //replaced bcrypt with md5
        ]);



        DB::table('clients')->insert([
            'name' => strtoupper($data['name']),
            'email' => $data['email'],
            'alias' => strtoupper(str_replace(array('(', ')', ',', '-'), '_', $data['name'])),
            'address' => $data['address'],
            'client_type' => 'N.A',
            'comment' => 'No Comment',
            'clientid' => $client_id,
            'credit' => 0,
            'client_agent_id' => 0,
            'discount_percentage' => 0,
            'contact_person' => 'N/A',
            'contact_phone' => 111111
        ]);

        $maildata = array('name' => $data['name'], "body" => "Test mail");

        \Mail::send('emails.welcome',   $maildata , function($message) {
            $message->to($this->email, $this->name)
                    ->subject($this->name.', Welcome to your new NQCL LIMS account ');
            $message->from('info@nqcl.go.ke', 'Rebecca from NQCL');
        });




        return $user;
    }

    function sendMail() {

// If you are not using Composer
// require("path/to/sendgrid-php/sendgrid-php.php");
        $from = new SendGrid\Email("Example User", "test@example.com");
        $subject = "Sending with SendGrid is Fun";
        $to = new SendGrid\Email("Example User", "test@example.com");
        $content = new SendGrid\Content("text/plain", "and easy to do anywhere, even with PHP");
        $mail = new SendGrid\Mail($from, $subject, $to, $content);
        $apiKey = getenv('SENDGRID_API_KEY');
        $sg = new \SendGrid($apiKey);
        $response = $sg->client->mail()->send()->post($mail);
        echo $response->statusCode();
        print_r($response->headers());
        echo $response->body();
    }

}
