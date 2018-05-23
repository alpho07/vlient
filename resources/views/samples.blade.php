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
                    <h4><i class="icon-reorder"></i> Sample Requests</h4>
                </div>
                <div class="widget-content">
                    <div class="tabbable box-tabs">
                        <ul class="nav nav-tabs">
                            <li><a href="#box_tab3" data-toggle="tab">Pending  Sample Requests</a></li>
                            <li><a href="#box_tab2" data-toggle="tab">Completed Sample  Requests</a></li>
                            <li class="active"><a href="#box_tab1" data-toggle="tab"> All Sample Requests</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="box_tab1">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="widget box">
                                            <div class="widget-header">
                                                <h4><i class="icon-reorder"></i>All Sample Requests</h4>
                                                <div class="toolbar no-padding">
                                                    <div class="btn-group">
                                                        {{--@if(Auth::user()->parent=='0')--}}
                                                          {{--@else--}}
                                                       <a href="{{route('q_request')}}" class="btn btn-warning">Request A Quote</a>  <a href="{{route('new')}}" class="btn btn-success"><i class="icon-plus-sign">Submit New Sample</i></a>
                                                       {{--@@endif--}}
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
                                                            <th>Lab Reference</th>
                                                            <th>Date Submitted</th>
                                                            <th>Sample Status</th>
                                                            <th>Analysis Status</th>
                                                            <th>CAN No.</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($all as $a)
                                                        <tr>
                                                            <td class="checkbox-column">
                                                                <input type="checkbox" value="{{$a->id}}" class="uniform">
                                                            </td>
                                                            <td>
                                                                {{$a->request_id}} 
                                                                 @if($a->t==1)
                                                                 | <a href="{{url('edit/'.$a->request_id)}}"><span class="label label-warning">Edit</span></a>
                                                              @else
                                                               
                                                            @endif
                                                            
                                                            </td>
                                                            <td>{{$a->designation_date_1}}</td>                                                            
                                                            <td>
                                                          @if($a->t==1)
                                                            <span class="label label-warning">Pending</span>
                                                              @else
                                                                <span class="label label-success">Approved</span>
                                                            @endif
                                                          </td>
                                                          <td>
                                                           @if($a->CAN=='-')
                                                                  <span class="label label-warning">Process Ongoing...</span>
                                                                @else
                                                                   <span class="label label-success">Complete</span>
                                                                @endif
                                                          </td>
                                                            <td>
                                                                @if($a->CAN=='-')
                                                                 {{'-'}}
                                                                @else
                                                                <a href="{{'http://156.0.233.241/NQCL/coa/coa_engine/'.$a->request_id}}" target="_blank">View COA</a>
                                                                @endif
                                                            </td>
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
    <h4><i class="icon-reorder"></i>Completed Requests</h4>
                            <div class="toolbar no-padding">
                                <div class="btn-group">
                                     @if(Auth::user()->parent=='0')
                                                        @else
                                                       <a href="{{route('q_request')}}" class="btn btn-warning"><i class="icon-euro">Request A Quote</i></a>  <a href="{{route('new')}}" class="btn btn-success"><i class="icon-plus-sign">Submit New Sample</i></a>
                                              @endif
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
                                    <th>Lab Reference</th>
                                    <th>Date Submitted</th>
                                    <th >Sample Status</th>
                                    <th>Analysis Status</th>
                                    <th>CAN No.</th>
                                </tr>
                            </thead>
                            <tbody>
                              @foreach($Completed as $a)
                                                        <tr>
                                                            <td class="checkbox-column">
                                                                <input type="checkbox" value="{{$a->id}}" class="uniform">
                                                            </td>
                                                            <td>{{$a->request_id}}</td>
                                                            <td>{{$a->designation_date_1}}</td>                                                            
                                                            <td>
                                                          @if($a->t==1)
                                                            <span class="label label-warning">Pending</span>
                                                              @else
                                                                <span class="label label-success">Approved</span>
                                                            @endif
                                                          </td>
                                                            <td>
                                                             @if($a->CAN=='-')
                                                                  <span class="label label-warning">Process Ongoing...</span>
                                                                @else
                                                                   <span class="label label-success">Complete</span>
                                                                @endif
                                                            </td> 
                                                            <td>
                                                            @if($a->CAN=='-') 
                                                                 {{'-'}}
                                                                @else
                                                                <a href="{{'http://156.0.233.241/NQCL/coa/coa_engine/'.$a->request_id}}" target="_blank">View COA</a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach

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
                        <h4><i class="icon-reorder"></i>Pending Sample Requests</h4>
                        <div class="toolbar no-padding">
                            <div class="btn-group">
                                 @if(Auth::user()->parent=='0')
                                                        @else                 
                                <a href="{{route('q_request')}}" class="btn btn-warning"><i class="icon-euro">Request A Quote</i></a>  <a href="{{route('new')}}"< class="btn btn-success"><i class="icon-plus-sign">Submit New Sample</i></a>
                            @endif
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
                                    <th>Lab Reference</th>
                                    <th>Date Submitted</th>
                                    <th >Sample Status</th>
                                    <th>Analysis Status</th>
                                    <th>CAN No.</th>
                                </tr>
                            </thead>
                            <tbody>
                                      @foreach($Pending as $a)
                                                        <tr>
                                                            <td class="checkbox-column">
                                                                <input type="checkbox" value="{{$a->id}}" class="uniform">
                                                            </td>
                                                            <td>{{$a->request_id}}</td>
                                                            <td>{{$a->designation_date_1}}</td>                                                            
                                                            <td>
                                                          @if($a->t==1)
                                                            <span class="label label-warning">Pending</span>
                                                              @else
                                                                <span class="label label-success">Approved</span>
                                                            @endif
                                                          </td>
                                                            <td> @if($a->CAN=='-')
                                                                  <span class="label label-warning">Process Ongoing...</span>
                                                                @else
                                                                   <span class="label label-success">Complete</span>
                                                                @endif</td> 
                                                            <td>{{$a->CAN}}</td>
                                                        </tr>
                                                    @endforeach

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
