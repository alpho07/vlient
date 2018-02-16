<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestDetails extends Model
{
    protected $table='request_details';
    
    protected $fillable = ['request_id','test_id','analyst_id','limit' ];
}
