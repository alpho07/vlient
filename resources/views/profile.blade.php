@extends('layouts.app')

@section('content')
<div class="container">
    <!--=== Page Header ===-->
    @include('partials.mini-header')
    <!-- /Page Header -->

    <!--=== Page Content ===-->
    <!--=== Full Size Inputs ===-->
    @if(Auth::user()->parent != '0')
    <form class="form-horizontal row-border" action="{{url('updatec')}}"method="post">

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
                            <button type="submit" class="btn btn-success btn-lg" style="margin-left: 20px;">UPDATE PROFILE DETAILS</button>
                        </div>
                    </div>                    
                </div>


            </div>
        </div>

    </form>
    <form action="{{url('update_passwordc')}}" method="post">
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
                    <button type="submit" class="btn btn-success btn-lg" style="margin-left: 20px;">CHANGE PASSWORD</button>
                </div>
            </div>                    
        </div>
    </form>
    @else
    <form class="form-horizontal row-border" action="{{url('cupdate')}}"method="post">

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
                            <button type="submit" class="btn btn-success btn-lg" style="margin-left: 20px;">UPDATE PROFILE DETAILS</button>
                        </div>
                    </div>                    
                </div>


            </div>
        </div>

    </form>
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
                    <button type="submit" class="btn btn-success btn-lg" style="margin-left: 20px;">CHANGE PASSWORD</button>
                </div>
            </div>                    
        </div>
    </form>
    @endif

    <!-- /.container -->
</div>
@endsection
