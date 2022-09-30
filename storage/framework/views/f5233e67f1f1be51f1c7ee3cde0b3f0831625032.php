<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- CSRF Token -->
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <?php echo $__env->make('common.head', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </head>

    <body class="nav-md myEffect bg-white">
    <?php if(Auth::check()): ?> <?php else: ?> <?php echo $__env->yieldContent('content'); ?>  <?php endif; ?>   
        <div class="container body ">
            <div class="main_container">
                <?php if(auth()->guard('admin')->guest()): ?>

                <?php else: ?>

                <div class="col-md-3 col-sm-4 left_col">
                    <div class="left_col scroll-view bg-white">
                        <div class="navbar nav_title navbar-expand-md"> <a href="<?php echo e(url('/admin')); ?>" class="site_title"> <img src="<?php echo e(URL::asset('images/abc-logo-lg.svg')); ?>" alt="logo" class="smImg img-fluid"> <img src="<?php echo e(URL::asset('images/abc-logo.svg')); ?>" alt="logo-lg" class="lgImg"> </a> </div>
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
                        <?php echo $__env->make('common.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                        <!-- /sidebar menu --> 
                        <!-- /menu footer buttons -->
                        <!--<div class="sidebar-footer hidden-smd-block d-sm-none d-md-blockall"> <a data-toggle="tooltip" data-placement="top" title="Settings"> <span class="glyphicon glyphicon-cog" aria-hidden="true"></span> </a> <a data-toggle="tooltip" data-placement="top" title="FullScreen"> <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span> </a> <a data-toggle="tooltip" data-placement="top" title="Lock"> <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span> </a> <a data-toggle="tooltip" data-placement="top" title="Logout"
                                            href="login.html"> <span class="glyphicon glyphicon-off" aria-hidden="true"></span> </a> </div>-->
                        <!-- /menu footer buttons --> 
                    </div>
                </div>
                <?php endif; ?>
                <!-- top navigation -->
                <?php echo $__env->make('common.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <!-- /top navigation --> 
                <!-- page content -->
                <div class="right_col" role="main"> 
                    <!-- top tiles -->
                     <?php if(Auth::check()): ?> <?php echo $__env->yieldContent('content'); ?> <?php endif; ?> 
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
        <?php echo $__env->make('common.footer', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </body>
</html>