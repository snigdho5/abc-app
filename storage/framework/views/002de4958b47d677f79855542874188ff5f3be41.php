<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Service Categories</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
                    <table id="datatable" class="table table-striped ">
                        <thead>
                            <tr class="headings">
                                <th class="column-title">Service Category</th>
                                <th class="column-title">Category Type </th>
                                <th class="column-title">Status</th>
                                <th class="column-title">Action</th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="pointer">
                                <td><?php echo e($value->acat_name); ?></td>
                                <td><?php echo e($value->acat_type); ?></td>
                                <td><?php if($value->acat_status ==1): ?> Enable <?php else: ?> Disable <?php endif; ?></td>
                                <td class="last">
                                    <a href="javascript:void(0);" title="Edit" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase editCategory" data-id="<?php echo e(base64_encode($value->acat_id)); ?>">View</a> 
                                   <!-- <a href="<?php echo e(route('category.delete',base64_encode($value->acat_id))); ?>"  title="Delete" class="d-inline-block delete confirmation"><img src="<?php echo e(URL::asset('images/delete.svg')); ?>" alt=""></a>-->
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>
                    </table>
                </div>

            </div>


        </div>
    </div>
</div>

<div class="row pt-3 serviceCat" style="display:none">
    <div class="col-lg-12 col-md-12 col-12">
        <div class="x_panel newRequestForm">
            <div class="x_title">
                <h2 id="titleText"> Add Service Category</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="categoryRegister" action="<?php echo e(route('category.register')); ?> " enctype="multipart/form-data"> 

                <?php echo e(csrf_field()); ?>

                <input type="hidden" name="acat_id" id="acat_id">
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
                <!--<div class="row mt-4">
                    <div class="col-md-4 mb-3 padRig">
                        <label for="locationName">Service Category</label>
                        <?php if($errors->has('acat_name')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('acat_name')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control " autocomplete="off" id="acat_name" name="acat_name" placeholder=" Category name"   >
                    </div>
                    <div class="col-md-4 mb-3 padRig">
                        <label for="locationName">Intro</label>
                        <?php if($errors->has('acat_intro')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('acat_intro')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <textarea class="form-control " autocomplete="off" id="acat_intro" name="acat_intro" placeholder=" Intro" rows="4" ></textarea>

                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="cat_type">Category Type</label>
                        <?php if($errors->has('acat_type')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('acat_type')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <select  class="form-control select " id="acat_type" name="acat_type" required="">
                            <option value="">Select Type</option>
                            <option value="Service"  >Service</option>
                            <option value="Add. Service" >Add. Service</option>
                            <option value="Package" >Package</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-4">
                    <input type="hidden" name="spam" id="spam" required/>
                    <div class="col mb-3 align-self-center mt-4">
                        <div class="custom-control custom-checkbox">

                            <input type="checkbox" name="flag_hour" class="custom-control-input all_flag" id="flag_hour" >
                            <label class="custom-control-label" for="flag_hour">Hourly</label>
                        </div>
                    </div>
                    <div class="col mb-3 align-self-center mt-4">
                        <div class="custom-control custom-checkbox">

                            <input type="checkbox" name="flag_halfday" class="custom-control-input all_flag" id="flag_halfday" >
                            <label class="custom-control-label" for="flag_halfday">Half Day</label>
                        </div>
                    </div>
                    <div class="col mb-3 align-self-center mt-4">
                        <div class="custom-control custom-checkbox">

                            <input type="checkbox" name="flag_fullday" class="custom-control-input all_flag" id="flag_fullday" >
                            <label class="custom-control-label" for="flag_fullday">Full Day</label>
                        </div>
                    </div>
                    <div class="col mb-3 align-self-center mt-4">
                        <div class="custom-control custom-checkbox">

                            <input type="checkbox" name="flag_month" class="custom-control-input all_flag" id="flag_month" >
                            <label class="custom-control-label" for="flag_month">Monthly</label>
                        </div>
                    </div>
                    <div class="col mb-3 align-self-center mt-4">
                        <div class="custom-control custom-checkbox">

                            <input type="checkbox" name="flag_quart" class="custom-control-input all_flag" id="flag_quart" >
                            <label class="custom-control-label" for="flag_quart">Quarterly</label>
                        </div>
                    </div>
                    <div class="col mb-3 align-self-center mt-4">
                        <div class="custom-control custom-checkbox">

                            <input type="checkbox" name="flag_halfyear" class="custom-control-input all_flag" id="flag_halfyear" >
                            <label class="custom-control-label" for="flag_halfyear">Half Yearly</label>
                        </div>
                    </div>
                    <div class="col mb-3 align-self-center mt-4">
                        <div class="custom-control custom-checkbox">

                            <input type="checkbox" name="flag_year" class="custom-control-input all_flag" id="flag_year" >
                            <label class="custom-control-label" for="flag_year">Yearly</label>
                        </div>
                    </div>
                </div>-->
				
				<div class="row mt-4 ml-2">Key benefits</div>
				<div class="row">
                    <input type="hidden" name="spam" id="spam" required/>
                    <div class="col mb-3 align-self-center mt-4">
                        <div class="custom-control custom-checkbox">

                            <input type="checkbox" name="business_address" class="custom-control-input all_flag" id="business_address" >
                            <label class="custom-control-label" for="business_address">Strategic Business Address</label>
                        </div>
                    </div>
                    <div class="col mb-3 align-self-center mt-4">
                        <div class="custom-control custom-checkbox">

                            <input type="checkbox" name="high_internet" class="custom-control-input all_flag" id="high_internet" >
                            <label class="custom-control-label" for="high_internet">Reliable High Speed internet</label>
                        </div>
                    </div>
                    <div class="col mb-3 align-self-center mt-4">
                        <div class="custom-control custom-checkbox">

                            <input type="checkbox" name="it_infra" class="custom-control-input all_flag" id="it_infra" >
                            <label class="custom-control-label" for="it_infra">Robust IT Infrastructure</label>
                        </div>
                    </div>
                    <div class="col mb-3 align-self-center mt-4">
                        <div class="custom-control custom-checkbox">

                            <input type="checkbox" name="parking_zone" class="custom-control-input all_flag" id="parking_zone" >
                            <label class="custom-control-label" for="parking_zone">Parking Zones</label>
                        </div>
                    </div>
                    <div class="col mb-3 align-self-center mt-4">
                        <div class="custom-control custom-checkbox">

                            <input type="checkbox" name="twentyfour_access" class="custom-control-input all_flag" id="twentyfour_access" >
                            <label class="custom-control-label" for="twentyfour_access">24x7 Access</label>
                        </div>
                    </div>
                    <div class="col mb-3 align-self-center mt-4">
                        <div class="custom-control custom-checkbox">

                            <input type="checkbox" name="event_activity" class="custom-control-input all_flag" id="event_activity" >
                            <label class="custom-control-label" for="event_activity">Events & Activities</label>
                        </div>
                    </div>
                </div>
				<div class="row mt-4">

						<div class="col-md-12 mb-3 padRig">
							<label for="pageName">Details</label>
							<?php if($errors->has('acat_detail')): ?>
							<span class="help-block">
								<strong><?php echo e($errors->first('acat_detail')); ?></strong>
							</span>
							<?php endif; ?>
							<textarea class="form-control" id="acat_detail" name="acat_detail" placeholder="Detail"></textarea>
							
						</div>
				</div>
                <!--<div class="row mt-4">
                   
                    
                    <div class="col-md-4 mb-3">
                        <label for="pageName" class="d-block">Image</label>
                        <?php if($errors->has('acat_img')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('acat_img')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <div  class="position-relative">
                            <span class="input-group-btn d-flex ">
                                <input id="customFile" type="file" class=" d-none" name="acat_img" onchange="$(this).parent().parent().find('.form-control').html($(this).val().split(/[\\|/]/).pop());" style="display: none;">
                            </span>
                            <label for="customFile" class=" form-control formFile custom-file-label rounded-pill" onclick="$(this).parent().find('input[type=file]').click();">Choose file</label>

                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3 padRig">
                        <label for="status">Types</label>
                        <?php if($errors->has('acat_per_type')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('acat_per_type')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <select  class="form-control select " id="acat_per_type" name="acat_per_type[]" multiple>
                            <option value="1"  >Per Hour</option>
                            <option value="2"  >Per Seats</option>
                            <option value="3"  >Per Workstation</option>
                            <option value="4"  >Per Cabin</option>
                        </select>
                    </div>
                    
                    
                    <div class="col-md-3 mb-3 padRig">
                        <label for="status">Add on</label>
                        <?php if($errors->has('acat_addons')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('acat_addons')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <select  class="form-control select" id="acat_addons" name="acat_addons">
                            <option value="1"  >Enable</option>
                            <option value="0" >Disable</option>
                        </select>
                    </div>
                    
                    
                    <div class="col-md-3 mb-3 padRig">
                        <label for="status">Status</label>
                        <?php if($errors->has('acat_status')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('acat_status')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <select  class="form-control select " id="acat_status" name="acat_status">
                            <option value="1"  >Enable</option>
                            <option value="0" >Disable</option>
                        </select>
                    </div>
                </div>
                <div id="imagePage"></div>-->
                <div class="row mt-4">
                    <div class="col-6  pb-3 clearfix">
                        <button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2   text-uppercase " id="submitCategory">Submit</button> 
                        <button type="button" class="btn btn-outline-secondary pl-4 pr-4 pt-2 pb-2 ml-2 text-uppercase  reset-btn" id="submitCategory">Reset</button></div>


                </div>



            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>