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
                <h4><i class="icon-reorder"></i> Search Term: {{$keyword}}</h4>
            </div>
            <div class="widget-content">
                <form class="form-horizontal row-border" action="{{url('search')}}" method="GET">

                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="col-md-10">
                                <input value="{{$keyword}}" type="text" name="keyword" title="Track by Sample Name, Batch No. or Labreference No." class="form-control bs-tooltip" placeholder="Enter Sample Name, Batch No. or Labreference No." required>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-success" type="submit">Search</button>
                            </div>
                        </div>
                          
                    </div>
                </form>

                <div class="col-md-12">                             
                    <table class="table table-striped .table-bordered">   
                        <tbody>
                            @if(!$caveats->isEmpty())
                            @foreach($caveats as $c)
                            @if($c->CAN=="")
                            @php $stat='Processing' @endphp
                            @else
                             @php $stat='Complete' @endphp
                            @endif
                            <tr>
                                <td>
                                    &#10148; &nbsp;{{$c->product_name.' ' .$c->request_id .'-'.$c->batch_no.' Status : Pending'}}
                                </td>
                            </tr>
                            @endforeach

                            @else
                            <tr><td>No Sample found for your search term... 
                                    <a href="{{url('new')}}" class="btn btn-success btn-lg">Submit Sample</a>  or   <a href="{{url('samples')}}" class="btn btn-primary btn-lg">Explore</a>
                                </td> 
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="pull-left">{{$caveats->render()}}</div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
