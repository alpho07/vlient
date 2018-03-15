@extends('layouts.app')

@section('content')
<div class="container">
    <!--=== Page Header ===-->
    @include('partials.mini-header')
    <!-- /Page Header -->

    <!--=== Page Content ===-->
    <!--=== Full Size Inputs ===-->
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget box">
                        <div class="widget-header">
                            <h4><i class="icon-reorder"></i>Contact Persons</h4> 
                            <div class="toolbar no-padding">
                                <div class="btn-group">
                                    <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content no-padding">
                            <a href="{{route('newc')}}"  class="btn btn-info btn-lg">New Contact</a>
                            <table class="table table-striped table-bordered table-hover table-checkable table-tabletools datatable ALLINVPICES">
                                <thead>
                                    <tr>
                                        <th class="checkbox-column">
                                            <input type="checkbox" class="uniform">All
                                        </th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>                                        
                                        <!--th>Action</th-->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cperson as $c)
                                    <tr>
                                        <td class="checkbox-column">
                                            <input type="checkbox" class="uniform" value="{{$c->id}}">
                                        </td>
                                        <td>{{$c->name}}</td>
                                        <td>{{$c->email}}</td>
                                        <td>{{$c->phone}}</td>
                                       
                                        <!--td><a href="{{url('editc/'.$c->id)}}">Edit</a> | <a href="{{url('delete/'.$c->id)}}">Delete</a></td-->
                                    </tr>
                                  @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- /.col-md-12 -->
    </div> <!-- /.row -->
    <!-- /Box Tabs -->

    <!-- /.container -->
</div>


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
@endsection
