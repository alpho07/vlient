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
            {{$message}}
            <br>
            <p>
                Click <a class="btn btn-primary btn-lg" href="{{url('newc')}}">HERE</a> to add Contact Person and Get Started
            </p>

        </div> <!-- /.col-md-3 -->

    </div>
</div> <!-- /.row -->
<!-- /Statboxes -->
</div>
@endsection
