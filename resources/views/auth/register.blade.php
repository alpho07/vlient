@extends('layouts.app')

@section('content')
<style>
    legend{
        font-size: 12px;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>New Client: Registration</strong></div>
                <div class="error" style="color:red; font-weight: bold;">
                    @if (count($errors)) 

                    <ul>
                        @foreach($errors->all() as $error) 
                        <li>{{ $error }}</li>
                        @endforeach 
                    </ul>
                    @endif 

                </div>
                <div class="panel-body">
                    <p><strong>Client Details</strong></p>
                    <form class="form-horizontal" method="POST" action="{{url('register')}}">
                        {{csrf_field()}}

                        <div class="form-group">

                            <fieldset>
                                <div class="col-md-6 ">
                                    <textarea id="" class="form-control" name="name" placeholder="Enter Client Name e.g. National Quality Control Laboratory" required="" >{{Request::old('name')}}</textarea>

                                </div>                   


                                <div class="col-md-6">
                                    <textarea id="address" class="form-control" name="address" placeholder="Enter Client Address e.g. Client Client P.O.BOX 123 Region"  required="" >{{Request::old('address')}}</textarea>

                                </div>
                            </fieldset>


                        </div>

                        <div class="form-group">

                            <div class="col-md-6">
                                <input id="email" value="{{Request::old('email')}}" class="form-control" placeholder="Enter Client Email e.g. company@domain.com" name="email"  required="" type="email">

                            </div>
                            <div class="col-md-6">
                                <input id="email" value="{{Request::old('phone')}}" class="form-control" placeholder="Enter Client Phone e.g. 07XXYYJJKK" name="phone"  required="" type="phone">

                            </div>
                        </div>
<!--                        <hr>
                        <strong> Contact Persons Details</strong>
                        <hr>-->

<!--                        <div class="FGROUP">
                            <div class="form-group ">                          
                                <fieldset>
                                    <legend>1<sup>st</sup> Contact Person</legend>
                                    <div class="col-md-4">
                                        <input  class="form-control" value="{{Request::old('cont.0')}}" name="cont[]" required="" placeholder="Name" type="text">

                                    </div>
                                    <div class="col-md-3">
                                        <input  class="form-control" value="{{Request::old('cphone.0')}}" name="cphone[]" required="" placeholder="Phone Number" type="text">

                                    </div>
                                    <div class="col-md-4">
                                        <input  class="form-control" value="{{Request::old('cemail.0')}}" name="cemail[]" required="" placeholder="Email" type="text">

                                    </div>
                                    <div class="col-md-1">
                                        <a href="#add" class="ADDCONT">+Add</a>                                  
                                    </div>
                                </fieldset>
                            </div>
                        </div>-->



<!--                        <hr>-->
                        <strong> Client Password</strong>
                        <hr>

                        <div class="form-group">

                            <div class="col-md-6">
                                <input id="password" class="form-control" name="password" required="" placeholder="Enter Password" type="password">

                            </div>
                            <div class="col-md-6">
                                <input id="password-confirm" class="form-control" name="password_confirmation" required="" placeholder="Confirm Password" type="password">
                            </div>
                        </div>






                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
