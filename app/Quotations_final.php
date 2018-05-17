<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quotations_final extends Model {

    protected $table='quotations_final';

    public $timestamps = false;
    
    protected $fillable = [
        'quotation_no',
        'client_id',
        'amount',
        'reporting_fee',
        'admin_fee',
        'discount',
        'payable_amount',
        'currency',
        'qdetails',
        'quotation_entries',
        'quotation_status',
        'signatory_title',
        'signatory_name',
        'date_printed',
        'print_status',
        'source_status'];

//Define relationships

    public function quotations(){

        return $this->hasMany('App\Quotations', 'quotation_no', 'quotation_no');
    
    }

}