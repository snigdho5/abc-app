<?php $__env->startSection('content'); ?>


<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Centre Configuration</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
                    <table id="datatable" class="table table-striped ">
                        <thead>
                            <tr class="headings">
                                <th class="column-title"> ID</th>
                                <th class="column-title">Centre</th>                  
                                <th class="column-title">Centre Address</th>                  
                                <th class="column-title">Location</th>                             
                                <th class="column-title">Status</th>
                                <th class="column-title">Action</th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $locnm = getLocationName($value->location); ?>
                            <tr class="pointer">
                                <td><?php echo e($value->centre_id); ?></td>
                                <td class="whiteSpace"><?php echo e($value->centre); ?></td>
                                <td class="whiteSpace"><?php echo e($value->centre_address); ?></td>
                                <td class="whiteSpace"><?php echo $locnm->loc_name; ?></td>
                                <td><?php if($value->status ==1): ?> Enable <?php else: ?> Disable <?php endif; ?></td>
                                <td class="last">

                                    <a href="<?php echo e(route('meetingroom.data',base64_encode($value->centre_id))); ?>" title="Edit" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase ">View</a> 
									 <?php if(Auth::guard('admin')->user()->admin_role == 1): ?> 
                                    <a href="<?php echo e(route('meetingroom.delete',base64_encode($value->centre_id))); ?>"  title="Delete" class="d-inline-block delete confirmation"><img src="<?php echo e(URL::asset('images/delete.svg')); ?>" alt=""></a>
									<?php endif; ?>
								</td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>
                    </table>
                </div>

            </div>
            <div class="clearfix"></div>
            <div class="row  m-0">
                <div class="col-12 text-right pl-0 pr-0 pb-3 clearfix"><a href="<?php echo e(route('centre.manage')); ?>" class="btn btn-outline-secondary  pl-4 pr-4 pt-2 pb-2  m-0" id="addNewRequest"> Manage Center </a></div>
            </div>
        </div>
    </div>
</div>

