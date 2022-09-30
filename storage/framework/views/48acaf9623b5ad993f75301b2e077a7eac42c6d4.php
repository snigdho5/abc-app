<?php if(auth()->guard('admin')->guest()): ?>


<?php else: ?>
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section"> 
        <!--<h3>General</h3>-->
        <ul class="nav side-menu">
            <li class="nav-item dashboard"><a href="<?php echo e(url('/admin')); ?>" class="nav-link dashboard-link"><span>Dashboard</span></a> </li>

            <?php if(Auth::guard('admin')->user()->admin_role==1 || Auth::guard('admin')->user()->admin_role==2): ?>
            <li class="nav-item services"><a class="nav-link"><span>Manage Services</span></a>
                <ul class="nav child_menu">

                    <?php if(Auth::guard('admin')->user()->admin_role==2): ?> 
                    <li class="nav-item"><a href="<?php echo e(route('meetingroom.managecenterconfig')); ?>" class="nav-link">Manage Centre configuration</a> </li>	
                    <li class="nav-item"><a href="<?php echo e(route('sprtservtocomp.manage')); ?>" class="nav-link">Tag Support Service to Centre</a> </li>
                    <?php endif; ?>

                    <?php if(Auth::guard('admin')->user()->admin_role==1): ?> 
                    <li class="nav-item"><a href="<?php echo e(route('meetingroom.manage')); ?>" class="nav-link">Manage Centre configuration</a> </li>
                    <li class="nav-item"><a href="<?php echo e(route('vopackage.manage')); ?>" class="nav-link">Manage VO Packages</a> </li>



                    <li class="nav-item"><a href="<?php echo e(route('tax.manage')); ?>" class="nav-link">Manage Taxes</a> </li>
                    <?php endif; ?>
                </ul>
            </li>
            <li class="nav-item  notepad"><a class="nav-link"><span>Manage Bookings</span></a>
                <ul class="nav child_menu">
                    <?php if(Auth::guard('admin')->user()->admin_role==2): ?> 
                    <li class="nav-item"><a href="<?php echo e(route('booking.create')); ?>" class="nav-link">Create  Booking</a> </li>
                    <?php endif; ?>
                    <li class="nav-item"><a href="<?php echo e(route('booking.manage')); ?>" class="nav-link">Manage  Bookings</a> </li>


                    <!--<li class="nav-item"><a href="#" class="nav-link">Add New Booking</a> </li>-->
                </ul>
            </li>
            <?php if(Auth::guard('admin')->user()->admin_role==1): ?> 
            <li class="nav-item"><a class="nav-link"><span>Manage Sub Admin</span></a>
                <ul class="nav child_menu">


                    <li class="nav-item"><a href="<?php echo e(route('manager.manage-manager'). '#ManagerAdminForm'); ?>" onclick="scrollToCustomerForm()" class="nav-link">Add New Manager</a> </li>    
                    <li class="nav-item"><a href="<?php echo e(route('manager.manage-manager')); ?>" class="nav-link">Manage Manager</a> </li>
                </ul>
            </li>
            <li class="nav-item"><a class="nav-link"><span>Manage Corporate <br/>Client</span></a>
                <ul class="nav child_menu">
                    <li class="nav-item"><a href="<?php echo e(route('company.manage')); ?>" class="nav-link">Manage Client</a> </li>
                    <li class="nav-item"><a href="<?php echo e(route('companyoffer.manage')); ?>" class="nav-link">Manage Client Offerings</a> </li>
                </ul>
            </li>


            <li class="nav-item customer"><a class="nav-link"><span>Manage Customers</span></a>
                <ul class="nav child_menu">
                    <li class="nav-item"><a href="<?php echo e(route('customer.manage')); ?>" class="nav-link">View Customers</a> </li>
                    <li class="nav-item"><a href="<?php echo e(route('customer.manage'). '#AddForm'); ?>" onclick="scrollToCustomerForm()" class="nav-link">Add New Customer</a> </li>
                </ul>
            </li>



            <li class="nav-item busines"><a class="nav-link"><span>Manage Business <br/>
                        Connect</span></a>
                <ul class="nav child_menu">
                    <li class="nav-item"><a href="<?php echo e(route('scustomer.manage')); ?>"  class="nav-link">View Customers</a> </li>
                    <li class="nav-item"><a href="<?php echo e(route('post.manage')); ?>"  class="nav-link"> Moderate Customer's Post</a> </li>
                </ul>
            </li>
            
            <li class="nav-item  notepad"><a class="nav-link"><span>Manage Forms Data</span></a>
                <ul class="nav child_menu">
                    <li class="nav-item"><a href="<?php echo e(route('query.manage')); ?>" class="nav-link">Manage Query</a> </li>
                    <li class="nav-item"><a href="<?php echo e(route('quote.manage')); ?>" class="nav-link">Manage Quotes</a> </li>
                    <li class="nav-item"><a href="<?php echo e(route('pquote.manage')); ?>" class="nav-link">Manage Partnership Quotes</a> </li>
                    <li class="nav-item"><a href="<?php echo e(route('rac.manage')); ?>" class="nav-link">Manage Refer a Client</a> </li>
                </ul>
            </li>
            
            
            <?php endif; ?>

            <li class="nav-item cms"><a class="nav-link"><span>Manage CMS Pages</span></a>
                <ul class="nav child_menu">
                    <?php if(Auth::guard('admin')->user()->admin_role==1): ?> 
                    <li class="nav-item"><a href="<?php echo e(route('news.manage')); ?>" class="nav-link">Manage News</a> </li>
                    <li class="nav-item"><a href="<?php echo e(route('offer.manage')); ?>" class="nav-link">Manage Offer</a> </li>
                    <li class="nav-item"><a href="<?php echo e(route('introdata.manage')); ?>" class="nav-link">Manage Intro Page </a> </li>
