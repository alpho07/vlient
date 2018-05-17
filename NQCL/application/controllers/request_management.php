<?php

//session_start();
class Request_Management extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

    function show_session() {
        $k = mysql_query('SELECT CURRENT_USER()');
        while ($row = mysql_fetch_array($k)) {
            echo $row[0];
        }
    }

    public function index() {
        $this->listing();
    }

    public function client_samples() {
        $this->listingClients();
    }

    public function markSelectedAsOos() {

        //Get array of all uri segments
        $all_uri_segments = $this->uri->segment_array();

        //Loop through all segments, insert NDQD segments into NDQD array 
        for ($i = 0; $i <= count($all_uri_segments); $i++) {
            if ($i > 2) {
                //Set oos status to 1
                $oos_status_update = array('oos_status' => 1);
                $this->db->where('request_id', $all_uri_segments[$i]);
                $this->db->update('request', $oos_status_update);
            }
        }
    }

    public function documentationReport() {
        //Default period is six months from now
        $e = date('Y-m-d');
        $s = date('Y-m-d', strtotime($e . '- 6 months'));

        //Get table columns
        $data['columns'] = $this->documentationReportColumns($s, $e);
        $data['start_date'] = $s;
        $data['end_date'] = $e;

        //Other
        $data['title'] = "Documentation Report";
        $data['settings_view'] = "documentation_report_v";
        $this->base_params($data);
    }

    public function getDocumentationReportArray($start_date, $end_date) {
        //Get Columns to be in documentation report table 
        $report_sql = "SELECT 
            DATE_FORMAT(r.designation_date, '%b-%y') as month,
            count(*) as samples_received, 
            count(case r.coa_done_status when '1' then 1 else NULL end ) as coas_issued,
            count(case r.analyst_status when '1' then 1 else NULL end) - count(case r.supervisor_status when '1' then 1 else NULL end) as analysis_ongoing,
            count(*) - count(case r.analyst_status when '1' then 1 else NULL end) as received_yet_to_be_issued,
            count(case w.reason_id when '1' then 1 else NULL end) as missing_reagents,
            count(case w.reason_id when '2' then 1 else NULL end) as missing_crs,
            count(case w.reason_id when '8' then 1 else NULL end) as moa_deficiency,
            count(case w.reason_id when '3' then 1 else NULL end) as equipment_deficiency,
            count(case w.reason_id when '4' then 1 else NULL end) as client_withdrawal
            FROM request r 
            LEFT JOIN assign_withdrawal_log w on w.request_id = r.request_id
            WHERE (r.designation_date
            BETWEEN '$start_date' AND '$end_date')
            GROUP BY MONTH(r.designation_date)";
        $report = $this->db->query($report_sql)->result_array();
        return $report;
    }

    public function documentationReportColumns($s, $e) {

        //Get array keys as report columns
        $documentation_report = $this->getDocumentationReportArray($s, $e);
        $report_columns = array();

        //Loop through result
        foreach (array_keys($documentation_report[0]) as $key) {
            array_push($report_columns, $key);
        }

        return $report_columns;
    }

    public function documentationReportAjax($s, $e) {
        $documentation_report = $this->getDocumentationReportArray($s, $e);
        echo json_encode($documentation_report);
    }

    public function samples() {
        //$data = array();
        $data['tag'] = $this->uri->segment(3);
        $data['filters'] = Request_filters::getAll();
        $data['title'] = "Request Management";
        $data['settings_view'] = "requests_v_ajax";
        $data['info'] = Request::getAll();
        $data['request'] = $this->getsamples();
        $this->base_params($data);
    }

    public function requests_list_all() {
        $filter = $this->uri->segment(3);
        $request = Request::getAllHydratedAll($filter);
        if (!empty($request)) {
            foreach ($request as $r) {
                $data[] = $r;
            }
            echo json_encode($data);
        } else {
            echo "[]";
        }
    }

    public function requests_list_year($year) {

        $request = Request::getAllHydratedYear($year);
        if (!empty($request)) {
            foreach ($request as $r) {
                $data[] = $r;
            }
            echo json_encode($data);
        } else {
            echo "[]";
        }
    }

    public function requests_list_current() {

        $request = Request::getAllHydrated();
        if (!empty($request)) {
            foreach ($request as $r) {
                $data[] = $r;
            }
            echo json_encode($data);
        } else {
            echo "[]";
        }
    }

    public function requests_list_current_clients() {

        $request = Request::getAllHydratedClients();
        if (!empty($request)) {
            foreach ($request as $r) {
                $data[] = $r;
            }
            echo json_encode($data);
        } else {
            echo "[]";
        }
    }

    function get_tests() {
        return $this->db->order_by('name', 'ASC')->get('tests')->result();
    }

    function tests_requested() {
        echo '1';
    }

    public function load_test_requested($labref) {

        $request = $this->db->query("SELECT t.name
FROM request_details rd, tests t, request r
WHERE t.id = rd.test_id
AND rd.request_id = r.request_id
AND r.request_id = '$labref'")->result();
        if (!empty($request)) {
            foreach ($request as $r) {
                $data[] = $r;
            }
            echo json_encode($data);
        } else {
            echo "Not Available At this time";
        }
    }

    public function getLogColumns($status) {
        //Get Columns to be in log table
        $columns_sql = "SELECT `COLUMN_NAME`, `COLUMN_COMMENT`
			FROM `INFORMATION_SCHEMA`.`COLUMNS`
			WHERE `TABLE_NAME`='request_log'
			AND `COLUMN_COMMENT` <> ''";
        $columns = $this->db->query($columns_sql)->result_array();
        return $columns;
    }

    public function requests_log() {

        //Get parameters from uri
        $id = $this->uri->segment(3);
        $status = $this->uri->segment(4);

        //Get Columns to be in log table
        $columns = $this->getLogColumns($status);

        //Initialize strings to hold columns sql
        $union_sql = '';
        $columns_string = '';
        $lastColumn = end($columns);

        //For loop populates columns
        foreach ($columns as $column) {
            $clmn = $column['COLUMN_NAME'];
            if ($column != $lastColumn) {
                $columns_string .= $clmn . ',';
                $union_sql .= "SELECT DISTINCT " . $clmn . " FROM request_log WHERE request_log_id =" . $id . " AND action = 'Updated' UNION ";
            } else {
                $columns_string .= $clmn;
                $union_sql .= "SELECT DISTINCT " . $clmn . " FROM request_log WHERE request_log_id =" . $id . " AND action = 'Updated'";
            }
        }

        //Get original unedited sample
        if ($status == 'new') {
            $sqlEdited = "SELECT " . $columns_string . " FROM request_log WHERE request_log_id =" . $id . " AND action = 'inserted' UNION SELECT " . $columns_string . " FROM request_log WHERE request_log_id =" . $id . " AND action = 'updated' ";
            $edited = $this->db->query($sqlEdited)->result_array();
            echo json_encode($edited);
            /* if(!empty($edited)){
              foreach ($edited as $r) {
              $data[] = $r;
              }
              echo json_encode($data);
              //echo json_encode($data);
              }
              else{
              echo "[]";
              } */
        } else if ($status == 'old') {
            $old_columns_string = str_replace('new', 'old', $columns_string);
            $sqlUnEdited = "SELECT " . $old_columns_string . " FROM request_log WHERE request_log_id =" . $id . " AND action = 'updated' ORDER BY request_log_id DESC LIMIT 1";
            $unedited = $this->db->query($sqlUnEdited)->result_array();
            if (!empty($unedited)) {
                foreach ($unedited as $r) {
                    $data[] = $r;
                }
                echo json_encode($data);
                //echo json_encode($data);
            } else {
                echo "[]";
            }
        }
    }

    public function showRequestLog() {
        $status = $this->uri->segment(4);
        $data['rlog_id'] = $this->uri->segment(3);
        $data['columns'] = $this->getLogColumns($status);
        $data['content_view'] = "request_log_v";
        $this->load->view("template1", $data);
    }

    public function requests_list_all_rejected() {
        $request = Request::getAllHydratedAllRejected();
        if (!empty($request)) {
            foreach ($request as $r) {
                $data[] = $r;
            }
            echo json_encode($data);
            //echo json_encode($data);
        } else {
            echo "[]";
        }
    }

    function get_sample_personnel($i) {
        echo json_encode($this->db->where('labref', $i)->get('tracking_table')->result());
    }

    function get_sample_sheet_download($i) {
        return $this->db->where('labref', $i)->get('worksheet_reason')->result();
    }

    function get_sample_compliance($i) {
        return $this->db->where('labref', $i)->get('test_compliance')->result();
    }

    function get_sample_compliancedesc($i) {
        return $this->db->where('labref', $i)->get('test_non_compliance')->result();
    }

    function get_sample_signatories($i) {
        echo json_encode($this->db->where('labref', $i)->get('tracking_table')->result());
    }

    function get_sample_signatories_1($i) {
        echo json_encode($this->db->where('labref', $i)->get('tracking_table')->result());
    }

    function load_tests($i) {
        echo json_encode($this->db->query("SELECT name FROM `tests` t, request_details si WHERE si.test_id = t.id AND si.request_id ='$i'")->result());
    }

    function load_tests_done($i) {
        echo json_encode($this->db->query("SELECT * FROM test_compliance WHERE labref ='$i'")->result());
    }

    function load_tests_reason($i) {
        echo json_encode($this->db->query("SELECT * FROM test_non_compliance WHERE labref ='$i'")->result());
    }

    function saveSampleDetails() {
        $labref = $this->input->post('sample_req');
        $this->db->where('labref', $labref)->delete('tracking_table');
        $activity = $this->input->post('activity');
        $by = $this->input->post('by');
        $d_issued = $this->input->post('to_who');
        $d_returned = $this->input->post('dater');
        $new_date = explode("-", $d_returned);

        $compliance = $this->input->post('compliance');
        $tests = $this->input->post('tests');
        $reason = $this->input->post('reason_of_nonc');
        //$this->output->enable_profiler();

        for ($i = 0; $i < count($activity); $i++) {
            $array = array(
                'labref' => $labref,
                'activity' => $activity[$i],
                'from_who' => $by[$i],
                'to_who' => $d_issued[$i],
                'date_added' => $d_returned[$i],
                'date_added_1' => $d_returned[$i]
            );
            $this->db->insert('tracking_table', $array);
        }
        $this->db->where('labref', $labref)->delete('test_compliance');
        for ($i = 0; $i < count($tests); $i++) {
            $array = array(
                'labref' => $labref,
                'test' => $tests[$i],
                'compliance' => $compliance[$i],
            );

            $this->db->insert('test_compliance', $array);
        }

        $array2 = array(
            'labref' => $labref,
            'reason' => $this->input->post('reason_of_nonc'),
        );
        $this->db->where('labref', $labref)->delete('test_non_compliance');
        $this->db->insert('test_non_compliance', $array2);
        $this->db->where('request_id', $labref)->update('request', array('compliance' => $this->input->post('reason_of_nonc')));
        $this->searchCAN($labref);
    }

    function searchCAN($labref) {
        $activity = $this->input->post('activity');
        $d_returned = $this->input->post('dater');

        for ($i = 0; $i < count($activity); $i++) {
            //echo $activity[$i];
            if (in_array("CAN No.", $activity)) {
                $key = array_search("CAN No.", $activity);
                $data = array(
                    'CAN' => $d_returned[$key],
                );
                $this->db->where('request_id', $labref)->update('request', $data);
                echo 'Found';
            } else {
                echo "No CAN YET";
            }
        }
    }

    function check_su_stat($i) {

        $number = $this->db->where('labref', $i)->count_all_results('sample_details');
        if ($number > 0) {
            echo json_encode(array('stat' => '1'));
        } else {
            echo json_encode(array('stat' => '0'));
        }
    }

    function GetAutocomplete($options = array()) {
        $this->db->distinct();
        $this->db->select('name');
        $this->db->like('name', $options['name'], 'after');
        $query = $this->db->get('clients');
        return $query->result();
    }

    function get_sample_data($i) {
        return $this->db->query("SELECT r.request_id,r.active_ing, r.sample_qty, d.name, r.designation_date, r.manufacturer_name, r.manufacturer_add, r.label_claim, r.country_of_origin, r.dsgntr
FROM request r, dosage_form d
WHERE r.dosage_form = d.id
AND r.request_id='$i';")->result();
    }

    function getMoreSampleInfo() {
        $data['reqid'] = $reqid = $this->uri->segment(3);
        $data['request'] = Request::getSingleHydratedSelect($data['reqid']);
        $data['tests'] = Request_details::getTestsNames($reqid);
        $data['client'] = Clients::getClient3($reqid);
        $data['info'] = $this->get_sample_data($reqid);
        $data['w_res'] = $this->get_sample_sheet_download($reqid);
        $data['c_comp'] = $this->get_sample_compliance($reqid);
        $data['desc'] = $this->get_sample_compliancedesc($reqid);
        $data['content_view'] = "sample_more_info_v";
        $this->load->view("template1", $data);
    }

    //Function to feed quote form lightbox - where documentation enter quoted amount.
    function quote() {
        $data['reqid'] = $this->uri->segment(3);
        $data['request'] = Request::getSingleHydrated($data['reqid']);
        $data['content_view'] = "quote_form_v";
        $this->load->view("template1", $data);
    }

    //Function to save quoted amount to Dispatch Register
    function saveQuote() {
        $quoted_amount = $this->input->post('quoted_amount');
        $negative_credit = -1 * $quoted_amount;
        $reqid = $this->uri->segment(3);
        $clientid = $this->uri->segment(4);
        $quotation_status = "1";
        $quotedamountUpdate = array('quotation_status' => $quotation_status,
            'amount' => $quoted_amount);
        $this->updateDispatchRegister($reqid, $quotedamountUpdate, $quotation_status, $clientid, $quoted_amount);
    }

    //Function to update Dispatch Register
    function updateDispatchRegister($reqid, $quotedamountUpdate, $quotation_status, $clientid, $negative_credit) {

        //Update Dispatch Register
        $this->db->where('request_id', $reqid);
        $this->db->update('dispatch_register', $quotedamountUpdate);

        //Update Request
        $this->db->where('request_id', $reqid);
        $this->db->update('request', array('quotation_status' => $quotation_status));

        //Update Client Credit
        $this->db->where('Clientid', $clientid);
        $this->db->update('clients', array('credit' => $negative_credit));
    }

    function GetAutocompleteActiveIngredients($options = array()) {
        $this->db->distinct();
        $this->db->select('active_ing');
        $this->db->like('active_ing', $options['active_ing'], 'after');
        $query = $this->db->get('request');
        return $query->result();
    }

    function active_ingredient_suggestions() {

        $term = $this->input->post('term', TRUE);

        $rows = $this->GetAutocompleteActiveIngredients(array('active_ing' => $term));

        $keywords = array();
        foreach ($rows as $row)
            array_push($keywords, $row->active_ing);

        echo json_encode($keywords);
    }

    function GetAutocompleteManufacturer($options = array()) {
        $this->db->distinct();
        $this->db->select('manufacturer_name');
        $this->db->like('manufacturer_name', $options['manufacturer_name'], 'after');
        $query = $this->db->get('request');
        return $query->result();
    }

    function manufacturer_suggestions() {

        $term = $this->input->post('term', TRUE);

        $rows = $this->GetAutocompleteManufacturer(array('manufacturer_name' => $term));

        $keywords = array();
        foreach ($rows as $row)
            array_push($keywords, $row->manufacturer_name);

        echo json_encode($keywords);
    }

    function GetAuthorizer($options = array()) {
        $this->db->distinct();
        $this->db->select('dsgntr');
        $this->db->like('dsgntr', $options['dsgntr'], 'after');
        $query = $this->db->get('request');
        return $query->result();
    }

    function Authorizer_suggestions() {

        $term = $this->input->post('term', TRUE);

        $rows = $this->GetAuthorizer(array('dsgntr' => $term));

        $keywords = array();
        foreach ($rows as $row)
            array_push($keywords, $row->dsgntr);

        echo json_encode($keywords);
    }

    function GetDesignation($options = array()) {
        $this->db->distinct();
        $this->db->select('dsgntn');
        $this->db->like('dsgntn', $options['dsgntn'], 'after');
        $query = $this->db->get('request');
        return $query->result();
    }

    function Designation_suggestions() {

        $term = $this->input->post('term', TRUE);

        $rows = $this->GetDesignation(array('dsgntn' => $term));

        $keywords = array();
        foreach ($rows as $row)
            array_push($keywords, $row->dsgntn);

        echo json_encode($keywords);
    }

    function suggestions() {

        $term = $this->input->post('term', TRUE);

        $rows = $this->GetAutocomplete(array('name' => $term));

        $keywords = array();
        foreach ($rows as $row)
            array_push($keywords, $row->name);

        echo json_encode($keywords);
    }

    function GetAutocompleteManufacturerAddress($options = array()) {
        $this->db->distinct();
        $this->db->select('manufacturer_add');
        $this->db->like('manufacturer_add', $options['manufacturer_add'], 'after');
        $query = $this->db->get('request');
        return $query->result();
    }

    function suggestions1() {

        $term = $this->input->post('term', TRUE);

        $rows = $this->GetAutocompleteManufacturerAddress(array('manufacturer_add' => $term));

        $keywords = array();
        foreach ($rows as $row)
            array_push($keywords, $row->manufacturer_add);

        echo json_encode($keywords);
    }

    function GetLabelClaim($options = array()) {
        $this->db->distinct();
        $this->db->select('label_claim');
        $this->db->like('label_claim', $options['label_claim'], 'after');
        $query = $this->db->get('request');
        return $query->result();
    }

    function suggestions2() {

        $term = $this->input->post('term', TRUE);

        $rows = $this->GetLabelClaim(array('label_claim' => $term));

        $keywords = array();
        foreach ($rows as $row)
            array_push($keywords, $row->label_claim);

        echo json_encode($keywords);
    }

    function GetProductName($options = array()) {
        $this->db->distinct();
        $this->db->select('product_name');
        $this->db->like('product_name', $options['product_name'], 'after');
        $query = $this->db->get('request');
        return $query->result();
    }

    function loadPeople() {


        $this->db->select('u.fname as fname, u.lname as lname, u.title as title, u.id as id');
        $this->db->from('user u');
        $this->db->join('users_types as us', 'us.email = u.email', 'left');
        $this->db->where('us.usertype_id = 1');
        $this->db->or_where('us.usertype_id = 2');
        $this->db->or_where('us.usertype_id = 3');
        $this->db->or_where('us.usertype_id = 5');
        $this->db->or_where('us.usertype_id = 8');
        $this->db->or_where('us.usertype_id = 30');
        $query = $this->db->order_by('fname', 'asc')
                ->group_by('u.id')
                ->get()
                ->result();
        echo json_encode($query);
    }

    function suggestions3() {

        $term = $this->input->post('term', TRUE);

        $rows = $this->GetProductName(array('product_name' => $term));

        $keywords = array();
        foreach ($rows as $row)
            array_push($keywords, $row->product_name);

        echo json_encode($keywords);
    }

    function getCodes() {
        $ref = $this->uri->segment(3);
        $refslash = $this->uri->segment(4);
        $ref4 = $this->sanitize_param($ref);
        if (!empty($refslash)) {
            $ref6 = $this->sanitize_param($refslash);
            $ref5 = "_" . $ref6;
        } else {
            $ref5 = "";
        }

        $alias = $ref4 . $ref5;
        $codes = Clients::getClientDetails($alias);
        echo json_encode($codes);
    }

    function pushCodes() {
        $codes = $this->getCodes();
        $codes_array = array();

        foreach ($codes as $code)
            array_push($codes_array, $code->code);
        echo json_encode($codes_array);
    }

    //Get Manufacturer Details

    function getManufacturerDetails() {
        //Pass Manufacturer Name at end of Uri
        $ref = $this->uri->segment(3);

        //Replace Spaces with Underscore
        $ref = str_replace('%20', '_', $ref);

        //Get Array of Manufacturer Details from Request Table
        $codes = Request::getManufacturerDetail($ref);

        //Encode received array into json
        echo json_encode($codes);
    }

    function getAuthorizerDetails() {
        //Pass Manufacturer Name at end of Uri
        $ref = $this->uri->segment(3);

        //Replace Spaces with Underscore
        $ref = str_replace('%20', '_', $ref);

        //Get Array of Manufacturer Details from Request Table
        $codes = Request::getAuthorizerDetail($ref);

        //Encode received array into json
        echo json_encode($codes);
    }

    function getProductDetails() {
        //Pass Manufacturer Name at end of Uri
        $ref = $this->uri->segment(3);

        //Replace Spaces with Underscore
        $ref = str_replace('%20', '_', $ref);

        //Get Array of Manufacturer Details from Request Table
        $codes = Request::getProductDetail($ref);

        //Encode received array into json
        echo json_encode($codes);
    }

    function pushManufacDetails() {
        $codes = $this->getCodes();
        $codes_array = array();

        foreach ($codes as $code)
            array_push($codes_array, $code->code);
        echo json_encode($codes_array);
    }

    function suggestions4() {

        $term = $this->input->post('term', TRUE);

        $rows = $this->GetProductDescription(array('description' => $term));

        $keywords = array();
        foreach ($rows as $row)
            array_push($keywords, $row->description);

        echo json_encode($keywords);
    }

    function GetProductDescription($options = array()) {
        $this->db->distinct();
        $this->db->select('description');
        $this->db->like('description', $options['description'], 'after');
        $query = $this->db->get('request');
        return $query->result();
    }

    function getsamples() {
        return $this->db->select('request_id')->get('request')->result();
    }

    function make_oos($labref) {
        $this->db->where('request_id', $labref)->update('request', array('oos' => '1'));
        $this->db->where('request_id', $labref)->update('request', array('oos_status' => '1'));
        $this->db->where('labref', $labref)->update('tests_done', array('oos' => '1'));
        redirect('supervisors/');
    }

    function mark_as_solved($labref) {
        $this->db->where('request_id', $labref)->update('request', array('rejected_status' => '0'));
        $this->db->update('reviewer_worksheets', array('status' => '0'));
        //redirect('supervisors/');
    }

    public function test_methods() {
        $reqid = $this->uri->segment(3);
        $data['tests'] = Request_details::getTests($reqid);
        $data['settings_view'] = "tests_methods_v";
        $this->base_params($data);
    }

    public function coPackageSave() {

        if (is_null($_POST)) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Data was not posted.'
            ));
        } else {
            echo json_encode(array(
                'status' => 'success',
                'message' => 'Data added successfully',
                'array' => json_encode($_POST)
            ));
        }

        $reqid = $this->uri->segment(3);
        $no_of_packs = $this->input->post("no_of_packs", TRUE);


        for ($i = 1; $i <= $no_of_packs; $i++) {
            $copack = new Copackages();
            $copack->request_id = $reqid;
            $copack->pack_no = $i;
            $copack->no_of_packs = $no_of_packs;
            $copack->save();
        }
    }

    public function label_form() {
        $data['reqid'] = $this->uri->segment(3);
        $data['tests'] = Request_details::getTestHistory($data['reqid']);
        $data['content_view'] = "label_form_v";
        $this->load->view("template1", $data);
    }

    public function generate_label() {
        $data['title'] = "Generate Label";
        $data['wetchemistry'] = Tests::getWetChemistry();
        $data['microbiologicalanalysis'] = Tests::getMicrobiologicalAnalysis();
        $data['medicaldevices'] = Tests::getMedicalDevices();
        $data['reqid'] = $this->uri->segment(3);
        $data['tests'] = Request_details::getTestHistory($data['reqid']);
        $data['content_view'] = "label_generate_v";
        $this->load->view("template1", $data);
    }

    public function coPackageDetailsSave() {
        if (is_null($_POST)) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Data was not posted.'
            ));
        } else {
            echo json_encode(array(
                'status' => 'success',
                'message' => 'Data added successfully',
                'array' => json_encode($_POST)
            ));
        }

        $reqid = $this->uri->segment(3);
        $name = $this->input->post('cp_name', TRUE);
        $batch_no = $this->input->post('cp_batch_no', TRUE);
        $exp_date = $this->input->post('cp_exp_date', TRUE);
        $mfg_date = $this->input->post('cp_mfg_date', TRUE);
        $quantity = $this->input->post('cp_quantity', TRUE);
        $unit = $this->input->post('cp_unit', TRUE);


        $copack = new Copackages();
        $copack->name = $name;
        $copack->request_id = $reqid;
        $copack->batch_no = $batch_no;
        $copack->exp_date = date('y-m-d', strtotime($exp_date));
        $copack->mfg_date = date('y-m-d', strtotime($mfg_date));
        $copack->quantity = $quantity;
        $copack->unit = $unit;
        $copack->save();
    }

    public function getTestMethods() {
        $testid = $this->uri->segment(3);
        $methods = Test_methods::getMethods($testid);
        echo json_encode($methods);
    }

    public function getMethodTypes() {
        $types = Test_methods_types::getAll();
        echo json_encode($types);
    }

    public function history() {

        $reqid = $this->uri->segment(3);
        $version_id = $this->uri->segment(4);
        //$data['row_count'] = Request::getRowCount();
        $data['history'] = Request::getHistory($reqid, $version_id);
        //$this -> view -> load('history_table');
        //$data['test_history'] = Request_details::testHistory($reqid, $version_id);
        //$data['settings_view'] = "history";
        $this->load->view('history', $data);
    }

    public function other_history() {

        $reqid = $this->uri->segment(3);
        $version_id = $this->uri->segment(4);
        //$data['row_count'] = Request::getRowCount();
        $data['chistory'] = Clients::getHistory($reqid, $version_id);
        $data['thistory'] = Request_details::getTestHistory($reqid, $version_id);
        //$this -> view -> load('history_table');
        //$data['test_history'] = Request_details::testHistory($reqid, $version_id);
        //$data['settings_view'] = "history";
        $this->load->view('other_history', $data);
    }

    public function getLabelPdf_standalone() {

        //DOMpdf initialization
        require_once("application/helpers/dompdf/dompdf_config.inc.php");
        $this->load->helper('dompdf', 'file');
        $this->load->helper('file');

        //DOMpdf configuration
        $dompdf = new DOMPDF();
        $dompdf->set_paper(array(0, 0, 316.8, 432));

        //Initialize Array to hold tests
        $tests = [];

        //Get array of all uri segments
        $t_array = $this->uri->segment_array();

        /* Loop through said array above, if index of array element is greater than 4 (where tests uri start)
          push element into tests[] array */

        foreach ($t_array as $key => $value) {
            if ($key > 4) {
                array_push($tests, $value);
            }
        }

        //Variable assignment
        $saveTo = './labels';
        $data['tests'] = $tests;
        $data['reqid'] = $this->uri->segment(3);
        $reqid = $data['reqid'];
        $data['prints_no'] = $this->uri->segment(4);
        $labelname = "Label" . $data['reqid'] . ".pdf";
        $data['settings_view'] = "label_view_standalone";
        $this->base_params($data);
        $html = $this->load->view('label_view_standalone', $data, TRUE);
        $dompdf->load_html($html);
        $dompdf->render();
        write_file($saveTo . "/" . $labelname, $dompdf->output());
        $this->setLabelStatus($reqid, $saveTo, $labelname);
        //$this -> output -> enable_profiler(TRUE);
    }

    public function getLabelPdf() {

        require_once("application/helpers/dompdf/dompdf_config.inc.php");

        $this->load->helper('dompdf', 'file');
        $this->load->helper('file');

        $dompdf = new DOMPDF();
        $dompdf->set_paper(array(0, 0, 316.8, 432));

        $saveTo = './labels';
        $data['reqid'] = $this->uri->segment(3);
        $reqid = $data['reqid'];
        $data['prints_no'] = $this->uri->segment(4);
        $labelname = "Label" . $data['reqid'] . ".pdf";
        $data['infos'] = Request::getSample($data['reqid']);
        $data['settings_view'] = "label_view2";
        $this->base_params($data);
        $html = $this->load->view('label_view2', $data, TRUE);

        $dompdf->load_html($html);
        $dompdf->render();
        write_file($saveTo . "/" . $labelname, $dompdf->output());
        $this->setLabelStatus($reqid, $saveTo, $labelname);
    }

    public function setLabelStatus($reqid, $saveTo, $labelname) {
        $file = $saveTo . "/" . $labelname;
        if (file_exists($file)) {
            $label_status = "1";
        } else {
            $label_status = "0";
        }

        //Update request table with label status
        $this->db->where('request_id', $reqid);
        $this->db->update('request', array('label_status' => $label_status));
    }

    public function edit_view() {
        $data['requests'] = Request::getAll();
        $this->load->view("requests_v_ajax", $data);
    }

    public function requests_list() {
        $request = Request::getAllHydrated();
        if (!empty($request)) {
            foreach ($request as $r) {
                $data[] = $r;
            }
            echo json_encode($data);
        } else {
            echo "[]";
        }
    }

    public function requests_list_oos1() {
        $request = Request::getAllHydratedOos();
        if (!empty($request)) {
            foreach ($request as $r) {
                $data[] = $r;
            }
            echo json_encode($data);
        } else {
            echo "[]";
        }
    }

    public function getRequest() {
        $reqid = $this->uri->segment(3);
        $request = Request::getSingleHydrated($reqid);
        echo json_encode($request);
    }

    public function setPresentationDescription() {


        $reqid = $this->uri->segment(3);
        $test_id = $this->uri->segment(4);
        $analyst_id = $this->uri->segment(5);
        $presentation = $this->input->post("presentation");
        $description = $this->input->post("description");
        $worksheet_url = $this->input->post("worksheet_url");

        $desc_status = '1';

        //Get monograph comment and id arrays
        $monograph_comment = $this->input->post("monograph_comment");
        $monograph_ids = $this->input->post("monograph_ids");

        //Loop through monographs, save
        for ($i = 0; $i < count($monograph_comment); $i++) {
            $monograph = new Monograph_usage();
            $monograph->request_id = $reqid;
            $monograph->analyst_id = $analyst_id;
            $monograph->monograph_id = $monograph_ids[$i];
            $monograph->comment = $monograph_comment[$i];
            $monograph->save();
        }

        $this->session->set_userdata('wksht_url', $worksheet_url);
        $presentation_description_update = array(
            'description' => $description,
            'presentation' => $presentation
        );

        $sample_issuance_update = array(
            'desc_status' => $desc_status
        );

        $s_array = array(
            'lab_ref_no' => $reqid
        );

        //Check if sample exists, update description and corresponding status
        if ($this->ifRequestIdExists($reqid) == 1) {

            //Update status
            $this->db->where($s_array);
            $this->db->update('sample_issuance', $sample_issuance_update);

            //Update description
            $this->db->where('request_id', $reqid);
            $this->db->update('request', $presentation_description_update);
        } else {
            echo json_encode(
                    array('errormsg' => '<small>Error updating description. Possible NDQD number changed. Check with Documentation.</small>')
            );
        }

        //Get all tests
        $all_tests = Request_details::getTestIds($reqid);

        //Get Client Id
        $client_id_info = Request::getClientId($reqid);
        $client_id = $client_id_info[0]['client_id'];

        //Loop through tests gotten and save an entry per iteration in the invoice billing table.
        foreach ($all_tests as $test) {

            //Get charges for tests that do not require specification of method
            $test_charges = Tests::getCharges($test['test_id']);

            //Make entries in Invoice Billing Table
            $cb = new Invoice_billing();
            $cb->request_id = $reqid;
            $cb->client_id = $client_id;
            $cb->test_id = $test['test_id'];
            if (!empty($test_charges)) {
                $cb->test_charge = $test_charges[0]['Charge'];
            }
            if ($test['test_id'] == 6) {
                $cb->method_charge = 2000;
                $cb->method_id = 31;
            }
            $cb->save();
        }
    }

    public function ifRequestIdExists($reqid) {
        $ridCount = Request::getRequestIdCount($reqid);
        return $ridCount[0]['count'];
    }

    public function getClientInfo() {
        $id = $this->uri->segment(3);
        $id = Clients::getClientInfo($id);
        echo json_encode($id);
    }

    function Assigned_samples() {
        $data['settings_view'] = "request_v_ds";
        $data['info'] = $this->getAssigned();
        $data['clients'] = $this->getClients();
        $data['anna'] = $this->getAnalsyts();
        $data['anna_r'] = $this->getReviewersArray();
        $data['title'] = "Assigned Samples";
        $this->base_params($data);
    }

    function Analyst_report() {
        $data['settings_view'] = "request_v_ds_3";
        $data['clients'] = $this->getClients();
        $data['anna'] = $this->getAnalsyts();
        $data['anna_r'] = $this->getReviewersArray();
        $data['title'] = "Monthly Samples";
        $this->base_params($data);
    }

    function unassigned() {
        $data['contents'] = "r_test_1";
        $data['info'] = $this->getAssigned();
        $data['activities'] = $this->get_status();
        $data['clients'] = $this->getClients();
        $data['anna'] = $this->getAnalsyts();
        $data['anna_r'] = $this->getReviewersArray();
        $data['depts'] = $this->getTestDepartments();
        $data['title'] = "Unassigned Samples";
        $data['phead'] = "NQCL &#187; Report Area";
        $data['r_h'] = "Unassigned Samples";
        $this->report_base($data);
    }

    function report_engine() {
        $data['contents'] = "r_test";
        $data['info'] = $this->getAssigned();
        $data['activities'] = $this->get_status();
        $data['clients'] = $this->getClients();
        $data['anna'] = $this->getAnalsyts();
        $data['anna_r'] = $this->getReviewersArray();
        $data['depts'] = $this->getTestDepartments();
        $data['title'] = "Client Report Engine";
        $data['phead'] = "NQCL &#187; Report Area";
        $data['r_h'] = "Client Report Generation";
        $this->report_base($data);
    }

    function get_status() {
        return $this->db
                        ->query("SELECT `activity` FROM `tracking_table` WHERE activity!='' GROUP BY activity")
                        ->result();
    }

    function getTestDepartments() {
        return $this->db
                        ->query("SELECT * FROM test_departments")
                        ->result();
    }

    function report_base($data) {
        $this->load->view('report_engine', $data);
    }

    function getClients() {
        $query = $this->db->query("SELECT c.id, c.name, COUNT(r.client_id) as  popular
FROM clients c
JOIN request r ON 
r.client_id = c.id
GROUP BY r.client_id ORDER BY popular DESC")->result();
        return $query;
    }

    function getAnalsyts() {
        $this->db->select('u.fname as fname, u.lname as lname,u.title as title, u.id as id');
        $this->db->from('user u');
        $this->db->join('users_types as us', 'us.email = u.email', 'left');
        $this->db->where('us.usertype_id = 1');
        $query = $this->db->get()->result();
        return $query;
    }

    function getReviewersArray() {
        $this->db->select('u.fname as fname, u.lname as lname,u.title as title, u.id as id');
        $this->db->from('user u');
        $this->db->join('users_types as us', 'us.email = u.email', 'left');
        $this->db->where('us.usertype_id = 3');
        $this->db->or_where('us.usertype_id = 28');
        $this->db->or_where('us.usertype_id = 31');
        $query = $this->db->get()->result();
        return $query;
    }

    function Review_samples() {
        $data['settings_view'] = "request_v_rs";
        $data['info'] = $this->getReview();
        $data['title'] = "Review Samples";
        $this->base_params($data);
    }

    function Draft_certificate_samples() {
        $data['settings_view'] = "request_v_ds_1";
        $data['info'] = $this->getDraftCert();
        $data['title'] = "Draft Certificate Samples";
        $this->base_params($data);
    }

    function getDraftCert() {
        return $this->db->where('stat', 0)->get('draft_samples')->result();
    }

    function getAssigned() {
        return $this->db->query("
			SELECT a_s.*, si.samples_no as quantity_issued, p.name as sample_packaging, r.packaging
			FROM `assigned_samples` a_s
			left join sample_issuance si on a_s.labref = si.lab_ref_no
			left join request r on a_s.labref = r.request_id
			left join packaging p on r.packaging = p.id
			group by a_s.labref")->result();
    }

    function getReview() {
        return $this->db->where('stat', 0)->get('review_samples')->result();
    }

    function complete($labref) {
        $this->db->where('labref', $labref)->update('assigned_samples', array('a_stat' => 1));

        $supervisor = $this->getSupervisor($labref);
        $from = $supervisor[0]->analyst_name;
        $date = date('d-M-Y H:i:s');
        $activity = 'Documentation - Awaiting review';
        $array_data = array(
            'activity' => $activity,
            'from' => $from,
            'to' => 'Documentation',
            'date' => $date,
            'stage' => '6',
            'current_location' => 'Documentation'
        );
        $this->db->where('labref', $labref)->update('worksheet_tracking', $array_data);
        $this->db->where('labref', $labref)->update('assigned_samples', array('date_time_returned' => $date));
        redirect('request_management/assigned_samples');
    }

    function confirm_completion($labref) {
        $date = date('d-M-Y H:i:s');
        $this->db->where('labref', $labref)->update('draft_samples', array('a_stat' => 3, 'date_time_completed' => $date));
        $supervisor = $this->getSupervisor($labref);
        $from = $supervisor[0]->analyst_name;

        $activity = 'Printed and Archieved';
        $array_data = array(
            'activity' => $activity,
            'from' => 'Documentation',
            'to' => 'Documentation',
            'date_added' => $date,
            'stage' => '12',
            'current_location' => 'Documentation'
        );
        $this->db->where('labref', $labref)->update('worksheet_tracking', $array_data);
        redirect('request_management/Draft_certificate_samples/');
    }

    public function getSupervisor($labref) {
        // $user_id = $this->session->userdata('user_id');
        $this->db->select('analyst_name');
        $this->db->where('labref', $labref);
        $query = $this->db->get('assigned_samples');
        return $result = $query->result();
    }

    public function SendToReviewer() {

        $labref = $this->uri->segment(3);
        $reviewer_name = $this->input->post('reviewer');

        $data = array(
            'labref' => $labref,
            'analyst_name' => $reviewer_name,
            'date_time' => date('d-m-Y H:i:s'),
        );
        $this->db->insert('review_samples', $data);
        $this->db->where('labref', $labref)->update('assigned_samples', array('stat' => 1));
        //redirect('request_management/assigned_samples');
    }

    public function SendToReviewer_r() {

        $labref = $this->uri->segment(3);
        $reviewer_name = $this->input->post('reviewer');

        $data = array(
            'labref' => $labref,
            'analyst_name' => $reviewer_name,
            'date_time' => date('d-m-Y H:i:s'),
        );
        $this->db->insert('review_samples', $data);
        $this->db->where('labref', $labref)->update('review_samples', array('stat' => 1));
        //redirect('request_management/assigned_samples');
    }

    public function listing() {
        //$data = array();
        $data['title'] = "Request Management";
        $data['filters'] = Request_filters::getAll();
        $data['settings_view'] = "requests_v_ajax";
        $data['info'] = Request::getAll();
        $data['tests'] = $this->get_tests();
        $data['request'] = $this->getsamples();
        $this->base_params($data);
    }

    public function listingClients() {
        //$data = array();
        $data['title'] = "Clients - Request Management";
        $data['filters'] = Request_filters::getAll();
        $data['settings_view'] = "requests_v_ajax_1";
        $data['info'] = Request::getAll();
        $data['tests'] = $this->get_tests();
        $data['request'] = $this->getsamples();
        $this->base_params($data);
    }

    public function retriever() {
        //$data = array();
        $data['title'] = "Lost & Found";
        $data['filters'] = Request_filters::getAll();
        $data['settings_view'] = "lost_and_found";
        $data['info'] = Request::getAll();
        $data['tests'] = $this->get_tests();
        $data['request'] = $this->getsamples();
        $this->base_params($data);
    }

    function correctdata($table, $labref) {
        $this->db->where('labref', $labref)->delete($table);
        $this->db->insert($table, array('labref' => $labref, 'assign_status' => '0'));
        $this->RecoverSample($labref);
    }

    function getlastplace($labref, $location = '') {
        $query = $this->db
                ->where('labref', $labref)
                ->order_by('id', 'ASC')
                ->limit(1)
                ->get('tracking_table')
                ->result();

        echo json_encode($query);
    }

//end listing

    function ajax_loader() {
        $this->db->select_max('id');
        $query = $this->db->get('request');
        $data = $query->result();
        echo json_encode($data);
    }

    function ajax_client_loader() {
        $this->db->select_max('id');
        $query = $this->db->get('clients');
        $data = $query->result();
        return $data;
    }

    public function add() {
        $data['currency'] = Currencies::getAll();
        $data['new_clientid'] = $this->ajax_client_loader();
        $data['months'] = Months::getAll();
        $data['title'] = "Add New Request";
        $data['last_req_id'] = Request::getLastRequestId();
        $data['lastClient'] = Clients::getLastId();
        //var_dump($data['last_req_id']);
        $data['dosageforms'] = Dosage_form::getAll();
        $data['packages'] = Packaging::getAll();
        $data['usertypes'] = User_type::getAll();
        $data['clients'] = Clients::getAll();
        $data['sample_id'] = '1';
        $data['wetchemistry'] = Tests::getWetChemistry();
        $data['microbiologicalanalysis'] = Tests::getMicrobiologicalAnalysis();
        $data['medicaldevices'] = Tests::getMedicalDevices();
        $data['client_agents'] = Client_agents::getAll();
        $data['scripts'] = array("jquery-ui.js");
        $data['scripts'] = array("jquery.ui.core.js", "jquery.ui.datepicker.js", "jquery.ui.widget.js");
        $data['styles'] = array("jquery.ui.all.css");
        $data['settings_view'] = "request_v";
        $this->base_params($data);
    }

//end add

    public function edit() {
        error_reporting(0);
        $reqid = $this->uri->segment(3);
        $data['reqid'] = $this->uri->segment(3);
        $data['labref'] = $this->uri->segment(3);
        $data['tests_checked'] = Request_details::getTestsNames($reqid);
        $data['title'] = "Edit Request";
        $data['tests_issued'] = Sample_issuance::getIssuedTests2($reqid);
        $data['months'] = Months::getAll();
        $data['packages'] = Packaging::getAll();
        $data['dosageforms'] = Dosage_form::getAll();
        $data['wetchemistry'] = Tests::getWetChemistry();
        $data['microbiologicalanalysis'] = Tests::getMicrobiologicalAnalysis();
        $data['medicaldevices'] = Tests::getMedicalDevices();
        $data['client'] = Clients::getClient2($reqid);
        $data['request'] = Request::getSingleHydrated($reqid);
        $data['issuance_status'] = Sample_issuance::checkIfIssued($reqid);
        $data['settings_view'] = "edit_request_v";
        $data['info'] = Request::getAll();
        $this->base_params($data);
    }

    public function edit_1() {
        error_reporting(0);
        $reqid = $this->uri->segment(3);
        $data['reqid'] = $this->uri->segment(3);
        $data['labref'] = $this->uri->segment(3);
        $data['tests_checked'] = Request_details::getTestsNames($reqid);
        $data['title'] = "Edit Request";
        $data['tests_issued'] = Sample_issuance::getIssuedTests2($reqid);
        $data['months'] = Months::getAll();
        $data['packages'] = Packaging::getAll();
        $data['dosageforms'] = Dosage_form::getAll();
        $data['wetchemistry'] = Tests::getWetChemistry();
        $data['microbiologicalanalysis'] = Tests::getMicrobiologicalAnalysis();
        $data['medicaldevices'] = Tests::getMedicalDevices();
        $data['client'] = Clients::getClientNew($reqid);
        $data['request'] = Request::getSingleHydrated($reqid);
        $data['issuance_status'] = Sample_issuance::checkIfIssued($reqid);
        $data['settings_view'] = "edit_request_v";
        $data['info'] = Request::getAll();
        $this->base_params($data);
    }

    public function edit_clients() {
        error_reporting(0);
        $reqid = $this->uri->segment(3);
        $data['reqid'] = $this->uri->segment(3);
        $data['labref'] = $this->uri->segment(3);
        $data['tests_checked'] = Request_details::getTestsNames($reqid);
        $data['title'] = "Edit Request";
        $data['tests_issued'] = Sample_issuance::getIssuedTests2($reqid);
        $data['months'] = Months::getAll();
        $data['packages'] = Packaging::getAll();
        $data['dosageforms'] = Dosage_form::getAll();
        $data['wetchemistry'] = Tests::getWetChemistry();
        $data['microbiologicalanalysis'] = Tests::getMicrobiologicalAnalysis();
        $data['medicaldevices'] = Tests::getMedicalDevices();
        $data['client'] = Clients::getClientNew($reqid);
        $data['request'] = Request::getSingleHydrated($reqid);
        $data['issuance_status'] = Sample_issuance::checkIfIssued($reqid);
        $data['settings_view'] = "edit_request_v_1";
        $data['info'] = Request::getAll();
        $this->base_params($data);
    }

    public function edit_save($labref) {

        if (is_null($_POST)) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Data was not posted.'
            ));
        } else {
            echo json_encode(array(
                'status' => 'success',
                'message' => 'Data added successfully',
                'array' => json_encode($_POST)
            ));
        }
        $priority = $this->input->post("priority");
        $id = $this->input->post("labref_id");
        $old_reqid = $this->input->post("lab_ref_no1");
        $reqid = $this->input->post("lab_ref_no");
        $dateformat = $this->input->post("dateformat");
        $test = $this->input->post("test");
        $cid = $this->input->post("client_id");
        $product_name = $this->input->post("product_name");
        $dosage_form = $this->input->post("dosage_form");
        $manufacturer_name = $this->input->post("manufacturer_name");
        $manufacturer_address = $this->input->post("manufacturer_address");
        $batch_no = $this->input->post("batch_no");
        $expiry_date = $this->input->post("e_date");
        $manufacture_date = $this->input->post("m_date");
        $label_claim = $this->input->post("label_claim");
        $active_ingredients = $this->input->post("active_ingredients");
        $quantity = $this->input->post("quantity");
        $applicant_reference_number = $this->input->post("applicant_reference_number");
        $client_number = $labref;
        $designator_name = $this->input->post("designator_name");
        $designation = $this->input->post("designation");
        // $designation_date = $this->input->post("designation_date");



        $urgency = $this->input->post("urgency");
        $edit_notes = $this->input->post("edit_notes");
        $country_of_origin = $this->input->post("country_of_origin");
        $product_lic_no = $this->input->post("product_lic_no");
        $presentation = $this->input->post("presentation");
        $description = $this->input->post("description");
        $clientsampleref = $this->input->post("client_ref_no");
        $packaging = $this->input->post("packaging");

        //Request Update Array
        $request_update_array = array(
            'request_id' => $reqid,
            'client_id' => $cid,
            'sample_qty' => $quantity,
            'priority' => $priority,
            'product_name' => $product_name,
            'label_claim' => $label_claim,
            'packaging' => $packaging,
            'active_ing' => $active_ingredients,
            'Dosage_form' => $dosage_form,
            'Manufacturer_name' => $manufacturer_name,
            'Manufacturer_add' => $manufacturer_address,
            'Batch_no' => $batch_no,
            'exp_date' => $expiry_date,
            'Manufacture_date' => $manufacture_date,
            'Designator_Name' => $designator_name,
            //'Designation_date' => $designation_date,
            'Urgency' => $urgency,
            'edit_notes' => $edit_notes,
            'clientsampleref' => $clientsampleref
        );


        //Update main request table
        $this->db->where(array('id' => $id));
        $this->db->update('request', $request_update_array);


        //Delete existing tests from Request Details
        $this->db->where(array('request_id' => $old_reqid));
        $this->db->delete('request_details');

        //Delete existing tests from Coa Body
        $this->db->where(array('labref' => $old_reqid));
        $this->db->delete('coa_body');

        //Update new tests
        for ($i = 0; $i < count($test); $i++) {
            //Save tests selected.
            //Save new tests to Request Details
            $request = new Request_details();
            $request->test_id = $test[$i];
            $request->request_id = $reqid;
            $request->save();

            //Save new tests to Request Details
            $cb = new Coa_body();
            $cb->test_id = $test[$i];
            $cb->labref = $reqid;
            $cb->save();

            //Check to see department the test belongs to
            $dept = Tests::getDepartments($test[$i]);

            //Push department of test into an array depts
            //$depts[] = $dept[0]['Department'];
        }


        //If NDQD number is changed, recreate worksheet and coa folders
        if ($old_reqid != $reqid) {
            $this->create_sample_folder($reqid);
            $this->create_coa_folder($reqid);
        }

        $this->save_client();
    }

    public function edit_save_clients($id) {

        if (is_null($_POST)) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Data was not posted.'
            ));
        } else {
            echo json_encode(array(
                'status' => 'success',
                'message' => 'Data added successfully',
                'array' => json_encode($_POST)
            ));
        }
        $quotation = $this->input->post("quotation");
        $priority = $this->input->post("priority");
        //$id = $this->input->post("labref_id");
        $old_reqid = $this->input->post("lab_ref_no1");
        $reqid = $this->input->post("lab_ref_no");
        $dateformat = $this->input->post("dateformat");
        $test = $this->input->post("test");
        $cid = $this->input->post("client_id");
        $product_name = $this->input->post("product_name");
        $dosage_form = $this->input->post("dosage_form");
        $manufacturer_name = $this->input->post("manufacturer_name");
        $manufacturer_address = $this->input->post("manufacturer_address");
        $batch_no = $this->input->post("batch_no");
        $expiry_date = $this->input->post("e_date");
        $manufacture_date = $this->input->post("m_date");
        $label_claim = $this->input->post("label_claim");
        $active_ingredients = $this->input->post("active_ingredients");
        $quantity = $this->input->post("quantity");
        $applicant_reference_number = $this->input->post("applicant_reference_number");
        //$client_number = $labref;
        $designator_name = $this->input->post("designator_name");
        $designation = $this->input->post("designation");
        // $designation_date = $this->input->post("designation_date");



        $urgency = $this->input->post("urgency");
        $edit_notes = $this->input->post("edit_notes");
        $country_of_origin = $this->input->post("country_of_origin");
        $product_lic_no = $this->input->post("product_lic_no");
        $presentation = $this->input->post("presentation");
        $description = $this->input->post("description");
        $clientsampleref = $this->input->post("client_ref_no");
        $packaging = $this->input->post("packaging");

        //Request Update Array
        $request_update_array = array(
            'request_id' => $reqid,
            'sample_qty' => $quantity,
            'priority' => $priority,
            'product_name' => $product_name,
            'label_claim' => $label_claim,
            'packaging' => $packaging,
            'active_ing' => $active_ingredients,
            'Dosage_form' => $dosage_form,
            'Manufacturer_name' => $manufacturer_name,
            'Manufacturer_add' => $manufacturer_address,
            'Batch_no' => $batch_no,
            'exp_date' => $expiry_date,
            'Manufacture_date' => $manufacture_date,
            'Designator_Name' => $designator_name,
            //'Designation_date' => $designation_date,
            'Urgency' => $urgency,
            'edit_notes' => $edit_notes,
            'clientsampleref' => $clientsampleref,
            'quotation' => $quotation
        );


        //Update main request table
        $this->db->where(array('id' => $id));
        $this->db->update('request', $request_update_array);


        //Delete existing tests from Request Details
        $this->db->where(array('request_id' => $old_reqid));
        $this->db->delete('request_details');

        //Delete existing tests from Coa Body
        $this->db->where(array('labref' => $old_reqid));
        $this->db->delete('coa_body');

        //Update new tests
        for ($i = 0; $i < count($test); $i++) {
            //Save tests selected.
            //Save new tests to Request Details
            $request = new Request_details();
            $request->test_id = $test[$i];
            $request->request_id = $reqid;
           // $request->save();

            //Save new tests to Request Details
            $cb = new Coa_body();
            $cb->test_id = $test[$i];
            $cb->labref = $reqid;
            //$cb->save();

            //Check to see department the test belongs to
            //$dept = Tests::getDepartments($test[$i]);
            //Push department of test into an array depts
            //$depts[] = $dept[0]['Department'];
        }


        //If NDQD number is changed, recreate worksheet and coa folders
        if ($old_reqid != $reqid) {
            $this->create_sample_folder($reqid);
            $this->create_coa_folder($reqid);
        }

        $this->update_sample($id);
    }

    function update_sample($id) {
        $client_update_array = array(
            't' => 0
        );

        $this->db->where('id', $id)->update('request', $client_update_array);
    }

    function save_client() {
        $client_name = $this->input->post("client_name");
        $client_id = $this->input->post("client_id");
        $client_address = $this->input->post("client_address");
        $clientT = $this->input->post("clientT");
        $client_email = $this->input->post("client_email");
        $contact_person = $this->input->post("contact_person");
        $contact_phone = $this->input->post("contact_phone");

        $client_update_array = array(
            'name' => $client_name,
            'address' => $client_address,
            'client_type' => $clientT,
            'email' => $client_email,
            'contact_person' => $contact_person,
            'contact_phone' => $contact_phone
        );

        $this->db->where('id', $client_id)->update('clients', $client_update_array);
    }

    public function oos_requests_list() {
        $data = $this->db->where('oos', 1)->get('request')->result();
        foreach ($data as $r):
            $result[] = $r;
        endforeach;
        echo json_encode($result);
    }

    public function getTestName() {
        $test_id = $this->uri->segment(3);
        $test = Tests::getTestName3($test_id);
        foreach ($test as $t) {
            $data[] = $t;
        }
        echo json_encode($data);
    }

    public function save() {


        if (is_null($_POST)) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Data was not posted.'
            ));
        } else {
            echo json_encode(array(
                'status' => 'success',
                'message' => 'Data added successfully',
                'array' => json_encode($_POST)
            ));
        }


        $dsgntr = $this->input->post("dsgntr");
        $dsgntn = $this->input->post("dsgntn");
        $moa = $this->input->post("moa");
        $crs = $this->input->post("crs");
        $the_type = $this->input->post("the_type");
        $the_year = $this->input->post("the_year");
        $the_month = $this->input->post("the_month");
        $the_number = $this->input->post("the_number");
        $full_nmuber = "NDQ" . $the_type . $the_year . $the_month . $the_number;

        $dateformat = $this->input->post("dateformat");
        $test = $this->input->post("test");
        $cid = $this->input->post("clientid");
        $client_agent = $this->input->post("client_agent");


        if (!empty($cid)) {
            $clientid = $this->input->post("clientid");

            //Get Client Agent Id of Client
            $c_a_i = Clients::getClientAgentId($clientid);
            $c_a_id = $c_a_i[0]['client_agent_id'];

            if ($client_agent != $c_a_id) {

                //Client Agent Update Array
                $client_agent_update = array('client_agent_id' => $client_agent);

                //Update Client Agent in Clients
                $this->db->where('id', $clientid);
                $this->db->update('clients', $client_agent_update);
            }
        } else {
            $cid = Clients::getLastId();
            $clientid = $cid[0]['max'] + 1;
        }

        $priority = $this->input->post("priority");
        $product_name = $this->input->post("product_name");
        $dosage_form = $this->input->post("dosage_form");
        $manufacturer_name = $this->input->post("manufacturer_name");
        $manufacturer_address = $this->input->post("manufacturer_address");
        $batch_no = $this->input->post("batch_no");
        // if ($dateformat == 'dmy') {
        // $expiry_date = $this->input->post("date_e");
        //$manufacture_date = $this->input->post("date_m");
        //  } else if ($dateformat == 'my') {
        //$ed = "31 " . $this->input->post("e_date");
        //$md = "01 " . $this->input->post("m_date");
        $expiry_date = $this->input->post("e_date");
        $manufacture_date = $this->input->post("m_date");
        //}
        $label_claim = $this->input->post("label_claim");
        $active_ingredients = $this->input->post("active_ingredients");
        $quantity = $this->input->post("quantity");
        $applicant_reference_number = $this->input->post("applicant_reference_number");
        $client_number = $full_nmuber;
        $designator_name = $this->input->post("designator_name");
        $designation = $this->input->post("designation");
        $designation_date = $this->input->post("designation_date");
        if ($designation_date == '') {
            $designation_date = date('Y-m-d');
        } else {
            $designation_date = $this->input->post("designation_date");
        }
        $urgency = $this->input->post("urgency");
        $edit_notes = $this->input->post("edit_notes");
        $country_of_origin = $this->input->post("country_of_origin");
        $product_lic_no = $this->input->post("product_lic_no");
        $presentation = $this->input->post("presentation");
        $description = $this->input->post("description");
        $clientsampleref = $this->input->post("applicant_reference_number");
        $packaging = $this->input->post("packaging");
        $client_agent_id = $this->input->post("client_agent");
        $currency = $this->input->post("currency");

        //$full_details_status = 0;
        //Loop through tests, saving each in row of its own in request_details table
        for ($i = 0; $i < count($test); $i++) {
            //Save tests selected.

            $request = new Request_details();
            $request->test_id = $test[$i];
            $request->request_id = $full_nmuber;
            $request->save();

            //Check to see department the test belongs to
            $dept = Tests::getDepartments($test[$i]);

            //Push department of test into an array depts
            $depts[] = $dept[0]['Department'];
        }

        //filter array to include only unique departments
        $dpts = array_unique($depts);

        //Check to see if tests selected are in more than 1 department - this determines the split status
        //If length of the depts array is greater than one, then Sample is split else set to 0, sample is not split.

        if (count($dpts) > 1) {
            $split_status = "1";
            foreach ($dpts as $key => $value) {
                $split = new Split();
                $split->request_id = $full_nmuber;
                $split->dept = $value;
                $split->save();
            }
        } else {
            $split_status = "0";
            foreach ($dpts as $key => $value) {
                $split = new Split();
                $split->request_id = $full_nmuber;
                $split->dept = $value;
                $split->save();
            }
        }

        $request = new Request();
        $request->dsgntn = $dsgntn;
        $request->dsgntr = $dsgntr;
        $request->moa = $moa;
        $request->crs = $crs;
        $request->clientsampleref = $clientsampleref;
        $request->dateformat = $dateformat;
        $request->priority = $priority;
        //$request->description = $description;
        $request->presentation = $presentation;
        $request->product_lic_no = $product_lic_no;
        $request->country_of_origin = $country_of_origin;
        $request->client_id = $clientid;
        $request->product_name = strtoupper($product_name);
        $request->Dosage_Form = $dosage_form;
        $request->Manufacturer_Name = $manufacturer_name;
        $request->Manufacturer_add = $manufacturer_address;
        $request->Batch_no = $batch_no;
        //$request -> full_details_status = $full_details_status;
        $request->exp_date = $expiry_date;
        $request->Manufacture_date = $manufacture_date;
        $request->label_claim = $label_claim;
        $request->Urgency = $urgency;
        $request->active_ing = $active_ingredients;
        $request->sample_qty = $quantity;
        $request->sample_quantity_bup = $quantity;
        $request->request_id = $full_nmuber;
        $request->Designator_Name = $designator_name;
        $request->Designation = $designation;
        $request->Designation_date = $designation_date;
        $request->Designation_date_1 = $designation_date;
        $request->edit_notes = $edit_notes;
        $request->packaging = $packaging;
        $request->split_status = $split_status;
        $request->client_agent_id = $client_agent_id;
        $request->save();
        $this->run_overal_micro();
        $this->create_sample_folder($client_number);
        $this->create_coa_folder($client_number);
        $this->addSampleTrackingInformation($clientid, $full_nmuber);


        for ($i = 0; $i < count($test); $i++) {
            $coa = new Coa_body();
            $coa->test_id = $test[$i];
            $coa->labref = $client_number;
            $coa->save();
        }

        $no = "  ";
        $dr = new Dispatch_register();
        $dr->client_id = $clientid;
        $dr->date = date('y-m-d');
        $dr->cert_no = "CAN" . "/" . $no . "/" . date('y');
        $dr->request_id = $client_number;
        $dr->invoice_no = $no . "/" . date('y');
        $dr->save();

        for ($i = 0; $i < count($test); $i++) {
            $test_charges = Tests::getCharges($test[$i]);
            $test_methods = Test_methods::getMethodsHydrated($test[$i]);
            //$method_charges = Test_methods_charges::getMethodCharge($test[$i]);
            $cb = new Client_billing();
            $cb->request_id = $client_number;
            $cb->client_id = $clientid;
            $cb->test_id = $test[$i];
            if (!empty($test_charges)) {
                $cb->test_charge = $test_charges[0]['Charge_' . strtolower($currency)];
                $tcharges[] = $test_charges[0]['Charge_' . strtolower($currency)];
            }
            //$coa -> total_test_charge = $test_charges[0]['charge'] + $method_charges[0]['charge'];
            $cb->currency = $currency;
            $cb->save();
        }

        $this->makeEntryInPaymentTable($clientid);
        $this->saveClientAsUser($clientid);
        //$this-> output->enable_profiler();
    }

    public function makeEntryInPaymentTable($ci) {
        //Make Entry in Payments table
        $cl = new Payments();
        $cl->client_id = $ci;
        $cl->save();
    }

    public function markSelectedForReanalysis() {

        //Get array of all uri segments
        $all_uri_segments = $this->uri->segment_array();

        //Loop through all segments, insert NDQD segments into NDQD array 
        for ($i = 0; $i <= count($all_uri_segments); $i++) {
            if ($i > 2) {

                //Get reanalysis count status of sample, and client id
                $r_count = Request::getReanalysisCount($all_uri_segments[$i]);
                $r_count_initial = $r_count[0]['r_count'];
                $rc = $r_count_initial + 1;

                //Push NDQD number into a variable
                $old_reqid = $all_uri_segments[$i];

                if ($r_count_initial < 1) {

                    //Client Id
                    $ci = $r_count[0]['client_id'];

                    //New Request Id with 'r' count appended
                    $new_reqid = $old_reqid . 'r' . $rc;

                    //Set r_count status to 1
                    $r_count_update = array('r_count' => $rc);
                    $this->db->where('request_id', $old_reqid);
                    $this->db->update('request', $r_count_update);

                    //Tables and corresponding main columns affected at entry of request 
                    $tablesAndColumns = array(
                        'request' => 'request_id',
                        'request_details' => 'request_id',
                        'split' => 'request_id',
                        'coa_body' => 'labref',
                        'dispatch_register' => 'request_id',
                        'client_billing' => 'request_id',
                        'microbiology_tracking' => 'labref'
                    );

                    //Copy paste rows in above tables but with 'r' appended to the NDQD number.
                    foreach ($tablesAndColumns as $table => $column) {
                        $this->copyPasteRow($table, $column, $old_reqid, $new_reqid);
                    }

                    //Run payment, tracking, worksheetcreation
                    $this->makeEntryInPaymentTable($ci);
                    $this->create_sample_folder($new_reqid);
                    $this->create_coa_folder($new_reqid);
                    $this->addSampleTrackingInformation($ci, $new_reqid);
                } else {
                    echo json_encode(
                            array(
                                'status' => 'error',
                                'message' => $old_reqid . ' has already been re-analysed ' . $r_count_initial . ' time(s).'
                            )
                    );
                }
            }
        }
    }

    public function copyPasteRow($t, $c, $o, $n) {

        //Save name of dynamic temp table in variale
        $tmp_table = $t . '_tmp2';

        //Select all from main table
        $select_all = "SELECT * FROM $t WHERE $c = '$o'";
        $sa = $this->db->query($select_all)->result_array();
        echo count($sa);

        //Select Max id
        $mi = $this->db->query("SELECT MAX(id) as max_id FROM $t;")->result_array();
        $max_id = $mi[0]['max_id'];
        var_dump($max_id);

        //Select First id
        $fi = $this->db->query("SELECT id as first_id FROM $t WHERE $c = '$o' ORDER BY id ASC LIMIT 1;")->result_array();
        $first_id = $fi[0]['first_id'];

        //Create temp table
        $create_temp_table = "CREATE TEMPORARY TABLE $tmp_table " . $select_all;

        //Update unique columns
        $update_unique_field = "UPDATE $tmp_table SET $c ='$n' WHERE $c = '$o';";
        $update_id = "UPDATE $tmp_table SET id =((SELECT MAX(id) FROM $t) + 1) WHERE $c = '$n';";

        //Insert into table
        $insert_into_table = "INSERT INTO $t SELECT * FROM $tmp_table WHERE $c = '$n';";

        $this->db->trans_start();
        $this->db->query($create_temp_table);
        $this->db->query($update_unique_field);
        for ($i = 0; $i < count($sa); $i++) {
            $new_id = $max_id + 1 + $i;
            $old_id = $first_id + $i;
            $this->db->query("UPDATE $tmp_table SET id = '$new_id' WHERE id = '$old_id';");
        }
        $this->db->query($insert_into_table);
        $this->db->query("DROP table $tmp_table");
        $this->db->trans_complete();

        $this->output->enable_profiler(TRUE);
    }

    public function testCh() {
        $t = $this->uri->segment(3);
        $test_charges = Tests_charges::getTestCharge($t);
        if (empty($test_charges)) {
            $test_methods = Test_methods::getMethodsHydrated($t);
        }
        var_dump($test_methods[0]['charge']);
    }

    public function checkUserExistsThenSendorError() {
        $user_is = $this->input->post('clientid');
        $this->db->select('id');
        $this->db->where('id', $user_is);
        $query = $this->db->get('clients');
        if ($query->num_rows() > 0) {
            return '1';
        } else {
            return '0';
        }
    }

    public function setComponents() {
        if (is_null($_POST)) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Data was not posted.'
            ));
        } else {
            echo json_encode(array(
                'status' => 'success',
                'message' => 'Data added successfully',
                'array' => json_encode($_POST)
            ));
        }

        $multicomponent_status = $this->input->post("multicomponent");
        $components = $this->input->post("component");
        $component_volume1 = $this->input->post("volume1");
        $component_volume1 = $this->input->post("volume1");
        $component_volume2 = $this->input->post("volume2");
        $component_unit1 = $this->input->post("unit1");
        $component_unit2 = $this->input->post("unit2");

        $multistage_status = $this->input->post("multistage");
        $stages_no = $this->input->post("multistage_no");
        //$this->output->enable_profiler();
        //$testid = $this->uri->segment(4);
        $request_id = $this->uri->segment(3);
        //$dissolution_id = '2';

        $stage = new Stages();
        //$stage->test_id = $testid;
        $stage->stages_no = $stages_no;
        $stage->stage_status = $multistage_status;
        $stage->request_id = $request_id;
        $stage->save();

        //Update Multicomponent and Multistage Statuses in the Client Billing Table
        if (empty($component_volume1)) {
            $component_status = 0;
        } else {
            $component_status = 1;
        }

        $component_status_updateArray = array('component_status' => $component_status);
        $cb_cstatus_updateArray = array('component_status' => $multicomponent_status);
        $cb_mstatus_updateArray = array('stage_status' => $multistage_status);
        $dissolution_where_array = array('request_id' => $request_id);
        $component_where_array = array('lab_ref_no' => $request_id);

        $this->db->where($component_where_array);
        $this->db->update('sample_issuance', $component_status_updateArray);

        $this->db->where('request_id', $request_id);
        $this->db->update('client_billing', $cb_cstatus_updateArray);

        $this->db->where($dissolution_where_array);
        $this->db->update('client_billing', $cb_mstatus_updateArray);

        //if($multicomponent_status == '1'){
        for ($i = 0; $i < count($components); $i++) {
            $component = new Components();
            $component->name = $components[$i];

            //If array is not empty then assign to model variables
            if (!empty($component_volume1)) {
                $component->volume1 = $component_volume1[$i];
                $component->volume2 = $component_volume2[$i];
                $component->unit1 = $component_unit1[$i];
                $component->unit2 = $component_unit2[$i];
            }

            $component->request_id = $request_id;
            $component->save();
        }

        if ($multicomponent_status == 1) {
            //Get tests affected by multicomponent quality of sample
            //Condition so that if single, not to enter multiple components	
            $multi_tests = $this->getMulticTests();
        } else {
            $multi_tests = array(0);
        }

        for ($j = 0; $j < count($multi_tests); $j++) {
            for ($i = 0; $i < count($components); $i++) {
                $component = new Invoice_components();
                $component->component = $components[$i];
                $component->test_id = $multi_tests[$j];
                $component->request_id = $request_id;
                $component->save();
            }
        }

        //Update multicomponent status in Sample Issuance Table
        $m_status_array = array('multicomponent_status' => $multicomponent_status);

        //Update Sample Issuance with status on whether its multicomponent / not.
        $this->db->where($component_where_array);
        $this->db->update('sample_issuance', $m_status_array);



        //Loop through array of components, saving each to own row in quotations_components table
    }

    public function showComponents() {

        $data['test_name'] = $this->uri->segment(5);
        $data['reqid'] = $reqid = $this->uri->segment(3);
        $client = Request::getClientId($reqid);
        $data['client_id'] = $client[0]['client_id'];
        $data['test_id'] = $this->uri->segment(4);
        $data['component_status'] = $this->uri->segment(5);
        $data['components'] = Components::getComponents($data['reqid']);
        $data['last_component'] = Components::getLastComponent($data['reqid'], $data['test_id']);
        $data['methods'] = Test_methods::getMethods($data['test_id']);
        $data['content_view'] = "componentsWizard_v";
        $this->load->view('template1', $data);
    }

    public function updateComponents() {

        if (is_null($_POST)) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Data was not posted.'
            ));
        } else {
            echo json_encode(array(
                'status' => 'success',
                'message' => 'Data added successfully',
                'array' => json_encode($_POST)
            ));
        }

        $componentName = $this->input->post("component_name");
        $methodName = $this->input->post("name");
        $methodId = $this->input->post("id");
        $reqid = $this->uri->segment(3);
        $test_id = $this->uri->segment(4);

        $componentsUpdateArray = array(
            'method_id' => $methodId,
            'method_name' => $methodName
        );

        $this->db->where('request_id', $reqid);
        $this->db->insert('components', $componentsUpdateArray);
    }

    public function getMulticTests2() {
        //Get tests that for multicomponent , can take different methods for each component
        $mc_tests = Tests::getMcTests();

        //Simplify multidimensional array
        $mct = array();
        foreach ($mc_tests as $mc) {
            array_push($mct, $mc['id']);
        }

        //Return simplified array
        return $mct;
    }

    public function updateMethods() {

        if (is_null($_POST)) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Data was not posted.'
            ));
        } else {
            echo json_encode(array(
                'status' => 'success',
                'message' => 'Data added successfully',
                'array' => json_encode($_POST)
            ));
        }

        $reqid = $this->uri->segment(3);
        $test_id = $this->uri->segment(4);
        $analyst_id = $this->uri->segment(5);
        $component_ids = $this->input->post("component_ids");
        $method_status = "1";
        $chroma_status = "1";
        $data['components'] = Components::getComponents($reqid, $test_id);

        //Get limits
        $limits = $this->input->post("limits");

        //Save the limits
        $lim = new Limits();
        $lim->request_id = $reqid;
        $lim->test_id = $test_id;
        $lim->analyst_id = $analyst_id;
        $lim->limits = $limits;
        $lim->save();

        //Get where array , limits array
        $rd_where_array = array('request_id' => $reqid, 'test_id' => $test_id);
        $limits_array = array('limits', $limits);

        //Update request details
        $this->db->where($rd_where_array);
        $this->db->update('request_details', array('limits' => $limits, 'analyst_id' => $analyst_id));

        //Get Currency
        $currency = Client_billing::getCurrency($reqid);
        $c = $currency[0]['currency'];

        $multi_tests = $this->getMulticTests2();

        //Get invoice components
        //$invoice_components  = Invoice_components::getInvoiceComponents($reqid);
        //Initialize array to hold method values separated by colon
        $methodsColonArray = array();



        for ($i = 0; $i < count($component_ids); $i++) {
            $post_index = "component" . $component_ids[$i];
            $method_name = $this->input->post($post_index);
            $method_charges = Test_methods::getMethodChargeHydrated($method_name, $test_id);
            if (empty($method_charges)) {
                $method_charge = '0';
                $method_id = '0';
            } else {
                $method_charge = $method_charges[0]['charge_' . strtolower($c)];
                $method_id = $method_charges[0]['id'];
            }

            //Push methods to array
            array_push($methodsColonArray, $method_name);


            $methodsUpdateArray2 = array(
                'method_name' => $this->input->post($post_index)
            );


            $m_array = array(
                'labref' => $reqid,
                'test_id' => $test_id
            );

            $m_array3 = array(
                'request_id' => $reqid,
                'test_id' => $test_id
            );

            $clientBillingWhereArray = array(
                'test_id' => $test_id,
                'request_id' => $reqid
            );

            $clientBillingUpdateArray = array(
                'method_id' => $method_id,
                'method_charge' => $method_charge
            );



            $this->db->where($m_array3);
            $this->db->update('components', $methodsUpdateArray2);

            if (count($component_ids) < 2) {
                $this->db->where($clientBillingWhereArray);
                $this->db->update('client_billing', $clientBillingUpdateArray);
            }

            if (in_array($test_id, $multi_tests)) {

                //Get Component Name
                $c_name = Components::getComponentName($component_ids[$i]);
                $component_name = $c_name[0]['name'];
                $invoice_component_where_array = array(
                    'component' => $component_name,
                    'test_id' => $test_id,
                    'request_id' => $reqid
                );

                $this->db->where($invoice_component_where_array);
                $this->db->update('invoice_components', $clientBillingUpdateArray);
            }
        }

        //If HPLC not a method selected, set chroma status to one, so as not to pop chroma conditions window
        if (!in_array('HPLC', $methodsColonArray)) {


            $si_where_array = array(
                'lab_ref_no' => $reqid,
                'test_id' => $test_id
            );

            $m_status_array = array('chroma_status' => $chroma_status);
            $this->db->where($si_where_array);
            $this->db->update('sample_issuance', $m_status_array);
        }

        //Concatenate array elements with colon as separator and assign result to new string variable
        $implodedColonArray = implode(":", $methodsColonArray);

        //Update method column with newly generated string
        $methodsUpdateArray4 = array(
            'method' => $implodedColonArray
        );

        //Set variable arrays to include update where parameters
        $m_array2 = array(
            'lab_ref_no' => $reqid,
            'test_id' => $test_id
        );
        $m_array3 = array(
            'labref' => $reqid,
            'test_id' => $test_id
        );

        //Update COA Body table.
        $this->db->where($m_array3);
        $this->db->update('coa_body', $methodsUpdateArray4);

        $m_status_array = array('method_status' => $method_status);

        $this->db->where($m_array2);
        $this->db->update('sample_issuance', $m_status_array);
    }

    public function quotation() {

        $reqid = $this->uri->segment(4);
        $methodIdArray = Request_test_methods::getMethods($reqid);
        $testIdArray = Request_details::getTests($reqid);

        foreach ($methodIdArray as $methodArray) {
            $data['method_charges'][] = Test_methods_charges::getCharges($methodArray['test_id']);
        }

        foreach ($testIdArray as $testArray) {
            $data['test_charges'][] = Tests::getCharges($testArray['test_id']);
        }

        /* for($i = 0; $i < count($testIdArray); $i++){
          $data['test_charges'][] = Tests_charges::getCharges($testIdArray[$i]['id']);
          } */

        //var_dump($testIdArray);
        $data['settings_view'] = 'invoice_v';
        $this->base_params($data);
    }

    public function getMethodCharges() {
        $mid = $this->uri->segment(3);
        $data['mcharges'] = Test_methods_charges::getMethodCharges($mid);
        $data['settings_view'] = "mcharges_v";
    }

    public function update() {


        //Variables storing the analysis request variables
        //variable storing the class instance

        $tests = $this->input->post("test");
        $clientid = $this->input->post("client_id");
        $product_name = $this->input->post("product_name");
        $dosage_form = $this->input->post("dosage_form");
        $manufacturer_name = $this->input->post("manufacturer_name");
        $manufacturer_address = $this->input->post("manufacturer_address");
        $batch_no = $this->input->post("batch_no");
        $dateformat = $this->input->post("dateformat");
        $expiry_date = $this->input->post("date_e");
        $manufacture_date = $this->input->post("date_m");
        $label_claim = $this->input->post("label_claim");
        $active_ingredients = $this->input->post("active_ingredients");
        $quantity = $this->input->post("quantity");
        $applicant_reference_number = $this->input->post("client_ref_no");
        $client_number = $this->input->post("lab_ref_no");
        $designator_name = $this->input->post("designator_name");
        $designation = $this->input->post("designation");
        //$designation_date = $this->input->post("designation_date");
        $edit_notes = $this->input->post("edit_notes");
        $country_of_origin = $this->input->post("country_of_origin");
        $product_lic_no = $this->input->post("product_lic_no");
        $presentation = $this->input->post("presentation");
        $description = $this->input->post("description");
        $tests_issued = Sample_issuance::getIssuedTests2($client_number);

        //$client_id =  $this -> input -> post("client_id");
        //Variables hold client information
        $client_name = $this->input->post("client_name");
        $client_address = $this->input->post("client_address");
        $client_type = $this->input->post("clientT");
        $contact_person = $this->input->post("contact_person");
        $contact_phone = $this->input->post("contact_phone");
        $client_ref_no = $this->input->post("client_ref_no");

        //Analysis update array holds above variables , later to
        //be passed to update() function (CodeIgniter.)

        $analysis_update_array = array(
            'client_id' => $clientid,
            'product_name' => $product_name,
            'Dosage_form' => $dosage_form,
            'Manufacturer_Name' => $manufacturer_name,
            'Manufacturer_add' => $manufacturer_address,
            'Batch_no' => $batch_no,
            'dateformat' => $dateformat,
            'exp_date' => $expiry_date,
            'Manufacture_date' => $manufacture_date,
            'label_claim' => $label_claim,
            'active_ing' => $active_ingredients,
            'sample_qty' => $quantity,
            'clientsampleref' => $applicant_reference_number,
            'request_id' => $client_number,
            //'Designation_date' => $designation_date,
            'edit_notes' => $edit_notes,
            'country_of_origin' => $country_of_origin,
            'product_lic_no' => $product_lic_no,
            'presentation' => $presentation,
            'description' => $description);

        //Array stores client details to be updated
        $client_update_array = array(
            'Name' => $client_name,
            'Address' => $client_address,
            'Client_type' => $client_type,
            'Contact_person' => $contact_person,
            'Contact_phone' => $contact_phone
        );

        //For loop , iterates through array of test ids, updating
        //each accordingly

        for ($i = 0; $i < count($tests); $i++) {

            foreach ($tests_issued as $tests_i) {
                if ($tests[$i] != $tests_i['Test_id']) {
                    $request = new Request_details();
                    $request->test_id = $test[$i];
                    $request->request_id = $client_number;
                    $request->save();
                }
            }
        }

        //Codeigniter where() and update() methods update tables accordingly.
        $this->db->where('request_id', $client_number);
        $this->db->update('request', $analysis_update_array);

        $this->db->where('clientid', $clientid);
        $this->db->update('clients', $client_update_array);

        //User is redirected to the requests listing page.
        redirect("request_management/listing");
    }

    public function edit_history() {
        $reqid = $this->uri->segment(3);
        $data['title'] = "Requests Edit History";
        $data['settings_view'] = "requests_edit_history_v";
        $data['info'] = Request::getHistory($reqid);
        //$data['requestInformation'] = $requestInformation;
        $this->base_params($data);
    }

    public function requests($id) {
        $data['title'] = "Request Information";
        $data['settings_view'] = "requests_v";
        $requestInformation = Request::getRequest($id);
        $data['requestInformation'] = $requestInformation;
        $this->base_params($data);
    }

    public function create_sample_folder($labref) {
        $workbooks = "Workbooks";
        if (is_dir($workbooks)) {
            mkdir($workbooks . "/" . $labref, 0777, true);
            $this->create_workbook($labref);
        }
    }

    function checkIfMicroPresence() {
        $tests = $this->input->post('test');
        for ($i = 0; $i < count($tests); $i++) {
            $testid[] = $tests[$i];
        }
        foreach ($testid as $test):
            if ($test == '8' || $test == '9' || $test == '10' || $test == '14' || $test == '15' || $test == '49' || $test == '50') {
                $data = '1';
            } else {
                $data = '0';
            }
        endforeach;

        return $data;
    }

    public function registerMicroNumber() {
        $the_type = $this->input->post("the_type");
        $the_year = $this->input->post("the_year");
        $the_month = $this->input->post("the_month");
        $the_number = $this->input->post("the_number");
        $labref = "NDQ" . $the_type . $the_year . $the_month . $the_number;
        $year = date('Y');
        $data = $this->db->select_max('number')->where('year', $year)->get('microbiology_tracking')->result();
        if (\is_null($data[0]->number)) {
            $this->db->insert('microbiology_tracking', array('labref' => $labref, 'year' => $year, 'number' => 001));
        } else {
            $this->db->insert('microbiology_tracking', array('labref' => $labref, 'year' => $year, 'number' => str_pad($data[0]->number + 1, 3, '0', STR_PAD_LEFT)));
        }
    }

    function run_overal_micro() {
        $determiner = $this->checkIfMicroPresence();
        if ($determiner == '1') {
            $this->registerMicroNumber();
        } else {
            //echo 'No Microbiology Test Found';
        }
    }

    public function create_workbook($labref) {
        $workbooks = "Workbooks";
        $target = "original_workbook/Template1.xlsx";
        $destination = "Workbooks/" . $labref . "/" . $labref . ".xlsx";
        if (is_dir($workbooks . "/" . $labref)) {
            copy($target, $destination);
        }
        //redirect("request_management/listing");
    }

    public function add_sample_to_priority_table() {
        $the_type = $this->input->post("the_type");
        $the_year = $this->input->post("the_year");
        $the_month = $this->input->post("the_month");
        $the_number = $this->input->post("the_number");
        $full_nmuber = "NDQ" . $the_type . $the_year . $the_month . $the_number;
        $data = array(
            'labref' => $full_nmuber,
            'priority' => 'High'
        );
        $this->db->insert('priority_table', $data);
    }

    public function create_coa_folder($labref) {
        $certificates = "certificates";
        if (is_dir($certificates)) {
            mkdir($certificates . "/" . $labref, 0777, true);
            $this->create_coa($labref);
        }
    }

    public function create_coa($labref) {
        $certificates = "certificates";
        $target2 = "original_coa/coa_template.xlsx";
        $destination2 = "certificates/" . $labref . "/" . $labref . "_COA.xlsx";
        if (is_dir($certificates . "/" . $labref)) {
            copy($target2, $destination2);
        }
        //redirect("request_management/listing");
    }

    public function getUsersInfo() {
        $user_id = $this->session->userdata('user_id');
        $this->db->select('fname,lname');
        $this->db->where('id', $user_id);
        $query = $this->db->get('user');
        return $result = $query->result();
    }

    function addSampleTrackingInformation($clientid, $reqid) {
        $the_type = $this->input->post("the_type");
        $the_year = $this->input->post("the_year");
        $the_month = $this->input->post("the_month");
        $the_number = $this->input->post("the_number");
        $full_nmuber = $reqid;
        //$full_nmuber = "NDQ" . $the_type . $the_year . $the_month . $the_number;
        $userInfo = $this->getUsersInfo();
        $client = $this->input->post("client_name");
        $activity = 'Samples Recieving';
        $labref = $full_nmuber;
        $names = $userInfo[0]->fname . " " . $userInfo[0]->lname;
        $from = $client . '- Client';
        $to = $names . '- Documentation';
        $date = date('d-M-Y H:i:s');

        $array_data = array(
            'labref' => $labref,
            'client_id' => $clientid,
            'activity' => $activity,
            'from' => $from,
            'to' => $to,
            'date_added' => $date,
            'state' => '1',
            'current_location' => 'Documentation'
        );
        $this->db->insert('worksheet_tracking', $array_data);
    }

    function get_report($tid, $from, $to) {
        return $this->db->query("SELECT r.request_id, r.product_name, r.designation_date, t.id
FROM request r, request_details rd, tests t
WHERE r.request_id = rd.request_id
AND rd.test_id = t.id
AND r.designation_date BETWEEN '$from' AND '$to
AND rd.test_id ='$tid'
ORDER BY r.designation_date ASC")->result();
    }

    function test_per_sample_report($from, $to, $tid) {
        //$this->load->view('');
        $css = "<!doctype><html><head><title>Samples Per Test Report between  $from and $to</title>";
        $css .= '<style type="text/css">
          .tg{width:900px;};
.tg  {border-collapse:collapse;border-spacing:0;border-color:#bbb;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#bbb;color:#594F4F;background-color:#E0FFEB;}
.tg th{font-family:Arial, sans-serif;font-size:30px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#bbb;color:#493F3F;background-color:#9DE0AD;}
.tg .tg-ugh9{background-color:#C2FFD6; width:300px;}
.tg .tg-031e .e12{background-color:#C2FFD6; width:300px;}
.he{ font-weight: bold;}
p{width:100%;
height: 5px;}
</style>


';


        $css .= '</head><body>';
        $css .= "<p><center><< <a href=" . base_url() . 'request_management/' . ">Back</a>   <strong><u>Samples Per Test Report between $from and $to </u></strong></center></p>";

        $query = $this->db->query("SELECT name  FROM tests WHERE id='$tid' ")->result();

        foreach ($query as $q):
            $css .= '<p></p>';
            $css .= '<center>';
            $css .= '<table class="tg"><tr><th class="tg-031e" colspan="4">' . $q->name . '</th></tr>';
            $css .= '<tr>
    <td class="tg-031e he">No:</td><td class="tg-031e he">Labreference No:</td><td class="tg-ugh9 he">Product Name</td><td class="tg-ugh9 he">Date Received</td></tr>';
            $query1 = $this->db->query("SELECT r.request_id, r.product_name, r.designation_date, t.id
FROM request r, request_details rd, tests t
WHERE r.request_id = rd.request_id
AND rd.test_id = t.id
AND r.designation_date BETWEEN '$from' AND '$to'
AND rd.test_id ='$tid'
ORDER BY r.designation_date ASC")->result();
            $i = 1;
            foreach ($query1 as $q1):
                $css .= ' <tr>
       <td class="tg-031e">' . $i . '</td>
    <td class="tg-031e">' . $q1->request_id . '</td>
	<td class="tg-031e">' . $q1->product_name . '</td>'
                        . ' <td class="tg-031e e12">' . $q1->designation_date . '</td></tr>';
                $i++;
            endforeach;

            $css .= '</table>';
            $css .= '</center></body></html>';
        endforeach;
        echo $css;
    }

    function dateToWords($date) {
        $dateTime = new DateTime($date);
        return $dateTime->format('jS M. Y');
    }

    function days_taken_sample_report($start, $end) {
        //$this->load->view('');
        $css = "<!doctype><html><head><title>Days Sample has taken between  ($start and $end) from receiving to COA Release</title>";
        $css .= '<style type="text/css">
          .tg{width:900px;};
.tg  {border-collapse:collapse;border-spacing:0;border-color:#bbb;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#bbb;color:#594F4F;background-color:#E0FFEB;}
.tg th{font-family:Arial, sans-serif;font-size:30px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#bbb;color:#493F3F;background-color:#9DE0AD;}
.tg .tg-ugh9{background-color:#C2FFD6; width:300px;}
.tg .tg-031e .e12{background-color:#C2FFD6; width:300px;}
.he{ font-weight: bold;}
p{width:100%;
height: 5px;}
</style>


';


        $css .= '</head><body>';
        $css .= "<p><center><< <a href=" . base_url() . 'request_management/' . ">Back</a>   <strong><u>General Sample period between $start and $end </u></strong></center></p>";



        $css .= '<p></p>';
        $css .= '<center>';
        $css .= '<table class="tg"><tr><th class="tg-031e" colspan="9">[' . $this->dateToWords($start) . ' to ' . $this->dateToWords($end) . ']</th></tr>';
        $css .= '<tr>
    <td class="tg-031e he">No:</td><td class="tg-031e he">Labreference No:</td><td class="tg-ugh9 he">Product Name</td><td class="tg-ugh9 he">Date received</td><td class="tg-ugh9 he">Date Issued</td><td class="tg-ugh9 he">Date Released</td><td class="tg-ugh9 he">Days Before Assignment</td><td class="tg-ugh9 he">Processing Days pre COA</td><td class="tg-ugh9 he">Days in NQCL pre COA</td></tr>';
        $query1 = $this->db->query("SELECT t1.labref,r.product_name,
r.designation_date_1 AS Date_Received,
t1.date_added_1 AS Date_Released,
t2.date_added_1 AS Date_Issued,
DATEDIFF(t2.date_added_1,r.designation_date_1) as duration_before_issuing, 
DATEDIFF(t1.date_added_1,t2.date_added_1) as processing_duration, 
DATEDIFF(t1.date_added_1,r.designation_date_1) as duration_total 
FROM tracking_table t1 
INNER JOIN tracking_table t2
ON t1.labref=t2.labref
INNER JOIN request r 
ON t1.labref = r.request_id
WHERE t1.activity='Authorization of COA Release' 
AND t2.activity='Issuing'
AND t2.date_added_1 BETWEEN '$start' AND '$end'
ORDER BY duration_total ASC")->result();
        $i = 1;
        foreach ($query1 as $q1):
            $css .= ' <tr>
       <td class="tg-031e">' . $i . '</td>
    <td class="tg-031e">' . $q1->labref . '</td><td class="tg-031e">' . $q1->product_name . '</td>
	<td class="tg-031e">' . $q1->Date_Received . '</td>
	<td class="tg-031e">' . $q1->Date_Issued . '</td>
	<td class="tg-031e">' . $q1->Date_Released . '</td>'
                    . ' <td class="tg-031e e12">' . $q1->duration_before_issuing . '</td>'
                    . '<td class="tg-031e e12">' . $q1->processing_duration . '</td>'
                    . '<td class="tg-031e e12">' . $q1->duration_total . '</td></tr>';
            $i++;
        endforeach;

        $css .= '</table>';
        $css .= '</center></body></html>';

        echo $css;





        $css1 = '';
        $css1 .= '<p></p>';
        $css1 .= '<center>';
        $css1 .= '<table class="tg"><tr><th class="tg-031e" colspan="3"> Activity Summary from  [' . $this->dateToWords($start) . ' to ' . $this->dateToWords($end) . ']</th></tr>';
        $css1 .= '<tr>
    <td class="tg-031e he">No:</td><td class="tg-031e he">Activity:</td><td class="tg-ugh9 he">Count</td></tr>';
        $query2 = $this->db->query("SELECT activity, COUNT(*) count 
  FROM 
     ( SELECT x.* 
         FROM tracking_table x 
         JOIN 
            ( SELECT labref, MAX(id) max_id ,date_added_1
              FROM tracking_table 
              GROUP BY labref
            ) y 
           ON y.labref = x.labref 
          AND y.max_id = x.id
          AND y.date_added_1 BETWEEN '$start' AND '$end'
     ) n 
 GROUP BY activity;")->result();
        $j = 1;
        foreach ($query2 as $q1):
            $css1 .= ' <tr>
       <td class="tg-031e">' . $j . '</td>
    <td class="tg-031e">' . $q1->activity . '</td><td class="tg-031e">' . $q1->count . '</td>';
            $j++;
        endforeach;

        $css1 .= '</table>';
        $css1 .= '</center></body></html>';

        echo $css1;
    }

    function days_taken_supervisor($start, $end, $sid = '') {
        //$this->load->view('');
        $css = "<!doctype><html><head><title>Days Sample has taken between  ($start and $end) from receiving to COA Release</title>";
        $css .= '<style type="text/css">
          .tg{width:900px;};
.tg  {border-collapse:collapse;border-spacing:0;border-color:#bbb;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#bbb;color:#594F4F;background-color:#E0FFEB;}
.tg th{font-family:Arial, sans-serif;font-size:30px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#bbb;color:#493F3F;background-color:#9DE0AD;}
.tg .tg-ugh9{background-color:#C2FFD6; width:300px;}
.tg .tg-031e .e12{background-color:#C2FFD6; width:300px;}
.he{ font-weight: bold;}
p{width:100%;
height: 5px;}
</style>


';


        $css .= '</head><body>';
        $css .= "<p><center><< <a href=" . base_url() . 'request_management/' . ">Back</a>   <strong><u>Supervisor General Sample period between $start and $end </u></strong></center></p>";



        $css .= '<p></p>';
        $css .= '<center>';
        $css .= '<table class="tg"><tr><th class="tg-031e" colspan="11">[' . $this->dateToWords($start) . ' to ' . $this->dateToWords($end) . ']</th></tr>';
        $css .= '<tr>
    <td class="tg-031e he">No:</td><td class="tg-031e he">Labreference No:</td><td class="tg-ugh9 he">Product Name</td><td class="tg-ugh9 he">Date received</td><td class="tg-ugh9 he">Date Issued (Analyst)</td><td class="tg-ugh9 he">Date Returned(Analyst Supervisor)</td><td class="tg-ugh9 he">Date Returned(Supervisor Documentation)</td><td class="tg-ugh9 he">Days Before Assignment</td><td class="tg-ugh9 he">Days with Analyst</td><td class="tg-ugh9 he">Days with supervisor</td><td class="tg-ugh9 he">Supervisor</td></tr>';
        $query1 = $this->db->query("SELECT t1.id,t1.labref,r.product_name, t1.from_who,
r.designation_date_1 AS Date_Received,
t3.date_added_1 As Date_Issued,
t2.date_added_1 AS Analyst_Return_Date,
t1.date_added_1 AS Supervisor_return_Date,
DATEDIFF(t3.date_added_1,r.designation_date_1) as dbs, 
DATEDIFF(t2.date_added_1,t3.date_added_1) as dwa, 
DATEDIFF(t1.date_added_1,t2.date_added_1) as dws 
FROM tracking_table t1 
INNER JOIN tracking_table t2
ON t1.labref=t2.labref
INNER JOIN tracking_table t3
ON t1.labref=t3.labref
INNER JOIN request r 
ON t1.labref = r.request_id
WHERE t1.activity='Returning to Documentation' 
AND t2.activity='Returning to supervisor'
AND t3.activity='Issuing'
AND t2.date_added_1 BETWEEN '$start' AND '$end'
ORDER BY from_who ASC")->result();
        $i = 1;
        foreach ($query1 as $q1):
            $css .= ' <tr>
       <td class="tg-031e">' . $i . '</td>
    <td class="tg-031e">' . $q1->labref . '</td><td class="tg-031e">' . $q1->product_name . '</td>
	<td class="tg-031e">' . $q1->Date_Received . '</td>
            <td class="tg-031e">' . $q1->Date_Issued . '</td>
	<td class="tg-031e">' . $q1->Analyst_Return_Date . '</td>
	<td class="tg-031e">' . $q1->Supervisor_return_Date . '</td>'
                    . ' <td class="tg-031e e12">' . $q1->dbs . '</td>'
                    . '<td class="tg-031e e12">' . $q1->dwa . '</td>'
                    . '<td class="tg-031e e12">' . $q1->dws . '</td><td class="tg-031e e12">' . $q1->from_who . '</td></tr>';
            $i++;
        endforeach;

        $css .= '</table>';
        $css .= '</center></body></html>';

        echo $css;





        $css1 = '';
        $css1 .= '<p></p>';
        $css1 .= '<center>';
        $css1 .= '<table class="tg"><tr><th class="tg-031e" colspan="3"> Activity Summary from  [' . $this->dateToWords($start) . ' to ' . $this->dateToWords($end) . ']</th></tr>';
        $css1 .= '<tr>
    <td class="tg-031e he">No:</td><td class="tg-031e he">Supervisor</td><td class="tg-ugh9 he">No. of samples Returned</td></tr>';
        $query2 = $this->db->query("SELECT labref,from_who, COUNT(id)as count 
FROM tracking_table
WHERE date_added_1 BETWEEN '$start' AND '$end'
AND activity='Returning to Documentation'
GROUP BY from_who ORDER BY count ASC")->result();
        $j = 1;
        foreach ($query2 as $q1):
            $css1 .= ' <tr>
       <td class="tg-031e">' . $j . '</td>
    <td class="tg-031e">' . $q1->from_who . '</td><td class="tg-031e">' . $q1->count . '</td>';
            $j++;
        endforeach;

        $css1 .= '</table>';
        $css1 .= '</center></body></html>';

        echo $css1;
    }

    public function base_params($data) {

        $data['styles'] = array("jquery-ui.css");
        $data['scripts'] = array("jquery-ui.js");
        $data['scripts'] = array("SpryAccordion.js");
        $data['styles'] = array("SpryAccordion.css");
        $data['quick_link'] = "request";
        $data['content_view'] = "settings_v";
        $data['banner_text'] = "NQCL Settings";
        $data['link'] = "settings_management";

        $this->load->view('template', $data);
    }

//end base_params
}
