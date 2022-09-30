@guest('admin')

@else
<?php $notidata = getNotificationAdmin(); ?>
<div class="top_nav">
    <div class="nav_menu">
        <nav class="">
            <div class="nav toggle"> <a id="menu_toggle"><i class="fa fa-bars"></i></a> </div>
            <ul class="nav navbar-nav navbar-expand-sm navbar-right ">
<!--             <li id="search_button" style="display:none"><button class="btn btn-secondary border-0" ><i class="fa fa-search"></i></button></li>-->
                <?php if (count($notidata['notidata']) > 0) { ?>
                    <li role="presentation"  class="dropdown nav-item"> 
                        <a href="javascript:;" id="notiCount" class="dropdown-toggle info-number nav-link" data-toggle="dropdown"
                           aria-expanded="false"><i class="fa fa-bell"></i><span class="badge bg-green">{{$notidata['noticount']}}</span> </a>
                        <ul id="menu1" class="dropdown-menu list-unstyled msg_list"role="menu">
                            @foreach($notidata['notidata'] as $key=>$value)

                            <li class="dropdown-item"> <a href="{{route('booking.data',base64_encode($value->booking_id))}}"><i class="fa fa-bell"></i> <span> <span class="time">{{get_time_difference_php($value->created_at)}}</span> </span> <span class="message">{{reduceWords($value->user_name.' has a service request')}} </span> </a> </li>
                            @endforeach
                            <li class="dropdown-item">
                                <div class="text-center"> <a href="{{route('booking.manage')}}"> <strong>See All Bookings</strong> <i class="fa fa-angle-right"></i> </a> </div>
                            </li>
                        </ul>
                    </li>
                <?php } else { ?>
                    <li role="presentation"  class="dropdown nav-item"> 
                        <a href="javascript:;" id="notiCount" class="dropdown-toggle info-number nav-link" data-toggle="dropdown"
                           aria-expanded="false"><i class="fa fa-bell"></i> </a>
                        <ul id="menu1" class="dropdown-menu list-unstyled msg_list"role="menu">
                            <li class="dropdown-item">
                                <div class="text-center"> <a> <strong>No Notifications</strong> <i class="fa fa-angle-right"></i> </a> </div>
                            </li>
                        </ul>
                    </li>
                <?php } ?>
                <li class="nav-item pl-3 pr-3"> <a href="javascript:;" class="user-profile dropdown-toggle nav-link" data-toggle="dropdown"aria-expanded="false"> <i class="fa fa-user"></i> <?php echo Auth::guard('admin')->user()->user_name?><span class=" fa fa-angle-down"></span> </a>
                    <ul class="dropdown-menu dropdown-usermenu float-right">
                        <li class="dropdown-item"><a href="{{ route('admin.changePwd') }}"> Profile</a></li>
<!--                        <li class="dropdown-item"><a href="javascript:;">Help</a> </li>-->
                        <li class="dropdown-item"><a href="{{ route('admin.auth.logout') }}"
                                                     onclick="event.preventDefault();
                                                             document.getElementById('logout-form').submit();"><i class="fa fa-sign-out float-right"></i> Log Out</a> </li>
                    </ul>
                </li>
<!--                <li> <a href="#"><img src="{{ URL::asset('images/setting.svg') }}" alt="setting"></a> </li>-->
            </ul>
        </nav>
           
    </div>
</div>


<form id="logout-form" action="{{ route('admin.auth.logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>
@endguest