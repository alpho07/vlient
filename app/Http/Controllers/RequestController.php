<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RequestModel;
use App\RequestDetails;
use Auth,
    DB;
use App\Quotation,
    App\User;
use App\Mail\QuotationRequest;
use Mail,
    Session;
use App\Quotations;
use App\Q_request_details;
use App\Quotations_final;
use App\Quotation_notes;
use App\Quotations_components;
use App\Test_methods;

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

       // $temp = $this->number . "-" . str_pad(Quotation::all()->last()->id, 2, '0', STR_PAD_LEFT);
        $temp = $this->number . "-" . str_pad(date('His'), 2, '0', STR_PAD_LEFT).'-'.Auth::user()->name;
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
                    'temp' => $temp,
                    'cperson' => Auth::user()->name,
                    'meth' => $meth
        ]);
    }
    
    public function q_request_edit($quotation_no) {

        //Get general quotation details
        $quotation = Quotations::where('quotation_no',$quotation_no)->take(1)->get();
        $quotations_id = $quotation[0]['quotations_id'];

        //Get tests
        $req_details = Q_request_details::where('quotations_id', $quotations_id)->with('tests')->get();

        //Initialize array to hold test ids
        $test_ids = array();
       
       // $temp = $this->number . "-" . str_pad(Quotation::all()->last()->id, 2, '0', STR_PAD_LEFT);
        $temp = $quotation_no;
        $user = Auth::user()->id;
        // $return2 = DB::select("SELECT * FROM clients WHERE clientid='$user'");
        $dosage = DB::connection('mysql2')->select("SELECT * FROM dosage_form");
        $wet = DB::connection('mysql2')->select("SELECT * FROM tests WHERE department = '1'");
        $micro = DB::connection('mysql2')->select("SELECT * FROM tests WHERE department = '2'");
        $med = DB::connection('mysql2')->select("SELECT * FROM tests WHERE department = '3'");
        $pack = DB::connection('mysql2')->select("SELECT * FROM packaging");
        $meth = DB::connection('mysql2')->select("SELECT * FROM methods");
        $clients = DB::connection('mysql2')->select("SELECT * FROM clients ORDER BY name ASC");
        return view('quote_edit')->with([
                    'title' => 'EDIT QUOTE',
                    'dosage' => $dosage,
                    'wet' => $wet,
                    'micro' => $micro,
                    'med' => $med,
                    'pack' => $pack,
                    'clients' => $clients,
                    'temp' => $temp,
                    'cperson' => Auth::user()->name,
                    'meth' => $meth,
                    'quotation' => $quotation,
                    'tests' => $req_details,
                    'test_ids' => $test_ids
        ]);
    }


    function sendQuotationRequest(Request $r) {
        $data = $r->all();
        $user = Auth::user();

        //Get batches, tests, client id, quotation no, email, currency, source status, sample name
        $no_of_batches = $r->batches;
        $tests = $r->tests;
        $client_no = $user->id;
        $q_no = $r->quotation_no;
        $email = $user->email;
        $currency = $r->currency;
        $currency_small = strtolower($currency);
        $source_status = 1;
        $sample_name = $r->sample_name;

        //Get id for individual entry
        $quotation_id = getQuotationId();

        //Get id for overall quotation
        $quotation_no = getQuotationNo($client_no, $q_no); 

        //Save to individual quotations table
        for($b=1;$b<=$no_of_batches;$b++){

            $q = new Quotations;
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
            $q->quotation_id = $quotation_id;
            $q->quotations_id = $quotation_id.'-'.$b;
            $q->quotation_no = $quotation_no;
            $q->batch_id = $b;
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
            $q->quotation_status = 0;
            $q->save();


        //Save tests in quotations tests table
        for($i=0;$i<count($tests);$i++){
            $request = new Q_request_details;
            $request -> test_id = $tests[$i];
            $request -> client_number = $client_no;
            $request -> client_email = $email;
            $request -> quotation_id = $quotation_id;
            $request -> quotations_id = $quotation_id.'-'.$b;
            $request -> save();
        
            //Add default component
            $method_details = Test_methods::where('test_id', $tests[$i])->get();
            var_dump($method_details[0]['id']);

            //Add to quotations components table
            $qc = new Quotations_components();
            $qc->component = $sample_name;
            $qc->quotation_id = $quotation_id;
            $qc->quotations_id = $quotation_id.'-'.$b;
            $qc->test_id = $tests[$i];
            $qc->method_id = $method_details[0]['id'];
            $qc->method_charge = $method_details[0]['charge_'.$currency_small];
            $qc->save();

        }



    }


            //Save to main quotation table
            $q_f = Quotations_final::firstOrNew(['quotation_no' => $quotation_no], ['quotation_entries'=>1, 'client_id'=>$client_no, 'currency'=>$currency, 'quotation_status'=>0, 'source_status' => $source_status]);
            $q_f -> save();

            //Initialize row at Quotation NOtes
            $q_n = new Quotation_notes;
            $q_n -> quotation_no = $quotation_no;
            $q_n -> save();


        $name = Auth::user()->name;
        $maildata = array(
            'name' => $name,
            "quotation_id" => $quotation_no,
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
    
    
     //Edit 
    function updateQuote(Request $r){

        //Update quotations final table
        $data = $r->all();
        $user = Auth::user();

        //Get batches, tests, client id, quotation no, email, currency, source status, sample name
        $no_of_batches = $r->batches;
        $db_no_of_batches = $r->db_batches;

        $tests = $r->tests;
        $client_no = $user->id;
        $q_no = $r->quotation_no;
        $email = $user->email;
        $currency = $r->currency;
        $currency_small = strtolower($currency);
        $source_status = 1;
        $sample_name = $r->sample_name;
        $client_message = $r->client_message;

        //Get quotation id
        $quotation_details = Quotations::select('quotation_id')->where('quotation_no', $q_no)->take(1)->get();
        $quotation_id = $quotation_details[0]['quotation_id'];

        //Get difference in no of batches
        $batch_diff = $no_of_batches - $db_no_of_batches;

        //Quotations_final update array
        $qf_update_array = array('currency'=>$currency);

        //Quotation update array
        $qu_update_array = array('sample_name'=>$sample_name, 'no_of_batches'=>$no_of_batches, 'currency'=>$currency);

        //Quotation notes update array
        $qn_update_array = array('client_note'=>$client_message);
            
        //Update quotation final, quotations
        Quotations_final::where('quotation_no', $q_no)->update($qf_update_array);
        Quotation_notes::where('quotation_no', $q_no)->update($qn_update_array);

        //Delete existing tests and components, quotations
        Q_request_details::where('quotation_id', $quotation_id)->delete();
        Quotations_components::where('quotation_id', $quotation_id)->delete();
        Quotations::where('quotation_id', $quotation_id)->delete();

        //Loop through batches
        for($i=1;$i<=$no_of_batches;$i++){

            $q = new Quotations;
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
            $q->quotation_id = $quotation_id;
            $q->quotations_id = $quotation_id.'-'.$i;
            $q->quotation_no = $q_no;
            $q->batch_id = $i;
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
            $q->qdetails = '';
            $q->quotation_status = 0;
            $q->save();        

            //Create new tests, updated component associations
            for($b=0;$b<count($tests);$b++){

                $request = new Q_request_details;
                $request -> test_id = $tests[$b];
                $request -> client_number = $client_no;
                $request -> client_email = $email;
                $request -> quotation_id = $quotation_id;
                $request -> quotations_id = $quotation_id.'-'.$i;
                $request -> save();
            
                //Add default component
                $method_details = Test_methods::where('test_id', $tests[$b])->get();
                var_dump($method_details[0]->id);

                //Add to quotations components table
                $qc = new Quotations_components();
                $qc->component = $sample_name;
                $qc->quotation_id = $quotation_id;
                $qc->quotations_id = $quotation_id.'-'.$i;
                $qc->test_id = $tests[$b];
                $qc->method_id = $method_details[0]->id;
                $qc->method_charge = $method_details[0]->{'charge_'.$currency_small};
                $qc->save();

            }

    }
        
        //Send Email to notify NQCL about the change
        $name = Auth::user()->name;
        $maildata = array(
            'name' => $name,
            "quotation_id" => $q_no,
            "sample_name" => $r->sample_name,
            "currency" => $r->currency,
            "batches" => $r->batches,
            "tests_requested" => $r->client_message
        );

        \Mail::send('emails.quotation', $maildata, function($message) {
            $name = Auth::user()->name;
            $email = Auth::user()->email;
            $message->to('wndethi@gmail.com', 'NQCL LIMS CLIENT')
                    ->subject($name . ', Quotation Edited.');
            $message->from($email, $name);
        });
        

        Session::put('quote', 'Your Quotation request has been edited successfully.');
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
        $r->moa = $req->method;
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
        $r->requester = Auth::user()->email;
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
        $ids = '';
        $tests = '';
        $cid = Auth::user()->user_id;
        $data = RequestModel::where('request_id', $id)->get();
        $client = User::where('user_id', $cid)->where('parent', '0')->get();
        $selected_dosage = DB::table('dosage_form')->where("id", $data[0]->dosage_form)->get();
        $request_details = RequestDetails::where('request_id', $data[0]->request_id)->get();
        foreach ($request_details as $rd):
            $ids .= $rd->test_id . ',';
        endforeach;
        $ids = rtrim($ids, ",");
        $tresults = DB::select(DB::raw("SELECT name FROM tests WHERE id IN ($ids)"));
        foreach ($tresults as $t):
            $tests .= $t->name . ',';
        endforeach;
        $tests = rtrim($tests, ",");
        // dd($tests);
        $dosage = DB::connection('mysql2')->select("SELECT * FROM dosage_form");
        $wet = DB::connection('mysql2')->select("SELECT * FROM tests WHERE department = '1'");
        $micro = DB::connection('mysql2')->select("SELECT * FROM tests WHERE department = '2'");
        $med = DB::connection('mysql2')->select("SELECT * FROM tests WHERE department = '3'");
        $pack = DB::connection('mysql2')->select("SELECT * FROM packaging");
        $meth = DB::connection('mysql2')->select("SELECT * FROM methods");
        return view('edit')->with([
                    'title' => 'EDIT SAMPLE ANALYSIS REQUEST FORM: ' . $data[0]->request_id . '',
                    'dosage' => $dosage,
                    'wet' => $wet,
                    'micro' => $micro,
                    'med' => $med,
                    'pack' => $pack,
                    'meth' => $meth,
                    'data' => $data,
                    'client' => $client,
                    'sd' => $selected_dosage,
                    'tests' => $tests,
                    'tids' => $request_details
        ]);
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
    public function update(Request $req) {

        $r = RequestModel::where('request_id', $req->request_id)
                ->update([
            'sample_qty' => $req->sample_qty,
            'sample_quantity_bup' => $req->sample_qty,
            'product_name' => $req->product_name,
            'active_ing' => $req->active_ing,
            'dosage_form' => $req->dosage_form,
            'manufacturer_name' => $req->manufacturer_name,
            'manufacturer_add' => $req->manufacturer_add,
            'batch_no' => $req->batch_no,
            'exp_date' => $req->exp_date,
            'manufacture_date' => $req->manufacture_date,
            'designator_name' => Auth::user()->name,
            'label_claim' => $req->label_claim,
            'description' => 'To be filled later',
            'packaging' => $req->dosage_form,
            'presentation' => $req->presentation,
            'country_of_origin' => $req->country_of_origin,
            'quotation' => $req->quotation,
            'moa' => $req->method
        ]);

        $new_id = [];
        foreach ($req->tests as $all):
            array_push($new_id, $all);
        endforeach;
        $final_array = array_unique($new_id);

        DB::delete(DB::raw("DELETE FROM request_details WHERE request_id='$req->request_id'"));

        foreach ($final_array as $t):
            $rd = new RequestDetails;
            $rd->request_id = $req->request_id;
            $rd->test_id = $t;
            $rd->limits = 'NULL';
            $rd->analyst_id = 0;
            $rd->save();
        endforeach;
        return redirect('edit/' . $req->request_id);
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
