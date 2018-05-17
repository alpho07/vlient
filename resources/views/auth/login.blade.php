@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default box">
                <div class="panel-heading">NQCL - CLIENT LOGIN</div>
                <div class="panel-body">
                    @if(Session::has('message'))
                    <div class="form-group has-error" style="font-weight: bold; color:red;"> {{Session::get('message')}}</div>                   
                    @endif
                    <form class="form-horizontal login-form" method="POST" action="{{ route('logincustom') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">


                            <div class="col-md-6 col-md-offset-3">
                                <div class="input-icon">
                                    <i class="icon-user"></i>
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus placeholder="E-Mail / Phone">
                                </div>
                                @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">

                            <div class="col-md-6 col-md-offset-3">
                                <div class="input-icon">
                                    <i class="icon-lock"></i>
                                    <input id="password" type="password" class="form-control" name="password" required placeholder="Password">
                                </div>
                                @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <!--div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div-->

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>

                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    Forgot Your Password?
                                </a>
                            </div>
                        </div>
                        
                          <div class="form-group">
                            <div class="">
                                <hr>
                                <p>Login or <a href="{{url('register')}}">Register Here</a> 
                                <hr>
                                <center>&copy{{date('Y')}}</center>
                            </div>
                        </div>
                        
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
