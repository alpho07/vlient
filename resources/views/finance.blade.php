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
                                                            <th>Invoice Reference</th>
                                                            <th>Date Created</th>
                                                            <th >Amount</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="checkbox-column">
                                                                <input type="checkbox" class="uniform">
                                                            </td>
                                                             <td>INV-NDQC11233</td>
                                                            <td>2017-07-08</td>
                                                            <td>50,126.00</td>
                                                            <td><span class="label label-success">Approved</span></td>
                                                            <td><a href="#">View</a> | <a href="#">Archive</a> | <a href="#">Print</a></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="checkbox-column">
                                                                <input type="checkbox" class="uniform">
                                                            </td>
                                                            <td>INV-NDQC11233</td>
                                                            <td>2017-01-06</td>
                                                            <td>50,126.00</td>
                                                            <td><span class="label label-success">Approved</span></td>
                                                            <td><a href="#">View</a> | <a href="#">Archive</a> | <a href="#">Print</a></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="checkbox-column">
                                                                <input type="checkbox" class="uniform">
                                                            </td>
                                                            <td>INV-NDQE23432</td>
                                                            <td>2017-02-05</td>
                                                            <td>10,126.00</td>
                                                            <td><span class="label label-warning">pending</span></td>
                                                            <td><a href="#">View</a> | <a href="#">Archive</a> | <a href="#">Print</a></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="checkbox-column">
                                                                <input type="checkbox" class="uniform">
                                                            </td>
                                                            <td>INV-NDQF3232r</td>
                                                            <td>2017-04-01</td>
                                                            <td>60,126.00</td>
                                                            <td><span class="label label-danger">Cancelled</span></td>
                                                            <td><a href="#">View</a> | <a href="#">Archive</a> | <a href="#">Print</a></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="checkbox-column">
                                                                <input type="checkbox" class="uniform">
                                                            </td>
                                                            <td>INV-NDQD12345</td>
                                                            <td>2017-07-08</td>
                                                            <td>40,126.00</td>
                                                            <td><span class="label label-success">Approved</span></td>
                                                            <td><a href="#">View</a> | <a href="#">Archive</a> | <a href="#">Print</a></td>
                                                        </tr>
                                                        
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
                                                            <th>Invoice Reference</th>
                                                            <th>Date Created</th>
                                                            <th >Amount</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="checkbox-column">
                                                                <input type="checkbox" class="uniform">
                                                            </td>
                                                            <td>INV-NDQD12345</td>
                                                            <td>2017-07-08</td>
                                                            <td>50,126.00</td>
                                                            <td><a href="#">View</a> | <a href="#">Archive</a> | <a href="#">Print</a></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="checkbox-column">
                                                                <input type="checkbox" class="uniform">
                                                            </td>
                                                            <td>INV-NDQC11233</td>
                                                            <td>2017-01-06</td>
                                                            <td>50,126.00</td>
                                                            <td><a href="#">View</a> | <a href="#">Archive</a> | <a href="#">Print</a></td>
                                                        </tr>
                                                      
                                                        
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
                                                            <th>Invoice Reference</th>
                                                            <th>Date Created</th>
                                                            <th >Amount</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="checkbox-column">
                                                                <input type="checkbox" class="uniform">
                                                            </td>
                                                            <td>INV-NDQD12345</td>
                                                            <td>2017-07-08</td>
                                                            <td>50,126.00</td>
                                                            <td><a href="#">View</a> | <a href="#">Archive</a> | <a href="#">Print</a></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="checkbox-column">
                                                                <input type="checkbox" class="uniform">
                                                            </td>
                                                            <td>INV-NDQC11233</td>
                                                            <td>2017-01-06</td>
                                                            <td>50,126.00</td>
                                                            <td><a href="#">View</a> | <a href="#">Archive</a> | <a href="#">Print</a></td>
                                                        </tr>
                                                         <tr>
                                                            <td class="checkbox-column">
                                                                <input type="checkbox" class="uniform">
                                                            </td>
                                                            <td>INV-NDQC11233</td>
                                                            <td>2017-01-06</td>
                                                            <td>50,126.00</td>
                                                            <td><a href="#">View</a> | <a href="#">Archive</a> | <a href="#">Print</a></td>
                                                        </tr>
                                                        
                                                      
                                                        
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
