@extends('layouts.app')

@section('content')
<div class="container">
    <!--=== Page Header ===-->
    @include('partials.mini-header')
    <!-- /Page Header -->

    <!--=== Page Content ===-->
    <!--=== Full Size Inputs ===-->
    <form class="form-horizontal row-border" action="{{route('saveq')}}" method="POST">
        <div class="row">
            {{csrf_field()}}
            <div class="col-md-12">
                <div class="widget box">
                    <div class="widget-header">
                        <h4><i class="icon-reorder"></i> Quotation Request</h4>
                    </div>
                    <div class="widget-content">


                        <div class="form-group">                            
                            <div class="col-md-12">
                                <div class="row">                                  

                                    <div class="col-md-3 pull-left">
                                        <label>
                                            <span>Qtn. Reference No</span>
                                        </label> <input type="text" name="quotation_id" class="form-control" id='ctype' readonly value="{{$temp}}">
                                    </div>
                                    
                                     <div class="col-md-3 ">
                                        <label>
                                            <span>Product Name</span>
                                        </label> <textarea  name="sample_name" class="form-control"  placeholder="Enter Product Name" id='sample_name' required></textarea>
                                    </div>
                                    
                                     <div class="col-md-3 pull-left">
                                        <label>
                                            <span>No of Batches</span>
                                        </label> <input type="number" name="batches" class="form-control"  required>
                                    </div>
                                    
                                      <div class="col-md-3 pull-left">
                                        <label>
                                            <span>Currency</span>
                                        </label> <select name="currency" class="form-control"  required>
                                            <option value=""></option>
                                            <option value="KES">KES</option>
                                            <option value="USD" >USD</option>
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
                                    <input type="text" readonly name="contactname" class="form-control" id='coname' placeholder="CONTACT NAME" value="{{$cperson[0]->contact_person}}">
                                </label>

                            </div>
                            <div class="col-md-4">
                                <label class="radio">
                                    <input type="text" readonly name="clientemail" class="form-control" id='cemail' placeholder="CLIENT EMAIL" value="{{Auth::user()->email}}">
                                </label>
                                <label class="radio">
                                    <input type="text" readonly name="contacttel" class="form-control" id='cphone' placeholder="CONTACT TELEPHONE" value="{{$cperson[0]->contact_phone}}">
                                </label>

                            </div>
                            <div class="col-md-4">
                                <label class="radio">
                                    <textarea  cols="5" readonly name="clientaddress" class="form-control" id='caddress' placeholder="CLIENT ADDRESS">{{$cperson[0]->address}}</textarea>
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
                        <h4><i class="icon-reorder"></i> Select Tests</h4>
                    </div>
                    <div class="widget-content">
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="radio">
                                    <strong><u>Wet-Chemistry</u></strong> 
                                </label>
                                @foreach($wet as $w)
                                <label class="radio">
                                    <input type="checkbox" class="uniform" value="{{$w->name}}" name="tests[]"> {{$w->name}}
                                </label>
                                @endforeach



                            </div>
                            <div class="col-md-4">
                                <label class="radio">
                                    <strong><u> Microbiology</u></strong>
                                </label>
                                @foreach($micro as $m)
                                <label class="radio">
                                    <input type="checkbox" class="uniform" value="{{$m->name}}" name="tests[]"> {{$m->name}}
                                </label>
                                @endforeach



                            </div>
                            <div class="col-md-4">
                                <label class="radio">
                                    <strong><u> Medical Devices</u></strong>
                                </label>
                                @foreach($med as $e)
                                <label class="radio">
                                    <input type="checkbox" class="uniform" value="{{$e->name}}" name="tests[]"> {{$e->name}}
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
                                    <textarea id="tests_requested" style="width: 100%;" name="tests_requested" class="form-control" placeholder="MESSAGE"></textarea>
                                </label>


                            </div>


                        </div> 

                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="form-group">
                <button type="submit" class="btn btn-success btn-lg">REQUEST A QUOTATION</button>
            </div>
        </div>
    </form>
</div>

<!-- /Page Content -->
</div>
<!-- /.container -->
</div>


@endsection
