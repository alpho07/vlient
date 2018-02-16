<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App;

/**
 * Description of Client
 *
 * @author ALPHY-DT
 */
class Client {
    protected $table='clients';
    
    protected $fillable = [
        'name','email','alias','address','client_type',
        'contact_person','contact_phone','client_id','comment','credit','client_agent_id','discount_percentage','created_at','updated_at'
    ];
}