<div class="row pt-3">

    <div class="col-lg-12 col-md-12 col-12">
        <div class="x_panel newRequestForm">
            <div class="x_title">
                <h2 id="titleText">Edit Centre Configuration</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="centreRegister" action="<?php echo e(route('meetingroom.edit')); ?>" enctype="multipart/form-data" > 
                <?php echo e(csrf_field()); ?>

                <input type="hidden" name="id" id="centre_id" value="<?php echo e($cid); ?>">
                <?php if(count($errors) > 0): ?>
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.								
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <p><?php echo e($error); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>								
                </div>
                <?php endif; ?>
                <?php if(session()->has('message')): ?>
                <div class="alert alert-success">
                    <?php echo e(session()->get('message')); ?>

                </div>
                <?php endif; ?>

                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <label for="status">Location</label>
                        <?php if($errors->has('location')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('location')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <select  class="form-control select config-val"   id="location" name="location" disabled="">
                            <option value="" >Select Location</option>
                            <?php $__currentLoopData = $locationData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option <?php if($cinfoData->location ==$value->loc_id ): ?> selected <?php endif; ?> value="<?php echo e($value->loc_id); ?>" ><?php echo e($value->loc_name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="pageName">Centre</label>
                        <?php if($errors->has('centre')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('centre')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control config-val" autocomplete="off" id="centre" name="centre" placeholder="Centre name" value="<?php echo e($cinfoData->centre); ?>" readonly >
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="pageName">Address</label>
                        <?php if($errors->has('centre_address')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('centre_address')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control config-val" autocomplete="off" id="centre_address" name="centre_address" placeholder="Centre address" value="<?php echo e($cinfoData->centre_address); ?>" readonly >
                    </div>

                </div>

                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <label for="pageName">Email</label>
                        <?php if($errors->has('centre_email')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('centre_email')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control config-val" autocomplete="off" id="centre_email" name="centre_email" placeholder="Centre email" value="<?php echo e($cinfoData->centre_email); ?>" readonly >
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="pageName">Mobile</label>
                        <?php if($errors->has('centre_mobile')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('centre_mobile')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control onlyNum config-val" autocomplete="off" id="centre_mobile" name="centre_mobile" placeholder="Centre mobile" maxlength="10" value="<?php echo e($cinfoData->centre_mobile); ?>" readonly >
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="pageName">Phone</label>
                        <?php if($errors->has('centre_phone')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('centre_phone')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control config-val" autocomplete="off" id="centre_phone" name="centre_phone" placeholder="Centre phone" value="<?php echo e($cinfoData->centre_phone); ?>" readonly >
                    </div>

                </div>

                <div class="row mt-4">

                    <div class="col-md-4 mb-3">
                        <label for="status">Status</label>
                        <?php if($errors->has('status')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('status')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <select  class="form-control select"   id="status" name="status">

                            <option <?php if($cinfoData->status ==1): ?> selected <?php endif; ?> value="1" >Enable</option>
                            <option <?php if($cinfoData->status ==0): ?> selected <?php endif; ?> value="0" >Disable</option>


                        </select>
                    </div>

                </div>





                <div class="row mt-4 configdiv" >
                    <div class="col-md-12 mb-3">
                        <div class="accordion" id="accordionExample">
                            <?php $i = 0; ?>
                            <?php $__currentLoopData = $minfoData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
//                            debug($value['MsInfoDetail']);
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
                                    <div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne_<?php echo e($value->acat_id); ?>" aria-expanded="true" aria-controls="collapseOne_<?php echo e($value->acat_id); ?>">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne_<?php echo e($value->acat_id); ?>" aria-expanded="true" aria-controls="collapseOne_<?php echo e($value->acat_id); ?>">
                                                <?php echo e($value->acat_name); ?>

                                            </button>
                                        </h2>
                                    </div>

                                    <div id="collapseOne_<?php echo e($value->acat_id); ?>" class="collapse <?php echo $cls; ?>" aria-labelledby="headingOne" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div id="loader" style="display:none;"></div>
                                            <button class="form-control addConfig" data-id="<?php echo e($value->acat_id); ?>" data-name="<?php echo e($value->acat_name); ?>" data-centerid="<?php echo e($cid); ?>"> Add new Configuration</button>
                                            <table  class="table table-striped table-responsive">
                                                <thead>
                                                    <tr class="headings">
                                                        <th class="column-title"> Configuration Type</th>
                                                        <th class="column-title">Seater</th>
														<?php if($value->acat_id == 5): ?>
															<th class="column-title">Package</th>
                                                        <?php endif; ?>
                                                        <?php if($value->flag_hour == 1): ?>
                                                        <th class="column-title">Rate/hour</th>
                                                        <?php endif; ?>
                                                        <?php if($value->flag_halfday == 1): ?>
                                                        <th class="column-title">Half Day</th>
                                                        <?php endif; ?>
                                                        <?php if($value->flag_fullday == 1): ?>
                                                        <th class="column-title">Full&nbsp;Day</th>
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
                                                        <th class="column-title">Configuration Status </th>  
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
//                                                    $rate_hour = 0.00;
//                                                    $rate_half = 0.00;
//                                                    $rate_full = 0.00;
//                                                    $rate_month = 0.00;
//                                                    $rate_qtr = 0.00;
//                                                    $rate_hy = 0.00;
//                                                    $rate_yr = 0.00;
////                                                    debug($value['MsInfoDetail']);
//                                                    foreach ($value['MsInfoDetail'] as $key => $value2) {
//                                                        $rate_hour = $value2->ms_hour;
//                                                        $rate_half = $value2->ms_half;
//                                                        $rate_full = $value2->ms_full;
//                                                        $rate_month = $value2->ms_month;
//                                                        $rate_qtr = $value2->ms_quart;
//                                                        $rate_hy = $value2->ms_hy;
//                                                        $rate_yr = $value2->ms_year;
//                                                    }
?>
                                                    <?php $__currentLoopData = $value['MeetingroomDetail']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
 <?php $infoData = getMsInfoDefaultPrice($value1->ms_id); ?>
                                                    <?php if($value1->center_id == $cid): ?>
                                                    
                                                   
                                                    <tr class="pointer">
                                                <input type="hidden" name="rr_id[]" value="<?php echo e($value1->rr_id); ?>">
                                                <td><?php echo e($value->acat_name); ?></td>

                                                <td><?php echo e($value1->ms_type); ?></td>
												
                                                <?php if($value1->ms_cat == 5): ?>
                                                <td>
                                                   <?php echo e($value1->ms_name); ?>

                                                </td>
                                                <?php endif; ?> 
												<?php if($value->flag_hour == 1): ?>
                                                <td>
                                                    <input type="text" class="form-control onlyDec" name="ratehour_<?php echo e($value1->rr_id); ?>" autocomplete="off" value="<?php echo e($value1->ms_hour); ?>">
                                                    <span>Rate/hour : <?php if(isset($infoData->ms_hour))echo $infoData->ms_hour ; ?></span>
                                                </td>
                                                <?php endif; ?>
                                                <?php if($value->flag_halfday == 1): ?>
                                                <td>
                                                    <input type="text" class="form-control onlyDec" name="ratehalf_<?php echo e($value1->rr_id); ?>" autocomplete="off" value="<?php echo e($value1->ms_half); ?>">
                                                    <span>Half Day : <?php if(isset($infoData->ms_half))echo $infoData->ms_half ; ?></span>
                                                </td>
                                                <?php endif; ?>
                                                <?php if($value->flag_fullday == 1): ?>
                                                <td>
                                                    <input type="text" class="form-control onlyDec" name="ratefull_<?php echo e($value1->rr_id); ?>" autocomplete="off" value="<?php echo e($value1->ms_full); ?>">
                                                    <span>Full Day : <?php if(isset($infoData->ms_full))echo $infoData->ms_full ; ?></span>
                                                </td>
                                                <?php endif; ?>
                                                <?php if($value->flag_month == 1): ?>
                                                <td>
                                                    <input type="text" class="form-control onlyDec" name="ratemonth_<?php echo e($value1->rr_id); ?>" autocomplete="off" value="<?php echo e($value1->ms_month); ?>">
                                                    <span>Month : <?php if(isset($infoData->ms_month))echo $infoData->ms_month; ?></span>
                                                </td>
                                                <?php endif; ?>
                                                <?php if($value->flag_quart == 1): ?>
                                                <td>
                                                    <input type="text" class="form-control onlyDec" name="ratequart_<?php echo e($value1->rr_id); ?>" autocomplete="off" value="<?php echo e($value1->ms_pln_quart); ?>">
                                                    <span>Quarterly : <?php if(isset($infoData->ms_quart))echo $infoData->ms_quart; ?></span>
                                                </td>
                                                <?php endif; ?>
                                                <?php if($value->flag_halfyear == 1): ?>
                                                <td>
                                                    <input type="text" class="form-control onlyDec" name="ratehy_<?php echo e($value1->rr_id); ?>" autocomplete="off" value="<?php echo e($value1->ms_pln_hy); ?>">
                                                    <span>Half Yearly :  <?php if(isset($infoData->ms_hy))echo $infoData->ms_hy; ?></span>
                                                </td>
                                                <?php endif; ?>
                                                <?php if($value->flag_year == 1): ?>
                                                <td>
                                                    <input type="text" class="form-control onlyDec" name="rateyr_<?php echo e($value1->rr_id); ?>" autocomplete="off" value="<?php echo e($value1->ms_pln_yr); ?>">
                                                    <span>Yearly : <?php if(isset($infoData->ms_year))echo $infoData->ms_year; ?></span>
                                                </td>
                                                <?php endif; ?>
												
                                                <td>
                                                    <input type="text" class="form-control onlyDec" name="countinv_<?php echo e($value1->rr_id); ?>" autocomplete="off" value="<?php echo e($value1->rr_no); ?>">
                                                </td>
                                                <td>

                                                    <select  class="form-control select"  id="conf_status_<?php echo e($value1->rr_id); ?>" name="conf_status_<?php echo e($value1->rr_id); ?>">
                                                        <option value="1" <?php if ($value1->ms_status == 1) echo 'selected="selected"'; ?> >Enable</option>
                                                        <option value="0" <?php if ($value1->ms_status == 0) echo 'selected="selected"'; ?>>Disable</option>
                                                    </select>
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

                <div class="row mt-4">
                    <div class="col-12  pb-3 clearfix"><button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2  m-0 text-uppercase" id="submitCentre">Edit Centre</button></div>

                </div>

            </form>
        </div>
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