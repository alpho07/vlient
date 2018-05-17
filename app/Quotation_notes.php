<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quotation_notes extends Model {

    protected $table='quotation_notes';
    public $timestamps = true;
    
    protected $fillable = [
        'quotaton_no',
        'note'];

}