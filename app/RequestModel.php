<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestModel extends Model
{
    protected $table='request';
    
    protected $fillable = [
        'sample_qty','product_name','active_ing','dosage_form','manufacturer_name',
        'manufacturer_add','batch_no','exp_date','manufacture_date','label_claim','description','presentation','country_of_origin',
        'packaging','client_id','c','t','quotation','requester'
    ];
}
