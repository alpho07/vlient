<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>New Quotation Request</title>
    </head>
    <body>
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Quotation Request Ref: {{$quotation_id}}
                </div>
                <div class="panel-body">
                    <div class="row">
                        Requester: {{Auth::user()->fname}}
                    </div> 
                    <div class="row">
                        Request Date: {{date('d-m-Y')}}
                    </div> 
                    
                     <div class="row">
                        Product Name: {{strtoupper($sample_name)}}
                    </div> 
                    <div class="row">
                        Batches: {{$batches}}
                    </div> 
                    <div class="row">
                        Currency Requested: {{$currency}}
                    </div> 
                    <div class="row">
                        <strong>Quotation Details:</strong><br>
                        <div class="row">
                            <p>{{$tests_requested}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
