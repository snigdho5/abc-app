@guest('admin')


@else
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section"> 
        <!--<h3>General</h3>-->
        <ul class="nav side-menu">
            <li class="nav-item dashboard"><a href="{{url('/admin')}}" class="nav-link dashboard-link"><span>Dashboard</span></a> </li>

            @if(Auth::guard('admin')->user()->admin_role==1 || Auth::guard('admin')->user()->admin_role==2)
            <li class="nav-item services"><a class="nav-link"><span>Manage Services</span></a>
                <ul class="nav child_menu">

                    @if(Auth::guard('admin')->user()->admin_role==2) 
                    <li class="nav-item"><a href="{{route('meetingroom.managecenterconfig')}}" class="nav-link">Manage Centre configuration</a> </li>	
                    <li class="nav-item"><a href="{{route('sprtservtocomp.manage')}}" class="nav-link">Tag Support Service to Centre</a> </li>
                    @endif

                    @if(Auth::guard('admin')->user()->admin_role==1) 
                    <li class="nav-item"><a href="{{route('meetingroom.manage')}}" class="nav-link">Manage Centre configuration</a> </li>
                    <!--<li class="nav-item"><a href="{{route('vopackage.manage')}}" class="nav-link">Manage VO Packages</a> </li>-->



                    <li class="nav-item"><a href="{{route('tax.manage')}}" class="nav-link">Manage Taxes</a> </li>
                    @endif
                </ul>
            </li>
            <li class="nav-item  notepad"><a class="nav-link"><span>Manage Bookings</span></a>
                <ul class="nav child_menu">
                    @if(Auth::guard('admin')->user()->admin_role==2) 
                    <li class="nav-item"><a href="{{route('booking.create')}}" class="nav-link">Create  Booking</a> </li>
                    @endif
                    <li class="nav-item"><a href="{{route('booking.manage')}}" class="nav-link">Manage  Bookings</a> </li>


                    <!--<li class="nav-item"><a href="#" class="nav-link">Add New Booking</a> </li>-->
                </ul>
            </li>
            @if(Auth::guard('admin')->user()->admin_role==1) 
            <li class="nav-item"><a class="nav-link"><span>Manage Managers</span></a>
                <ul class="nav child_menu">


                    <li class="nav-item"><a href="{{route('manager.manage-manager'). '#ManagerAdminForm'}}" onclick="scrollToCustomerForm()" class="nav-link">Add New Manager</a> </li>    
                    <li class="nav-item"><a href="{{route('manager.manage-manager')}}" class="nav-link">Manage Managers</a> </li>
                </ul>
            </li>
            @endif
            <li class="nav-item"><a class="nav-link"><span>Manage Corporate <br/>Client</span></a>
                <ul class="nav child_menu">
                    <li class="nav-item"><a href="{{route('companyclient.manage')}}" class="nav-link">Manage Company</a> </li>
                    <li class="nav-item"><a href="{{route('company.manage')}}" class="nav-link">Manage Client</a> </li>
                    <li class="nav-item"><a href="{{route('companyoffer.manage')}}" class="nav-link">Manage Client Offerings</a> </li>
                    
                </ul>
            </li>

