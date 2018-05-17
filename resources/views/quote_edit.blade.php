@extends('layouts.app')

@section('content')
<div class="container">
    <!--=== Page Header ===-->
    @include('partials.mini-header')
    <!-- /Page Header -->

    <!--=== Page Content ===-->
    <!--=== Full Size Inputs ===-->
    <form class="form-horizontal row-border" action="{{route('updateQuote')}}" method="POST">
        <div class="row">
            {{csrf_field()}}
            <div class="col-md-12">
                <div class="widget box">
                    <div class="widget-header">
                        <h4><i class="icon-reorder"></i>Edit Quotation Request</h4>
                    </div>
                    <div class="widget-content">


                        <div class="form-group">                            
                            <div class="col-md-12">
                                <div class="row">                                  

                                    <div class="col-md-3 pull-left">
                                        <label>
                                            <span>Qtn. Reference No</span>
                                        </label> <input type="text" name="quotation_no" class="form-control" id='ctype' readonly value="{{$temp}}">
                                    </div>
                                    
                                     <div class="col-md-3 ">
                                        <label>
                                            <span>Product Name</span> <span class="label label-info">{{$quotation[0]['sample_name']}}</span>
                                        </label> <textarea  name="sample_name" class="form-control"  placeholder="Enter Product Name" id='sample_name' required>{{$quotation[0]['sample_name']}}</textarea>
                                    </div>
                                    
                                     <div class="col-md-3 pull-left">
                                        <label>
                                            <span>No of Batches</span> <span class="label label-info" >{{$quotation[0]['no_of_batches']}}</span>
                                        </label> <input type="text" name="batches" class="form-control" value="{{$quotation[0]['no_of_batches']}}" pattern="[1-9]{1}[0-9]{0,4}" required>
                                    </div>
                                    
                                      <div class="col-md-3 pull-left">
                                        <label>
                                            <span>Currency</span> <span class="label label-info" >{{$quotation[0]['currency']}}</span> 
                                        </label> <select name="currency" class="form-control" required>
                                            <option value="KES" @if( $quotation[0]['currency'] == 'KES')  selected="selected"  @endif >KES</option>
                                            <option value="USD" @if( $quotation[0]['currency'] == 'USD')  selected="selected"  @endif >USD</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!--Forms -->

        <div class="row hidden" >
            <div class="col-md-12">
                <div class="widget box">
                    <div class="widget-header">
                        <h4><i class="icon-reorder"></i> Personal Information</h4>
                    </div>
                    <div class="widget-content">
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="radio">
                                    <input type="text" readonly name="clientname" class="form-control" id='cname'  placeholder="CLIENT NAME"  value="{{Auth::user()->fname}}">
                                </label>
                                <label class="radio">
                                    <input type="text" readonly name="contactname" class="form-control" id='coname' placeholder="CONTACT NAME" value="">
                                </label>

                            </div>
                            <div class="col-md-4">
                                <label class="radio">
                                    <input type="text" readonly name="clientemail" class="form-control" id='cemail' placeholder="CLIENT EMAIL" value="{{Auth::user()->email}}">
                                </label>
                                <label class="radio">
                                    <input type="text" readonly name="contacttel" class="form-control" id='cphone' placeholder="CONTACT TELEPHONE" value="">
                                </label>

                            </div>
                            <div class="col-md-4">
                                <label class="radio">
                                    <textarea  cols="5" readonly name="clientaddress" class="form-control" id='caddress' placeholder="CLIENT ADDRESS">{{Auth::user()->address}}</textarea>
                                </label>


                            </div>
                            <p></p>
                            <div class="col-md-12 pull-right">
                                <a href="{{url('profile')}}">Edit Profile</a>
                            </div>
                        </div>                       
                    </div>
                </div>
            </div>
        </div>





        <div class="row">
            <div class="col-md-12">
                <div class="widget box">
                    <div class="widget-header">
                        <h4><i class="icon-reorder"></i> Select Tests 
                        <span class="label label-info">
                            @php foreach($tests as $test) {
                                    array_push($test_ids, $test->tests->id); 
                                    echo $test->tests->name.' '; 
                                } 
                            @endphp
                        </span>
                    </h4>
                    </div>
                    <div class="widget-content">
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="radio">
                                    <strong><u>Wet-Chemistry</u></strong> 
                                </label>
                                @foreach($wet as $w)
                                <label class="radio">
                                    <input type="checkbox" @if(in_array($w->id, $test_ids)) {{'checked'}} @endif id="{{$w->name}}" class="uniform" value="{{$w->id}}" name="tests[]"> {{$w->name}} 
                                </label>
                                @endforeach



                            </div>
                            <div class="col-md-4">
                                <label class="radio">
                                    <strong><u> Microbiology</u></strong>
                                </label>
                                @foreach($micro as $m)
                                <label class="radio">
                                    <input type="checkbox" @if(in_array($m->id, $test_ids)) {{'checked'}} @endif id="{{$m->name}}" class="uniform" value="{{$m->id}}" name="tests[]"> {{$m->name}}
                                </label>
                                @endforeach



                            </div>
                            <div class="col-md-4">
                                <label class="radio">
                                    <strong><u> Medical Devices</u></strong>
                                </label>
                                @foreach($med as $e)
                                <label class="radio">
                                    <input type="checkbox" @if(in_array($m->id, $test_ids)) {{'checked'}} @endif id="{{$e->name}}" class="uniform" value="{{$e->id}}" name="tests[]"> {{$e->name}}
                                </label>
                                @endforeach


                            </div>


                        </div> 

                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="widget box">
                    <div class="widget-header">
                        <h4><i class="icon-reorder"></i> Request Information</h4>
                    </div>
                    <div class="widget-content">
                        <div class="form-group">


                            <div class="col-md-10">
                                <label class="radio">
                                    <textarea id="tests_requested" style="width: 100%;" name="client_message" class="form-control" placeholder="Write custom message here"></textarea>
                                </label>


                            </div>


                        </div> 

                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="form-group">
                <button type="submit" class="btn btn-success btn-lg">SUBMIT EDITED QUOTE</button>
            </div>
        </div>
    </form>
</div>

<!-- /Page Content -->
</div>
<!-- /.container -->
</div>


@endsection
