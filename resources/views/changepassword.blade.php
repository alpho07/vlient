@extends('layouts.app')

@section('content')
<div class="container">
    <!--=== Page Header ===-->
    @include('partials.mini-header')
    <!-- /Page Header -->

    <!--=== Page Content ===-->
    <!--=== Full Size Inputs ===-->
    @if(Auth::user()->parent != '0')

    <form action="{{url('update_password')}}" method="post">
        {{csrf_field()}}
        <div class="widget box">
            <div class="widget-header">
                <h4><i class="icon-reorder"></i> Change Password</h4>
            </div>
            <div class="widget-content">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="form-group">
                    <!div class="col-md-6">
                    <label class="radio">
                        <input required type="password" name="password"  class="form-control"  placeholder="New Password">
                    </label>
                    <label class="radio">
                        <input required type="password" name="password_confirmation"  class="form-control"  placeholder="Confirm New Password">
                    </label>                             

                </div>
            </div>
            <div class="form-group">
                <span style="margin-left:10px;">Tick the checkbox below to show change password button</span><br>
                <input type="checkbox" id="CPUPASS" style="margin-left:10px;" title="Check to show update Button">
                <button type="submit" class="btn btn-success btn-lg CPCLIPASS" style="margin-left: 20px; display:none;">CHANGE PASSWORD</button>
            </div>
        </div>                    
</div>
</form>
@else

<form action="{{url('update_password')}}" method="post">
    {{csrf_field()}}
    <div class="widget box">
        <div class="widget-header">
            <h4><i class="icon-reorder"></i> Change Password</h4>
        </div>
        <div class="widget-content">
            @if(Session::has('pcsuccess'))
            <div class="alert alert-success">
                {{Session::get('pcsuccess')}}
            </div>
            @else

            @endif
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="form-group">
                <div class="col-md-6">
                    <label class="radio">
                        <input required type="password" name="password"  class="form-control"  placeholder="New Password">
                    </label>
                    <label class="radio">
                        <input required type="password" name="password_confirmation"  class="form-control"  placeholder="Confirm New Password">
                    </label>                             

                </div>
            </div>
            <div class="form-group">
                <span style="margin-left:10px;">Tick the checkbox below to show change password button</span><br>
                <input type="checkbox" id="UPASS" style="margin-left:10px;" title="Check to show update Button">
                <button type="submit" class="btn btn-success btn-lg CLIPASS" style="margin-left: 20px; display:none;">CHANGE PASSWORD</button>
            </div>
        </div>                    
    </div>
</form>
@endif

<!-- /.container -->
</div>
@endsection