@if(Auth::guard('admin')->user()->admin_role==1) 
            <li class="nav-item customer"><a class="nav-link"><span>Manage Customers</span></a>
                <ul class="nav child_menu">
                    <li class="nav-item"><a href="{{route('customer.manage')}}" class="nav-link">View Customers</a> </li>
                    <li class="nav-item"><a href="{{route('customer.manage'). '#AddForm'}}" onclick="scrollToCustomerForm()" class="nav-link">Add New Customer</a> </li>
                </ul>
            </li>



            <li class="nav-item busines"><a class="nav-link"><span>Manage Business <br/>
                        Connect</span></a>
                <ul class="nav child_menu">
                    <li class="nav-item"><a href="{{route('scustomer.manage')}}"  class="nav-link">View Customers</a> </li>
                    <li class="nav-item"><a href="{{route('post.manage')}}"  class="nav-link"> Moderate Customer's Post</a> </li>
                </ul>
            </li>
            
            <li class="nav-item  notepad"><a class="nav-link"><span>Manage Forms Data</span></a>
                <ul class="nav child_menu">
                    <li class="nav-item"><a href="{{route('query.manage')}}" class="nav-link">Manage Query</a> </li>
                    <li class="nav-item"><a href="{{route('quote.manage')}}" class="nav-link">Manage Quotes</a> </li>
                    <li class="nav-item"><a href="{{route('pquote.manage')}}" class="nav-link">Manage Partnership Quotes</a> </li>
                    <li class="nav-item"><a href="{{route('rac.manage')}}" class="nav-link">Manage Refer a Client</a> </li>
                </ul>
            </li>
            
            
            @endif

            <li class="nav-item cms"><a class="nav-link"><span>Manage CMS Pages</span></a>
                <ul class="nav child_menu">
                    @if(Auth::guard('admin')->user()->admin_role==1) 
                    <li class="nav-item"><a href="{{route('news.manage')}}" class="nav-link">Manage News</a> </li>
                    <li class="nav-item"><a href="{{route('offer.manage')}}" class="nav-link">Manage Offer</a> </li>
                    <!--<li class="nav-item"><a href="{{route('introdata.manage')}}" class="nav-link">Manage Intro Page </a> </li>-->
<!--                    <li class="nav-item"><a href="{{route('discount.manage')}}" class="nav-link">Manage Discount</a> </li>-->
                    <li class="nav-item"><a href="{{route('event.manage')}}" class="nav-link">Manage Events</a> </li>
                    @endif
                    <li class="nav-item"><a href="{{route('centreloc.manage')}}" class="nav-link">Send Center Location</a> </li>
                    <li class="nav-item"><a href="{{route('applink.manage')}}" class="nav-link">Send Application Link</a> </li>
                    <li class="nav-item"><a href="{{route('virtualtour.manage')}}" class="nav-link">Manage Virtual Tour </a> </li>
                    <li class="nav-item"><a href="{{route('emailmatrix.manage')}}" class="nav-link">Manage Email Matrix</a> </li>
					@if(Auth::guard('admin')->user()->admin_role==1) 
                    <li class="nav-item"><a href="{{route('clientbenefits.manage')}}" class="nav-link">Manage Client Benefits</a> </li>
					@endif
                </ul>
            </li>
            @if(Auth::guard('admin')->user()->admin_role==1) 

            <li class="nav-item  notepad"><a class="nav-link"><span>Manage Order <br> Cancellation Rules</span></a>
                <ul class="nav child_menu">
                    <li class="nav-item"><a href="{{route('cbooking.manage')}}" class="nav-link">View Cancelled Requests</a> </li>
                    <li class="nav-item"><a href="{{route('ctext.manage')}}" class="nav-link">Update Cancellation Text</a> </li>
                </ul>
            </li>

            <li class="nav-item master"><a class="nav-link"><span>Manage Master Info</span></a>
                <ul class="nav child_menu">
 <li class="nav-item"><a href="{{route('category.manage')}}" class="nav-link">Manage Service Category</a> </li>
                    <li class="nav-item"><a href="{{route('msinfo.manage')}}" class="nav-link">Manage Service Configuration</a> </li>
                   
                    <li class="nav-item"><a href="{{route('location.manage')}}" class="nav-link">Manage City/Location</a> </li>
                    <li class="nav-item"><a href="{{route('centre.manage')}}" class="nav-link">Manage Centre</a> </li>
                    <li class="nav-item"><a href="{{route('sprtserv.manage')}}" class="nav-link">Manage Support Service</a> </li>

                    <li class="nav-item"><a href="{{route('sprtservtocomp.manage')}}" class="nav-link">Tag Support Service to Centre</a> </li>
                </ul>

            </li>
            <li class="nav-item"><a  class="nav-link"><span>Manage Notifications</span></a>
                <ul class="nav child_menu">
                    <li class="nav-item"><a href="{{route('notification.manage')}}" class="nav-link">Send Messages</a> </li>
                </ul>
            </li> 
            @endif
            @endif
        </ul>
    </div>
</div>
@endguest 