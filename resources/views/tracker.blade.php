@extends('layouts.app')

@section('content')
<div class="container">
    <!--=== Page Header ===-->
    @include('partials.mini-header')
    <!-- /Page Header -->

    <!--=== Page Content ===-->
    <!--=== Statboxes ===-->
    <div class="col-md-12">
        <div class="widget box">
            <div class="widget-header">
                <h4><i class="icon-reorder"></i> Tracker</h4>
            </div>
            <div class="widget-content">
                <form class="form-horizontal row-border" action="{{url('search')}}" method="GET">
                  
                        <div class="form-group">
                            <div class="col-md-12"><input value="{{Request::old('keyword')}}" type="text" name="keyword" title="Track by Sample Name, Batch No. or Labreference No." class="form-control bs-tooltip" placeholder="Enter Sample Name, Batch No. or Labreference No."></div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-5">
                            <button class="btn btn-success">Track Sample</button>
                            </div>
                        </div>
                   
                </form>
            </div>
        </div>
    </div>

    <!-- /Statboxes -->
</div>
@endsection
