@extends('layouts.app')

@section('content')
<div class="container">
    <!--=== Page Header ===-->
    @include('partials.mini-header')
    <!-- /Page Header -->

    <!--=== Page Content ===-->
    <!--=== Statboxes ===-->
    <div class="row row-bg "> <!-- .row-bg -->
        <div class="col-lg-offset-2">
    <div class="col-sm-6 col-md-3">
        <div class="statbox widget box box-shadow">
            <div class="widget-content">
                <div class="visual green">
                    <i class="icon-beaker"></i>
                </div>
                <div class="title">ANALYSIS SAMPLES</div>
                <div class=""></div>
                <a class="more" href="{{route('samples')}}"><small>View all submitted samples or Submit new Samples for analysis.</small> <i class="pull-right icon-angle-right"></i></a>
            </div>
        </div> <!-- /.smallstat -->
    </div> <!-- /.col-md-3 -->


            <div class="col-sm-6 col-md-3">
                <div class="statbox widget box box-shadow">
                    <div class="widget-content">
                        <div class="visual cyan">
                            <i class="icon-double-angle-right"></i>
                        </div>
                        <div class="title">TRACK SAMPLE</div>                      
                        <a class="more" href="{{route('tracker')}}"><small>Track all your submitted samples easy and fast.</small> <i class="pull-right icon-angle-right"></i></a>
                    </div>
                </div> <!-- /.smallstat -->
            </div> <!-- /.col-md-3 -->


            <div class="col-sm-6 col-md-3 hidden-xs">
                <div class="statbox widget box box-shadow">
                    <div class="widget-content">
                        <div class="visual yellow">
                            <i class="icon-money"></i>
                        </div>
                        <div class="title">MY FINANCIALS</div>
                        <a class="more" href="{{route('finance')}}"><small>Get all your financial details. Pending/Paid invoices & Quotations</small>  <i class="pull-right icon-angle-right"></i></a>
                    </div>
                </div> <!-- /.smallstat -->
            </div> <!-- /.col-md-3 -->

        </div>
    </div> <!-- /.row -->
    <!-- /Statboxes -->
</div>
@endsection
