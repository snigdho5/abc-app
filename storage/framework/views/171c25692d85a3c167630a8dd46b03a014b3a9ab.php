<?php $__env->startSection('content'); ?>


<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel m-0">
		<div class="row"> 
	<div class="col-lg-6 col-md-6 col-12">	
          <h2>Select Service </h2>
	</div>
		<div class="col-lg-6 col-md-6 col-12 ml-auto text-right">	
        <h2 >Welcome <?php echo Auth::guard('admin')->user()->user_name;?></h2>
	</div>
		</div>
	 
                <div class="clearfix"></div>
  <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="row pt-3">

    <div class="col-lg-12 col-md-12 col-12">
	     <form method="post" id="centreRegister" action="<?php echo e(route('meetingroom.editmanager')); ?> " enctype="multipart/form-data" > 
	     
  <div class="x_panel newRequestForm">
                <div class="row mt-4 configdiv">
                    <div class="col-md-12 mb-3">
                        <div class="accordion" id="accordionExample">
                            <?php $i = 0; ?>
                            <?php $__currentLoopData = $minfoData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            $i++;
                            $cls = '';
                            if (isset($cat_changed) && $cat_changed != '') {
                                if ($value->acat_id == $cat_changed) {
                                    $cls = 'show';
                                }
                            } else {
                                if ($i == 1) {
                                    $cls = 'show';
                                }
                            }
                            if ($value->acat_id == $cinfoData->flag_abc_lounge || $value->acat_id == $cinfoData->flag_built_to_suit || $value->acat_id == $cinfoData->flag_virtual_office || $value->acat_id == $cinfoData->flag_ser_office || $value->acat_id == $cinfoData->flag_co_working || $value->acat_id == $cinfoData->flag_meeting_room || $value->acat_id == 7 || $value->acat_id == 8) {
                                ?>
                                <div class="card">
                                    <div class="card-header" id="headingOne">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne_<?php echo e($value->acat_id); ?>" aria-expanded="true" aria-controls="collapseOne_<?php echo e($value->acat_id); ?>">
                                                <?php echo e($value->acat_name); ?>

                                            </button>
                                        </h2>
                                    </div>

                                    <div id="collapseOne_<?php echo e($value->acat_id); ?>" class="collapse <?php echo $cls; ?>" aria-labelledby="headingOne" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div id="loader" style="display:none;"></div>
                                            <table  class="table table-striped table-responsive">
                                                <thead>
                                                    <tr class="headings">
                                                        <th class="column-title"> Configuration Type</th>
                  <th class="column-title">Seater</th>
                <?php if($value->flag_hour == 1): ?>
            <th class="column-title">Rate/hour</th>
          <?php endif; ?>
            <?php if($value->flag_halfday == 1): ?>
        <th class="column-title">Half Day</th>
 <?php endif; ?>
    <?php if($value->flag_fullday == 1): ?>
      <th class="column-title">Full Day</th>
       <?php endif; ?>
          <?php if($value->flag_month == 1): ?>
        <th class="column-title">Rate/Month</th>
        <?php endif; ?>
   <?php if($value->flag_quart == 1): ?>
      <th class="column-title">Rate/Qtrly</th>
       <?php endif; ?>
     <?php if($value->flag_halfyear == 1): ?>
     <th class="column-title">Rate/Half Yearly</th>
     <?php endif; ?>
     <?php if($value->flag_year == 1): ?>
      <th class="column-title">Rate/Yearly</th>
       <?php endif; ?>
        <th class="column-title">Add Inventory</th>  
	<th class="column-title">Action</th>  
	
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $rate_hour = 0.00;
                                                    $rate_half = 0.00;
                                                    $rate_full = 0.00;
                                                    $rate_month = 0.00;
                                                    $rate_qtr = 0.00;
                                                    $rate_hy = 0.00;
                                                    $rate_yr = 0.00;
                                                    foreach ($value['MsInfoDetail'] as $key => $value2) {
							
							
							
                                                        $rate_hour = $value2->ms_hour;
                                                        $rate_half = $value2->ms_half;
                                                        $rate_full = $value2->ms_full;
                                                        $rate_month = $value2->ms_month;
                                                        $rate_qtr = $value2->ms_quart;
                                                        $rate_hy = $value2->ms_hy;
                                                        $rate_yr = $value2->ms_year;
							
                                                    }
                                                    ?>
                                                    <?php $__currentLoopData = $value['MeetingroomDetail']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($value1->center_id == $cid): ?>
                                                    <tr class="pointer">
                                                <input type="hidden" name="rr_id[]" value="<?php echo e($value1->rr_id); ?>">
                                                <td><?php echo e($value1->ms_name); ?></td>

                                                <td><?php echo e($value1->ms_type); ?></td>
                                                <?php if($value->flag_hour == 1): ?>
                                                <td>
                                                    <input type="text" class="form-control onlyDec" name="ratehour_<?php echo e($value1->rr_id); ?>" autocomplete="off" value="<?php echo e($value1->ms_hour); ?>">
                                                    <span>Rate/hour : <?php echo $rate_hour; ?></span>
                                                </td>
                                                <?php endif; ?>
                                                <?php if($value->flag_halfday == 1): ?>
                                                <td>
                                                    <input type="text" class="form-control onlyDec" name="ratehalf_<?php echo e($value1->rr_id); ?>" autocomplete="off" value="<?php echo e($value1->ms_half); ?>">
                                                    <span>Half Day : <?php echo $rate_half; ?></span>
                                                </td>
                                                <?php endif; ?>
                                                <?php if($value->flag_fullday == 1): ?>
                                                <td>
                                                    <input type="text" class="form-control onlyDec" name="ratefull_<?php echo e($value1->rr_id); ?>" autocomplete="off" value="<?php echo e($value1->ms_full); ?>">
                                                    <span>Full Day : <?php echo $rate_full; ?></span>
                                                </td>
                                                <?php endif; ?>
                                                <?php if($value->flag_month == 1): ?>
                                                <td>
                                                    <input type="text" class="form-control onlyDec" name="ratemonth_<?php echo e($value1->rr_id); ?>" autocomplete="off" value="<?php echo e($value1->ms_month); ?>">
                                                    <span>Month : <?php echo $rate_month; ?></span>
                                                </td>
                                                <?php endif; ?>
                                                <?php if($value->flag_quart == 1): ?>
                                                <td>
                                                    <input type="text" class="form-control onlyDec" name="ratequart_<?php echo e($value1->rr_id); ?>" autocomplete="off" value="<?php echo e($value1->ms_pln_quart); ?>">
                                                    <span>Quarterly : <?php echo $rate_qtr; ?></span>
                                                </td>
                                                <?php endif; ?>
                                                <?php if($value->flag_halfyear == 1): ?>
                                                <td>
                                                    <input type="text" class="form-control onlyDec" name="ratehy_<?php echo e($value1->rr_id); ?>" autocomplete="off" value="<?php echo e($value1->ms_pln_hy); ?>">
                                                    <span>Half Yearly : <?php echo $rate_hy; ?></span>
                                                </td>
                                                <?php endif; ?>
                                                <?php if($value->flag_year == 1): ?>
                                                <td>
                                                    <input type="text" class="form-control onlyDec" name="rateyr_<?php echo e($value1->rr_id); ?>" autocomplete="off" value="<?php echo e($value1->ms_pln_yr); ?>">
                                                    <span>Yearly: <?php echo $rate_yr; ?></span>
                                                </td>
                                                <?php endif; ?>
                                                <td>
                                                    <input type="text" class="form-control onlyDec" name="countinv_<?php echo e($value1->rr_id); ?>" autocomplete="off" value="<?php echo e($value1->rr_no); ?>">
                                                </td>
						<td>
                           <a href="<?php echo e(route('booking.select',base64_encode($value1->rr_id))); ?>" title="Select" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase" data-id="<?php echo e(base64_encode($value->booking_id)); ?>">Select</a> 
                                                </td>
						
                                                </tr>
                                                <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </div>
                    </div>
                </div>

 </div>
            </form>
       
    </div>
</div>
<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title config-title"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <table  class="table table-striped ">
                    <thead>
                        <tr class="headings">
                            <th class="column-title"></th>
                            <th class="column-title"> Configuration Type</th>
                            <th class="column-title">Seater</th>
                        </tr>
                    </thead>
                    <tbody class="populateConfiguration">



                    </tbody>
                </table>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary text-uppercase add-config-modal" data-dismiss="modal">Add Configuration</button>
            </div>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>