<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RequestModel;
use App\RequestDetails;
use Auth,
    DB;
use App\Quotation;
use App\Mail\QuotationRequest;
use Mail,Session;

class RequestController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $number = '';

    public function __construct() {
        $this->number = 'Q-' . date('y-m');
    }

    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function q_request() {

        //$temp = $this->number . "-" . str_pad(Quotation::all()->last()->id, 2, '0', STR_PAD_LEFT);
        $user = Auth::user()->id;
        // $return2 = DB::select("SELECT * FROM clients WHERE clientid='$user'");
        $dosage = DB::connection('mysql2')->select("SELECT * FROM dosage_form");
        $wet = DB::connection('mysql2')->select("SELECT * FROM tests WHERE department = '1'");
        $micro = DB::connection('mysql2')->select("SELECT * FROM tests WHERE department = '2'");
        $med = DB::connection('mysql2')->select("SELECT * FROM tests WHERE department = '3'");
        $pack = DB::connection('mysql2')->select("SELECT * FROM packaging");
        $meth = DB::connection('mysql2')->select("SELECT * FROM methods");
        $clients = DB::connection('mysql2')->select("SELECT * FROM clients ORDER BY name ASC");
        return view('quote')->with([
                    'title' => 'REQUEST A QUOTE',
                    'dosage' => $dosage,
                    'wet' => $wet,
                    'micro' => $micro,
                    'med' => $med,
                    'pack' => $pack,
                    'clients' => $clients,
                    'temp' => 'QUOTATION-TEMP',
                    'cperson' => Auth::user()->name,
                    'meth' => $meth
        ]);
    }

    function sendQuotationRequest(Request $r) {
        $data = $r->all();
        $user = Auth::user();
        $q = new Quotation;
       // $padded = str_pad(Quotation::all()->last()->id, 2, '0', STR_PAD_LEFT);
       // $id = $this->number . "-" . $padded;
        $q->client_number = $user->id;
        $q->client_email = $user->email;
        $q->client_name = $user->name;
        $q->sample_name = $r->sample_name;
        $q->no_of_batches = $r->batches;
        $q->quotation_date = date('Y-m-d');
        $q->active_ingredients = 'NULLL';
        $q->dosage_form = 0;
        $q->quotations_id = 'GENQUOTE';
        $q->quotation_no = 'GENQUOTE'; //'NDQ-' . Auth::id() . "-" . date('y-m') . "-Q-" . $padded;
        $q->quotation_entries = 0;
        $q->quotation_entries_done = 0;
        $q->amount = 0.00;
        $q->reporting_fee = 0;
        $q->admin_fee = 0;
        $q->discount = 0;
        $q->currency = $r->currency;
        $q->quotation_status = 0;
        $q->completion_status = 0;
        $q->quotation_print_status = 0;
        $q->signatory_title = 'NULL';
        $q->signatory_name = 'NULL';
        $q->qdetails = $r->tests_requested;
        $q->save();

        $name = Auth::user()->name;
        $maildata = array(
            'name' => $name,
            "quotation_id" => "QUOTEGEnT",
            "sample_name" => $r->sample_name,
            "currency" => $r->currency,
            "batches" => $r->batches,
            "tests_requested" => $r->tests_requested
        );

        \Mail::send('emails.quotation', $maildata, function($message) {
            $name = Auth::user()->name;
            $email = Auth::user()->email;
            $message->to('wndethi@gmail.com', 'NQCL LIMS CLIENT')
                    ->subject($name . ', Quotation Request');
            $message->from($email, $name);
        });
        Session::put('quote', 'Your Quotation request has been received, we shall get back to you shortly');
        return redirect('finance');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req) {
        $r = new RequestModel;
        $r->request_id = $req->request_id;
        $r->client_id = Auth::user()->id;
        $r->sample_qty = $req->sample_qty;
        $r->sample_quantity_bup = $req->sample_qty;
        $r->product_name = $req->product_name;
        $r->active_ing = $req->active_ing;
        $r->dosage_form = $req->dosage_form;
        $r->manufacturer_name = $req->manufacturer_name;
        $r->manufacturer_add = $req->manufacturer_add;
        $r->batch_no = $req->batch_no;
        $r->exp_date = $req->exp_date;
        $r->manufacture_date = $req->manufacture_date;
        $r->designator_name = Auth::user()->name;
        $r->designation = '1';
        $r->edit_notes = 'None';
        $r->designation_date = date('Y-m-d');
        $r->designation_date_1 = date('Y-m-d');
        $r->label_claim = $req->label_claim;
        $r->description = 'To be filled later';
        $r->packaging = $req->dosage_form;
        $r->presentation = $req->presentation;
        $r->country_of_origin = $req->country_of_origin;
        $r->c = 0;
        $r->oos = 0;
        $r->invoice_print_status = 0;
        $r->invoice_status = 0;
        $r->coa_done_date = date('Y-m-d');
        $r->coa_collection_status = 0;
        $r->component_status = 0;
        $r->product_lic_no = 0;
        $r->dateformat = 0;
        $r->clientsampleref = 0;
        $r->moa = 0;
        $r->crs = 0;
        $r->dsgntr = 0;
        $r->dsgntn = 0;
        $r->assign_withdrawal_date = date('Y-m-d');
        $r->assign_withdrawal_reason = 0;
        $r->reassigned_status = 0;
        $r->payment_status = 0;
        $r->oos_status = 0;
        $r->full_details_status = 0;
        $r->split_status = 0;
        $r->quotation_status = 0;
        $r->label_status = 0;
        $r->proforma_no_status = 0;
        $r->proforma_print_status = 0;
        $r->client_agent_id = 0;
        $r->proforma_no = 0;
        $r->compliance = 'None';
        $r->invoice_no = 0;
        $r->t = 1;
        $r->user_id = Auth::user()->user_id;
        $r->quotation = $req->quotation;
        $r->save();


        foreach ($req->tests as $t):
            $rd = new RequestDetails;
            $rd->request_id = $req->request_id;
            $rd->test_id = $t;
            $rd->limits = 'NULL';
            $rd->analyst_id = 0;
            $rd->save();
        endforeach;
        return redirect('samples');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $data = RequestModel::where('request_id', $id)->get();
        dd($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

}
