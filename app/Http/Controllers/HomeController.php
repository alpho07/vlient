<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Auth,
    Session;
use Illuminate\Support\Collection;
use App\RequestModel;
use App\Quotation;
use App\Quotations;
use App\Quotations_final;


class HomeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $r) {
        $id = Auth::user()->id;
        $uid = Auth::user()->user_id;
        $cperson = \App\User::where('parent', '!=', '0')->where('user_id', $uid)->get();
        if (count($cperson) <= 0) {
            $message = 'Welcome ' . Auth::user()->name . ', Before you proceed we require that you add a contact person who can login and submit samples on behalf of the company';
            $style = "class=DISABLED";
            return view('default')->with(['title' => 'WELCOME NOTE', 'message' => $message, 'style' => $style]);
        } else {
            $Completed = RequestModel::whereUser_id($id)->where("CAN", "!=", "-")->get();
            $pending = RequestModel::whereUser_id($id)->where("CAN", "-")->get();
            $r->session()->put('completed', count($Completed));
            $r->session()->put('pending', count($pending));
            $style = 'class=nothing';
            return view('home')->with(['title' => 'CLIENT DASHBOARD', 'style' => $style]);
        }
    }

    function tracker() {
        return view('tracker')->with(['title' => 'SAMPLE TRACKING']);
    }

    function finance() {
        $id = Auth::user()->id;
        $quotation = Quotations_final::whereclient_id($id)->with('quotations')->get();
        return view('finance')->with(['title' => 'FINANCE DASHBOARD', 'quotations' => $quotation]);
    }

    function samples() {

        $id = Auth::user()->user_id;
        $ALL = RequestModel::whereUser_id($id)->get();
        $Completed = RequestModel::whereUser_id($id)->where("CAN", "!=", "-")->get();
        $pending = RequestModel::whereUser_id($id)->where("CAN", "-")->get();
        // dd($Completed);
        return view('samples')->with([
                    'title' => 'ANALYSIS REQUESTS',
                    'all' => $ALL,
                    'Completed' => $Completed,
                    'Pending' => $pending
        ]);
    }

    function request() {
        $temp = 'NDQTEMP' . date('YmdHis');
        $user = Auth::user()->user_id;
        $return2 = DB::select("SELECT * FROM nqcl_clients WHERE parent='0' AND user_id='$user'");
        $dosage = DB::connection('mysql2')->select("SELECT * FROM dosage_form");
        $wet = DB::connection('mysql2')->select("SELECT * FROM tests WHERE department = '1'");
        $micro = DB::connection('mysql2')->select("SELECT * FROM tests WHERE department = '2'");
        $med = DB::connection('mysql2')->select("SELECT * FROM tests WHERE department = '3'");
        $pack = DB::connection('mysql2')->select("SELECT * FROM packaging");
        $meth = DB::connection('mysql2')->select("SELECT * FROM methods");
        $clients = DB::connection('mysql2')->select("SELECT * FROM clients ORDER BY name ASC");
        return view('new')->with([
                    'title' => 'NEW ANALYSIS REQUESTS',
                    'dosage' => $dosage,
                    'wet' => $wet,
                    'micro' => $micro,
                    'med' => $med,
                    'pack' => $pack,
                    'clients' => $clients,
                    'temp' => $temp,
                    'cperson' => $return2,
                    'meth' => $meth
        ]);
    }

    function profile() {
        $user = Auth::user()->user_id;
        $return = DB::select("SELECT * FROM nqcl_clients WHERE user_id='$user'");
        $return2 = DB::select("SELECT * FROM clients WHERE clientid='$user'");
        return view('profile')->with(['title' => 'PROFILE', 'client' => $return, 'cperson' => $return2]);
    }

    function cperson() {
        $user = Auth::user()->user_id;
        $return = DB::select("SELECT * FROM nqcl_clients WHERE user_id='$user' AND parent != 0");
        return view('cperson')->with(['title' => 'CONTACT PERSONS', 'cperson' => $return]);
    }

    function newc() {
        $user = Auth::user()->user_id;
        $uid = Auth::user()->user_id;
        $return = DB::select("SELECT * FROM nqcl_clients WHERE user_id='$user'");
        $return2 = DB::select("SELECT * FROM clients WHERE clientid='$user'");
        $cperson = \App\User::where('parent', '!=', '0')->where('user_id', $uid)->get();
        if (count($cperson) <= 0) {
            $style = "class=DISABLED";
        } else {
            $style = "class='nothing'";
        }
        return view('newc')->with(['title' => 'CONTACT PERSONS', 'client' => $return, 'cperson' => $return2, 'style' => $style]);
    }

    function search(Request $r) {
        $keyword = $r->keyword;
        $user = Auth::user()->user_id;

        $caveats = DB::select(DB::raw("SELECT * FROM request WHERE ( (product_name LIKE '$keyword%') OR (request_id LIKE '$keyword%') OR (batch_no LIKE '$keyword%') ) AND user_id='$user' "));
        //OR  County LIKE '%$search%' OR Description LIKE '%$search%' OR Area LIKE '%$search%' OR Landmark LIKE '%$search%' OR Road LIKE '%$search%'
        //Get current page form url e.g. &page=6
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        //Create a new Laravel collection from the array data
        $collection = new Collection($caveats);

        //Define how many items we want to be visible in each page
        $perPage = 5;

        //Slice the collection to get the items to display in current page
        // $currentPageSearchResults = $collection->slice($currentPage * $perPage, $perPage)->all();
        $currentPageSearchResults = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();

        //Create our paginator and pass it to the view
        $paginatedSearchResults = new LengthAwarePaginator($currentPageSearchResults, count($collection), $perPage);

        $paginatedSearchResults->setPath($r->url());
        $paginatedSearchResults->appends($r->except(['_token']));
        return view('sresult')->with([
                    'keyword' => $keyword,
                    'title' => 'Sample Tracking',
                    'caveats' => $paginatedSearchResults,
                    'total' => $caveats
        ]);
    }

    protected function update(Request $data) {



        Auth::User()->update([
            'name' => strtoupper($data['fname']),
            'phone' => $data['phone'],
            'email' => $data['email'],
            'address' => $data['address'],
        ]);



        return redirect()->back();
    }

    protected function updatec(Request $data) {



        Auth::User()->update([
            'name' => strtoupper($data['fname']),
            'phone' => $data['phone'],
            'email' => $data['email'],
            'address' => Auth::user()->address
        ]);

        return redirect()->back();
    }
    
    function changepassword(){
        return view('changepassword');
    }

    protected function sendPasswordEmail() {
        $name = Auth::user()->name;
        $maildata = array('name' => $name, "body" => "");

        \Mail::send('emails.changepassword', $maildata, function($message) {
            $name = Auth::user()->name;
            $email = Auth::user()->email;
            $message->to($email, $name)
                    ->subject($name. ', Change of password Notification');
            $message->from('info@nqcl.go.ke', 'Rebecca from NQCL');
        });
        
         Session::put('psuccess', 'An email has been sent to your email address with password change link');
        return redirect()->back();
        
    }

    protected function updatePassword(Request $data) {

        $this->validate($data, [
            'password' => 'required|string|min:6|confirmed',
        ]);
        $k = Auth::User()->update([
            'password' => md5('#*seCrEt!@-*%' . $data['password'])
        ]);
        if ($k) {
            Session::put('psuccess', 'Password Updated Successfully');
        } else {
            Session::put('error', 'An error occured. Password not updated');
        }

        return redirect('profile');
    }

    protected function updatePasswordc(Request $data) {

        $this->validate($data, [
            'password' => 'required|string|min:6|confirmed',
        ]);
        $k = Auth::User()->update([
            'password' => md5('#*seCrEt!@-*%' . $data['password'])
        ]);
        if ($k) {
            Session::put('psuccess', 'Password Updated Successfully');
        } else {
            Session::put('error', 'An error occured. Password not updated');
        }

        return redirect()->back();
    }

}
