@extends('layouts.app')

@section('content')
<div class="container">
    <!--=== Page Header ===-->
    @include('partials.mini-header')
    <!-- /Page Header -->

    <!--=== Page Content ===-->
    <!--=== Full Size Inputs ===-->
    <form class="form-horizontal row-border" action="#">


        <div class="row">
            <div class="col-md-12">
                <div class="widget box">
                    <div class="widget-header">
                        <h4><i class="icon-reorder"></i> Personal Information</h4>
                    </div>
                    <div class="widget-content">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label class="radio">
                                    <input type="text" name="firstname" class="form-control" style="background: greenyellow"  placeholder="FIRST NAME">
                                </label>
                                <label class="radio">
                                    <input type="text" name="lastname" class="form-control"  placeholder="LAST NAME">
                                </label>
                                <label class="radio">
                                    <textarea  cols="5" name="postaladdress" class="form-control" placeholder="POSTAL ADDRESS"></textarea>
                                </label>

                            </div>
                            <div class="col-md-6">
                                <label class="radio">
                                    <input type="text" name="company" class="form-control"  placeholder="COMPANY">
                                </label>
                                <label class="radio">
                                    <input type="text" name="mobile" class="form-control"  placeholder="MOBILE NO.">
                                </label>
                                <label class="radio">
                                    <input type="text" name="email" class="form-control"  placeholder="E-MAIL">
                                </label>

                            </div>                      


                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success btn-lg" style="margin-left: 20px;">UPDATE PROFILE DETAILS</button>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>


        <!-- /.container -->
</div>
@endsection
