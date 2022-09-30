<?php $__env->startSection('content'); ?>
<?php 
$custData = getTotalCustCount(); 
$reqData = getTotalReqCount();


?>
<div class="tile_count bg-white">
    <div class="row">  <div class="col text-right pb-3"> <span class="current-date-range">1st- <?php echo ordinal(date('t')). " ". date("M y"); ?></span></div></div>
    <div class="row">
       <?php if(Auth::guard('admin')->user()->admin_role == 1){ ?>
        <div class="col  tile_stats_count"> <span class="count_top"> Total Customers</span>
            <div
                class="count"><?php echo e($custData['count']); ?></div>
            <span class="count_bottom">
                <?php if ($custData['perIncr']
                        > 0) { ?>
                    <i class="green"><i class="fa fa-sort-asc"></i><?php echo e(round($custData['perIncr'],1)); ?>% </i>
                <?php } else if ($custData['perIncr']
                        < 0) { ?>
                    <i class="red"><i class="fa fa-sort-down"></i><?php echo e(round(abs($custData['perIncr']),1)); ?>% </i>
                <?php } else { ?>
                    <i class="green"><?php echo e(round(abs($custData['perIncr']),1)); ?>% </i>
<?php } ?>
                From last Month
            </span> </div>
        <div class="col   tile_stats_count"> <span class="count_top"> This Month</span>
            <div
                class="count"><?php echo e($custData['currmonth']); ?></div>
            <span class="count_bottom">
                <?php if ($custData['perDiffWeek']
                        > 0) { ?>
                    <i class="green"><i class="fa fa-sort-asc"></i><?php echo e(round($custData['perDiffWeek'],1)); ?>% </i>
                <?php } else if ($custData['perDiffWeek']
                        < 0) { ?>
                    <i class="red"><i class="fa fa-sort-down"></i><?php echo e(round(abs($custData['perDiffWeek']),1)); ?>% </i>
<?php } else { ?>
                    <i class="green"><?php echo e(round(abs($custData['perDiffWeek']),1)); ?>% </i>
<?php } ?>
                From last Week
            </span> </div>
	    <?php }?>
        <div class="col   tile_stats_count"> <span class="count_top"> Confirmed Bookings</span>
            <div
                class="count green"><?php echo e($reqData['new_req_count']); ?></div>
            <span class="count_bottom">
                <?php if ($reqData['newperDiffWeek']
                        > 0) { ?>
                    <i class="green"><i class="fa fa-sort-asc"></i><?php echo e(round($reqData['newperDiffWeek'],1)); ?>% </i>
<?php } else if ($reqData['newperDiffWeek']
        < 0) { ?>
                    <i class="red"><i class="fa fa-sort-down"></i><?php echo e(round(abs($reqData['newperDiffWeek']),1)); ?>% </i>
<?php } else { ?>
                    <i class="green"><?php echo e(round(abs($reqData['newperDiffWeek']),1)); ?>% </i>
                <?php } ?>
                From last Week
            </span> </div>
        <div class="col   tile_stats_count"> <span class="count_top"> Pending Bookings</span>
            <div
                class="count"><?php echo e($reqData['prog_req_count']); ?></div>
            <span class="count_bottom">
                <?php if ($reqData['progperDiffWeek']
                        > 0) { ?>
                    <i class="green"><i class="fa fa-sort-asc"></i><?php echo e(round($reqData['progperDiffWeek'],1)); ?>% </i>
<?php } else if ($reqData['progperDiffWeek']
        < 0) { ?>
                    <i class="red"><i class="fa fa-sort-down"></i><?php echo e(round(abs($reqData['progperDiffWeek']),1)); ?>% </i>
                <?php } else { ?>
                    <i class="green"><?php echo e(round(abs($reqData['progperDiffWeek']),1)); ?>% </i>
                <?php } ?>
                From last Week
            </span> </div>
        
        <div class="col   tile_stats_count"> <span class="count_top"> Total Bookings</span>
            <div
                class="count"><?php echo e($reqData['total_req_count']); ?></div>
            <span class="count_bottom">
<?php if ($reqData['perIncr']
        > 0) { ?>
                    <i class="green"><i class="fa fa-sort-asc"></i><?php echo e(round($reqData['perIncr'],1)); ?>% </i>
<?php } else if ($reqData['perIncr']
        < 0) { ?>
                    <i class="red"><i class="fa fa-sort-down"></i><?php echo e(round(abs($reqData['perIncr']),1)); ?>% </i>
<?php } else { ?>
                    <i class="green"><?php echo e(round(abs($reqData['perIncr']),1)); ?>% </i>
<?php } ?>
                From last Month
            </span> </div>
        <!--<div class="col-lg-2 col-md-4 col-6 tile_stats_count">

            <span class="count_top"><i class="fa fa-user"></i> Total Connections</span>

            <div class="count">7,325</div>

            <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>

          </div>--></div>
</div>
<!-- /top tiles -->

