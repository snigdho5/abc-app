<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @include('common.head')
    </head>

    <body class="nav-md myEffect bg-white">
    @if(Auth::check()) @else @yield('content')  @endif   
        <div class="container body ">
            <div class="main_container">
                @guest('admin')

                @else

                <div class="col-md-3 col-sm-4 left_col">
                    <div class="left_col scroll-view bg-white">
                        <div class="navbar nav_title navbar-expand-md"> <a href="{{url('/admin')}}" class="site_title"> <img src="{{ URL::asset('images/abc-logo-lg.svg') }}" alt="logo" class="smImg img-fluid"> <img src="{{ URL::asset('images/abc-logo.svg') }}" alt="logo-lg" class="lgImg"> </a> </div>
                        <div class="clearfix"></div>
                        <!-- menu profile quick info --> 
                        <!--<div class="profile clearfix">
                                            <div class="profile_pic">
                                                <img src="images/img.jpg" alt="..." class="rounded-circle profile_img">
                                            </div>
                                            <div class="profile_info"> <span>Welcome,</span>
                                                 <h2>John Doe</h2>
                                            </div>
                                        </div>--> 
                        <!-- /menu profile quick info --> 
                        <!-- sidebar menu -->
                        @include('common.sidebar')
                        <!-- /sidebar menu --> 
                        <!-- /menu footer buttons -->
                        <!--<div class="sidebar-footer hidden-smd-block d-sm-none d-md-blockall"> <a data-toggle="tooltip" data-placement="top" title="Settings"> <span class="glyphicon glyphicon-cog" aria-hidden="true"></span> </a> <a data-toggle="tooltip" data-placement="top" title="FullScreen"> <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span> </a> <a data-toggle="tooltip" data-placement="top" title="Lock"> <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span> </a> <a data-toggle="tooltip" data-placement="top" title="Logout"
                                            href="login.html"> <span class="glyphicon glyphicon-off" aria-hidden="true"></span> </a> </div>-->
                        <!-- /menu footer buttons --> 
                    </div>
                </div>
                @endguest
                <!-- top navigation -->
                @include('common.navbar')
                <!-- /top navigation --> 
                <!-- page content -->
                <div class="right_col" role="main"> 
                    <!-- top tiles -->
                     @if(Auth::check()) @yield('content') @endif 
                </div>
                <!-- /page content --> 
                <!-- footer content 
                <footer>
                  <div class="float-right">Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a> </div>
                  <div class="clearfix"></div>
                </footer>-->
                <!-- /footer content --> 
            </div>
        </div>
        <!-- jQuery --> 
        @include('common.footer')
    </body>
</html>