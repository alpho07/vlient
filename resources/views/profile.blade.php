@extends('layouts.app')

@section('content')
<div class="container">
    <!--=== Page Header ===-->
    @include('partials.mini-header')
    <!-- /Page Header -->

    <!--=== Page Content ===-->
    <!--=== Full Size Inputs ===-->
    @if(Auth::user()->parent != '0')
    <form class="form-horizontal row-border" action="{{url('updatec')}}"method="post" id='CHILDFORM'>

        {{csrf_field()}}
        <div class="row">
            <div class="col-md-12">
                <div class="widget box">
                    <div class="widget-header">
                        <h4><i class="icon-reorder"></i> Personal Information</h4>
                    </div>
                    <input type="hidden" name="client_id" value="{{Auth::user()->user_id}}" class="form-control"  >

                    <div class="widget-content">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label class="radio">
                                    <input required type="text" name="fname" value="{{Auth::user()->name}}" class="form-control"  placeholder="Company Name">
                                </label>
                                <label class="radio">
                                    <input required type="text" name="email" value="{{Auth::user()->email}}" class="form-control"  placeholder="Company Email">
                                </label>
                                <label class="radio">
                                    <input required type="text" name="phone" value="{{Auth::user()->phone}}" class="form-control"  placeholder="Company Phone">
                                </label>
                                

                            </div>



                        </div>
                        <div class="form-group">
                        <span style="margin-left:10px;">Tick the checkbox below to show update button</span><br>
                         <input type="checkbox" id="CPROFILE" style="margin-left:10px;" title="Check to show update Button">
                         <button type="button" class="btn btn-success btn-lg CPUPDATE" id="CHILDUPDATER" style="margin-left: 20px; display:none;">UPDATE PROFILE DETAILS</button>
                        </div>
                    </div>                    
                </div>


            </div>
        </div>

    </form>
    <form action="{{url('requestchange')}}" method="get">
        {{csrf_field()}}
          <div class="widget box">
            <div class="widget-header">
                <h4><i class="icon-reorder"></i> Change Password</h4>
            </div>
            <div class="widget-content">
                @if(Session::has('psuccess'))
                <div class="alert alert-success">
                    {{Session::get('psuccess')}}
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
                    <!--div class="col-md-6">
                        <label class="radio">
                            <input required type="password" name="password"  class="form-control"  placeholder="New Password">
                        </label>
                        <label class="radio">
                            <input required type="password" name="password_confirmation"  class="form-control"  placeholder="Confirm New Password">
                        </label>                             

                    </div-->
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
    <form class="form-horizontal row-border" action="{{url('cupdate')}}"method="post" id='PARENTFORM'>

        {{csrf_field()}}
        <div class="row">
            <div class="col-md-12">
                <div class="widget box">
                    <div class="widget-header">
                        <h4><i class="icon-reorder"></i> Company Information</h4>
                    </div>
                    <input type="hidden" name="client_id" value="{{$client[0]->user_id}}" class="form-control"  >

                    <div class="widget-content">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label class="radio">
                                    <input required type="text" name="fname" value="{{$client[0]->name}}" class="form-control"  placeholder="Company Name">
                                </label>
                                <label class="radio">
                                    <input required type="text" name="email" value="{{$client[0]->email}}" class="form-control"  placeholder="Company Email">
                                </label>
                                <label class="radio">
                                    <input required type="text" name="phone" value="{{$client[0]->phone}}" class="form-control"  placeholder="Company Phone">
                                </label>
                                <label class="radio">
                                    <textarea required  cols="5" name="address" class="form-control" placeholder="Company Address">{{@$cperson[0]->address}}</textarea>
                                </label>

                            </div>



                        </div>
                        <div class="form-group">
                        <span style="margin-left:10px;">Tick the checkbox below to show update button</span><br>
                         <input type="checkbox" id="UPROFILE" style="margin-left:10px;" title="Check to show update Button">
                            <button type="submit" class="btn btn-success btn-lg CLIUPDATE" id='PARENTUPDATER' style="margin-left: 20px; display:none;">UPDATE PROFILE DETAILS</button>
                        </div>
                    </div>                    
                </div>


            </div>
        </div>

    </form>
    <form action="{{url('requestchange')}}" method="get">
        {{csrf_field()}}
        <div class="widget box">
            <div class="widget-header">
                <h4><i class="icon-reorder"></i> Change Password</h4>
            </div>
            <div class="widget-content">
                @if(Session::has('psuccess'))
                <div class="alert alert-success">
                    {{Session::get('psuccess')}}
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
                    <!--div class="col-md-6">
                        <label class="radio">
                            <input required type="password" name="password"  class="form-control"  placeholder="New Password">
                        </label>
                        <label class="radio">
                            <input required type="password" name="password_confirmation"  class="form-control"  placeholder="Confirm New Password">
                        </label>                             

                    </div-->
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