<div class="row">
    <div class="col-12 col-md-12 col-lg-12 col-xl-5   d-flex ">
        <div class="col-12 x_panel tile  borRadiShad">
            <div class="x_title">
                <h2>Booking History</h2>

                <div class="clearfix"></div>
            </div>
            <div class="x_content row m-0">
                <div class="col-lg-6 col-sm-6 col-12 col-xl-5  mt-4 proj310 p-0">
                    <canvas id="canvasDoughnut" height="200" width="200"></canvas>
                    <samp>  <strong><?php echo e($reqData['total_req_count']); ?></strong></samp> </div>
                <div class="col-lg-6 col-sm-5 col-12 col-xl-7  mt-5 float-right ml-auto pr-0">
                    <table class="tile_info bg-light mt-2">
                        <tr>
                            <td><i class="fa fa-square blue"></i>Confirmed- <strong id="prog_req_count"><?php echo e($reqData['new_req_count']); ?></strong></td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-square  lightGreen"></i>Pending  - <strong id="new_req_count"><?php echo e($reqData['prog_req_count']); ?></strong></td>
                        </tr>
                        <tr>
                            <td><i class="fa fa-square text-warning "></i>Cancelled - <strong id="closed_req_count"><?php echo e($reqData['canceled_req_count']); ?></strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-12 col-xl-7 mt-4 mt-xl-0  d-flex ">
	      <?php if(Auth::guard('admin')->user()->admin_role == 1){ ?>
        <div class="col-12 dashboard_graph border borRadiShad">
            <div class="row x_title m-0">
                <div class="col-lg-6 p-0">
                    <h2 class="size-16">Customer Summary</h2>
                </div>
                <div class="col-lg-6">
                    <?php
                        $first_day = date('m-01-Y'); // hard-coded '01' for first day
                        $last_day = date('m-t-Y');
                    ?>
<!--                    <div id="reportrange" class="float-right border p-1"> <i class="glyphicon glyphicon-calendar fa fa-calendar"></i> <span><?php echo e(date("M j, Y",strtotime($first_day))); ?> - <?php echo e(date("M j, Y",strtotime($last_day))); ?></span> <b
                            class="caret"></b> </div>-->
                    <!--<div><span>Last 6 Months Trend</span></div>-->
                    <div class="col-lg-6 p-0">
                    <h2 class="size-16">Last 6 months trend</h2>
                </div>
                </div>
            </div>
		    
            <div class="row m-0">
                <div class="col-lg-9 col-md-9 col-12 mt-4" id="graph-container">
                    <input type="hidden" id="closed_req" value="<?php echo e($custData['appr_cust_count6']); ?>">
                    <input type="hidden" id="cancel_req" value="<?php echo e($custData['pend_cust_count6']); ?>">
                    <canvas id="orderSummary" class="demo-placeholder" ></canvas>

                </div>
                <div class="col-lg-3 col-md-3 col-12 bg-white pt-5">
                    <div class="col-12 this-month-text"><h2 class="pb-2 size-14">This Month</h2></div>

                    <div class="col-lg-12 col-md-12 col-12 mb-2 mb-xl-0">
                        <div class="col pl-0 pr-0 pt-3">
                            <p class="mb-2 size-12">Approved  - <strong class="text-success float-right closed-req-cnt"><?php echo e($custData['appr_cust_count']); ?></strong></p>

                            <div class="progress progress_sm">
                                <div class="progress-bar bg-success closed-req-cnt-bar" role="progressbar" data-transitiongoal="<?php echo e($custData['appr_cust_count']); ?>"></div>
                            </div>

                        </div>
                        <div class="col pl-0 pr-0 pt-3">
                            <p class="mb-2 size-12">Pending - <strong class="text-warning float-right cancel-req-cnt"><?php echo e($custData['pend_cust_count']); ?></strong></p>

                            <div class="progress progress_sm">
                                <div class="progress-bar bg-warning cancel-req-cnt-bar" role="progressbar" data-transitiongoal="<?php echo e($custData['pend_cust_count']); ?>"></div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
		

		
        </div>
	    <?php }?>
	    
    </div>
</div>
<div class="row pt-4">
    <div class="col-lg-12 col-md-12 col-12">
        <div class="x_panel bgHistory">
            <div class="x_title">
                <h2>New Bookings</h2>
<?php $pendingRequestList = getPendingRequest(); ?>
                <div class="clearfix"></div>
            </div>
            <div class="x_content mt-4">

                <div class="table-responsive">
<?php if (count($pendingRequestList)
        > 0) { ?>
                        <table class="table table-striped">
                            <thead>
                                <tr class="headings">

                                    <th class="column-title">Booking ID</th>
                                    <th class="column-title">Customer Name</th>
                                    <th class="column-title">Mobile</th>
                                    <th class="column-title">Booking Date</th>
                                    <th class="column-title">Booking Amount</th>
                                    <th class="column-title">Status</th>
                                    <th class="column-title">Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $pendingRequestList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key =>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="even pointer">

                                    <td class=" "><?php echo e($value->booking_code); ?></td>
                                    <td class=" "><?php echo e($value->user_name); ?></td>
                                    <td class=" ">+91 <?php echo e($value->user_phone); ?></td>
                                    <td class=" "><?php echo date("d M Y",strtotime($value->created_at)); ?></td>
                                    <td class=" "><?php echo e($value->tot_book_amnt); ?> INR</td>
                                    <td class=" "><?php 
                                        if($value->book_status ==1){
                                           echo "Pending"; 
                                        }
                                        if($value->book_status ==2){
                                           echo "Confirmed"; 
                                        }
                                        if($value->book_status ==3){
                                           echo "Pay at centre"; 
                                        }
                                        if($value->book_status ==4){
                                           echo "Cancelled"; 
                                        }
?></td>
                                    <td class=" last">
                                        <a href="<?php echo e(route('booking.data',base64_encode($value->booking_id))); ?>" title="Edit" class=" btn btn-secondary btnp">View</a></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                <?php } else { ?>
                        No data
<?php } ?>
                </div>

<?php if (count($pendingRequestList)
        > 5) { ?>
                    <div class="row">
                        <div class="col-12 text-right"><a href="<?php echo e(route('booking.manage')); ?>" class="btn btn-outline-secondary pl-4 pr-4 m-0">View More</a></div>


                    </div>
<?php } ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>