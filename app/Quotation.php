<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model {

    protected $table='quotations';
    
    protected $fillable = ['client_number',
        'client_email',
        'client_name',
        'sample_name',
        'no_of_batches',
        'quotation_date',
        'active_ingredients',
        'dosage_form',
        'quotations_id',
        'quotation_no',
        'quotation_entries',
        'quotation_entries_done',
        'amount',
        'reporting_fee',
        'admin_fee',
        'discount',
        'currency',
        'quotation_status',
        'completion_status',
        'quotation_print_status',
        'signatory_title',
        'signatory_name'];

}
