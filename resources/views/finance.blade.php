@extends('layouts.app')

@section('content')
<div class="container">
    <!--=== Page Header ===-->
    @include('partials.mini-header')
    <!-- /Page Header -->

    <!--=== Page Content ===-->
    <!--=== Statboxes ===-->
    <div class="row">
        <div class="col-md-12">
            <div class="widget box">
                <div class="widget-header">
                    <h4><i class="icon-reorder"></i> Invoices</h4>
                </div>
                <div class="widget-content">
                    @if(Session::has('quote'))
                    <div class="alert alert-success">
                        {{Session::get('quote')}}
                    </div>
                    @else 
                    @endif
                    <div class="btn-group">
                        {{--@if(Auth::user()->parent=='0')--}}
                        {{--@else--}}
                        <a href="{{route('q_request')}}" class="btn btn-warning">Request A Quote</a>  <a href="{{route('new')}}" class="btn btn-success"><i class="icon-plus-sign">Add New Request</i></a>
                        {{--@endif--}}
                    </div>
                    <div class="tabbable box-tabs">
                        <ul class="nav nav-tabs">
                            <li><a href="#box_tab3" data-toggle="tab">Pending Invoices</a></li>
                            <li><a href="#box_tab2" data-toggle="tab">Paid Invoices</a></li>
                            <li class="active"><a href="#box_tab1" data-toggle="tab"> All Invoices</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="box_tab1">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="widget box">
                                            <div class="widget-header">
                                                <h4><i class="icon-reorder"></i>All Invoices</h4>
                                                <div class="toolbar no-padding">
                                                    <div class="btn-group">
                                                        <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="widget-content no-padding">
                                                <table class="table table-striped table-bordered table-hover table-checkable table-tabletools datatable ALLINVPICES">
                                                    <thead>
                                                        <tr>
                                                            <th class="checkbox-column">
                                                                <input type="checkbox" class="uniform">All
                                                            </th>
                                                            <th>Quotation No.</th>
                                                            <th>Invoice Reference</th>
                                                            <th>Date Created</th>
                                                            <th >Amount</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($quotations as $q)
                                                        <tr>                                                           
                                                            <td class="checkbox-column">
                                                                <input type="checkbox" class="uniform" value="{{$q->id}}">
                                                            </td>
                                                            <td>{{$q->quotations_id}}</td>
                                                            <td>INV-NDQC11233</td>
                                                            <td>{{$q->quotation_date}}</td>
                                                            <td>{{$q->amount}}</td>
                                                            <td>
                                                                @if($q->quotation_status===1)
                                                                <span class="label label-success">Paid</span>
                                                                @else
                                                                <span class="label label-warning">Pending</span>
                                                                @endif

                                                            </td>
                                                            <td><a href="#">View</a> | <a href="#">Archive</a> | <a href="#">Print</a></td>
                                                        </tr>
                                                        @endforeach

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane" id="box_tab2">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="widget box">
                                            <div class="widget-header">
                                                <h4><i class="icon-reorder"></i>Paid Invoices</h4>
                                                <div class="toolbar no-padding">
                                                    <div class="btn-group">
                                                        <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="widget-content no-padding">
                                                <table class="table table-striped table-bordered table-hover table-checkable table-tabletools datatable ALLINVPICES">
                                                    <thead>
                                                        <tr>
                                                            <th class="checkbox-column">
                                                                <input type="checkbox" class="uniform">All
                                                            </th>
                                                            <th>Quotation No.</th>
                                                            <th>Invoice Reference</th>
                                                            <th>Date Created</th>
                                                            <th >Amount</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>





                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="box_tab3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="widget box">
                                            <div class="widget-header">
                                                <h4><i class="icon-reorder"></i>Pending Invoices</h4>
                                                <div class="toolbar no-padding">
                                                    <div class="btn-group">
                                                        <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="widget-content no-padding">
                                                <table class="table table-striped table-bordered table-hover table-checkable table-tabletools datatable">
                                                    <thead>
                                                        <tr>
                                                            <th class="checkbox-column">
                                                                <input type="checkbox" class="uniform">All
                                                            </th>
                                                            <th>Quotation No.</th>
                                                            <th>Invoice Reference</th>
                                                            <th>Date Created</th>
                                                            <th >Amount</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>





                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /.tabbable portlet-tabs -->
                </div> <!-- /.widget-content -->
            </div> <!-- /.widget .box -->
        </div> <!-- /.col-md-12 -->
    </div> <!-- /.row -->
    <!-- /Box Tabs -->

    <!-- /Statboxes -->
</div>
@endsection
