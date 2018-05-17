<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quotations_components extends Model {

    protected $table='quotations_components';

    public $timestamps = false;
    
    protected $fillable = [
        'component',
        'quotations_id',
        'quotation_id',
        'test_id',
        'method_id',
        'method_charge',
        'test_charge',
        'additional_charge',
        'charge_system',
        'stages_no'
    ];

//Define relationships

    public function quotations(){

        return $this->hasOne('App\Quotations', 'quotations_id', 'quotations_id');
    
    }

}