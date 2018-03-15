@extends('layouts.app')

@section('content')
<div class="container">
    <!--=== Page Header ===-->
    @include('partials.mini-header')
    <!-- /Page Header -->

    <!--=== Page Content ===-->
    <!--=== Full Size Inputs ===-->
    <form class="form-horizontal row-border" action="{{url('regcontact')}}"method="post">
        <div class="error" style="color:red; font-weight: bold;">
            @if (count($errors)) 

            <ul>
                @foreach($errors->all() as $error) 
                <li>{{ $error }}</li>
                @endforeach 
            </ul>
            @endif 

        </div>

        {{csrf_field()}}
        <div class="row">
            <div class="col-md-12">
                <div class="widget box">
                    <div class="widget-header">
                        <h4><i class="icon-reorder"></i>New Contact Person</h4>
                    </div>
                    <p>NOTE: All new contact persons default passwords is 123456</p>
                    <input type="hidden" name="client_id" value="{{$client[0]->id}}" class="form-control"  >

                    <div class="widget-content">
                        <div class="form-group">
                            <div class="col-md-6">
                                <label class="radio">
                                    <input required type="text" name="name" value="{{old('name')}}" class="form-control"  placeholder=" Name">
                                </label>
                                <label class="radio">
                                    <input required type="text" name="email" value="{{old('email')}}" class="form-control"  placeholder=" Email">
                                </label>
                                <label class="radio">
                                    <input required type="text" name="phone" value="{{old('phone')}}" class="form-control"  placeholder=" Phone">
                                </label>


                            </div>



                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success btn-lg" style="margin-left: 20px;">Add Contact</button>
                        </div>
                    </div>                    
                </div>


            </div>
        </div>

    </form>


    <!-- /.container -->
</div>
@endsection
