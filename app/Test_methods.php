<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test_methods extends Model {

    protected $table='test_methods';

    public $timestamps = false;
    
//Define relationships

    public function tests(){

        return $this->belongsTo('App\Tests', 'id', 'test_id');
    
    }

}