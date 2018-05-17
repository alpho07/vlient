<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Q_request_details extends Model {

    protected $table='q_request_details';
    
    protected $fillable = [
        'quotations_id',
        'quotation_id',
        'client_email',
        'client_number',
        'test_charge',
        'method_charge',
        'test_id',
        'method_id',
        'compendia_id'
    ];

    public $timestamps = false;

    //Define relationships

    public function quotations(){

        return $this->belongsTo('App\Quotations', 'quotations_id', 'quotations_id');
    
    }

    public function tests(){
        return $this->belongsTo('App\Tests', 'test_id', 'id');
    }


}