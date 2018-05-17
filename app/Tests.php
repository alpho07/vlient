<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tests extends Model {

    protected $table='tests';

    public $timestamps = false;

    //Define relationships

    public function q_request_details(){
        return $this->hasMany('App\Q_request_details', 'id', 'test_id');
    }


}