<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quotations extends Model {

    protected $table='quotations';
    
    protected $fillable = ['client_number',
        'client_email',
        'client_number',
        'sample_name',
        'no_of_batches',
        'quotation_date',
        'active_ingredients',
        'dosage_form',
        'quotation_id',
        'quotations_id',
        'quotation_no',
        'quotation_entries',
        'quotation_entries_done',
        'amount',
        'reporting_fee',
        'admin_fee',
        'discount',
        'batch_id',
        'ndq_ref',
        'currency',
        'qdetails',
        'quotation_status',
        'completion_status',
        'quotation_print_status',
        'signatory_title',
        'signatory_name'];

    //Define relationships

    public function q_request_details(){

        return $this->hasMany('App\Q_request_details', 'quotations_id', 'quotations_id');
    
    }


    public function quotations_final(){

        return $this->belongsTo('App\Quotations_final', 'quotation_no', 'quotation_no');
    
    }


}
