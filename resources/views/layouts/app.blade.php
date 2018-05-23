
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    @include('partials.header')
    <body>
        <style>
    .enable{
        display: inline;
    }
    
    .DISABLED{
        display: none !important;
    }
    
    .yummy {
    color: #fff;
    padding: 2px 3px 2px;
    border: 1px solid #9fa3a7;
    text-decoration: none;
    text-shadow: 0 1px 0 rgba(0,0,0,0.3);
    
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    border-radius: 5px;
    -moz-background-clip: padding;
    -webkit-background-clip: padding-box;
    background-clip: padding-box;
    background-color: #ffffff;
    box-shadow:
        0 1px 0 rgba(0,0,0,.08),
        inset 0 1px 2px rgba(255,255,255,.67),
        inset 0 -1px 0 rgba(0,0,0,.14);
 
}
</style>
        <div id="app">
            <nav class="navbar navbar-default navbar-static-top">
                <div class="container">
                    <div class="navbar-header">

                        <!-- Collapsed Hamburger -->
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                            <span class="sr-only">Toggle Navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>

                        <!-- Branding Image -->
                        <a class="navbar-brand" href="{{ url('/home') }}">
                            <img class="yummy" src="{{asset('assets/img/coa.png')}}" alt="NQCL - LIMS" style="height:50px !important;"/>
                        </a>
                        
                    </div>

                    <div class="collapse navbar-collapse" id="app-navbar-collapse">
                        <!-- Left Side Of Navbar -->
                        <ul class="nav navbar-nav">
                            &nbsp;
                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="nav navbar-nav navbar-right">
                            <!-- Authentication Links -->
                            @if (Auth::guest())
                            <li ><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                            @else
                            <li {{@$style}}><a href="{{ route('home') }}">Dashboard</a></li>
                            <li {{@$style}}><a href="{{ route('tracker') }}">Tracker</a></li>
                            <li {{@$style}}><a href="{{ route('finance') }}">Financials</a></li>
                            <li {{@$style}}><a href="{{ route('samples') }}">Samples</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('profile') }}">                                     
                                            Profile
                                        </a>

                                    </li>
                                    @if(Auth::user()->parent !='0')
                                    
                                    @else
                                    <li>
                                        <a href="{{ route('contact_persons') }}">                                     
                                            Contact Persons
                                        </a>

                                    </li>
                                    @endif
                                    <li>
                                        <a href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                   document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>

                                </ul>
                            </li>
                            @endif
                            <li> <div class="pull-right yummy"><img style="width:45px !important;" alt="Court of Arms" src="{{asset('assets/img/logo_main.png')}}"></div></li>
                        </ul>
                        
                    </div>
                  
                </div>
                
            </nav>
            <div style="padding:15px;">
                @yield('content')
            </div>
        </div>

        <!-- Scripts -->

    </body>
  
</html>