<!--                    <li class="nav-item"><a href="<?php echo e(route('discount.manage')); ?>" class="nav-link">Manage Discount</a> </li>-->
                    <li class="nav-item"><a href="<?php echo e(route('event.manage')); ?>" class="nav-link">Manage Events</a> </li>
                    <?php endif; ?>
                    <li class="nav-item"><a href="<?php echo e(route('centreloc.manage')); ?>" class="nav-link">Send Center Location</a> </li>
                    <li class="nav-item"><a href="<?php echo e(route('applink.manage')); ?>" class="nav-link">Send Application Link</a> </li>
                    <li class="nav-item"><a href="<?php echo e(route('virtualtour.manage')); ?>" class="nav-link">Manage Virtual Tour </a> </li>
                    <li class="nav-item"><a href="<?php echo e(route('emailmatrix.manage')); ?>" class="nav-link">Manage Email Matrix</a> </li>

                </ul>
            </li>
            <?php if(Auth::guard('admin')->user()->admin_role==1): ?> 

            <li class="nav-item  notepad"><a class="nav-link"><span>Manage Order <br> Cancellation Rules</span></a>
                <ul class="nav child_menu">
                    <li class="nav-item"><a href="<?php echo e(route('cbooking.manage')); ?>" class="nav-link">View Cancelled Requests</a> </li>
                    <li class="nav-item"><a href="<?php echo e(route('ctext.manage')); ?>" class="nav-link">Update Cancellation Text</a> </li>
                </ul>
            </li>

            <li class="nav-item master"><a class="nav-link"><span>Manage Master Info</span></a>
                <ul class="nav child_menu">
 <li class="nav-item"><a href="<?php echo e(route('category.manage')); ?>" class="nav-link">Manage Service Category</a> </li>
                    <li class="nav-item"><a href="<?php echo e(route('msinfo.manage')); ?>" class="nav-link">Manage Service Configuration</a> </li>
                   
                    <li class="nav-item"><a href="<?php echo e(route('location.manage')); ?>" class="nav-link">Manage City/Location</a> </li>
                    <li class="nav-item"><a href="<?php echo e(route('centre.manage')); ?>" class="nav-link">Manage Centre</a> </li>
                    <li class="nav-item"><a href="<?php echo e(route('sprtserv.manage')); ?>" class="nav-link">Manage Support Service</a> </li>

                    <li class="nav-item"><a href="<?php echo e(route('sprtservtocomp.manage')); ?>" class="nav-link">Tag Support Service to Centre</a> </li>
                </ul>

            </li>
            <?php endif; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>
<?php endif; ?> 