<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Client Offer</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
                    
                        <table id="datatable" class="table table-striped ">
                            <thead>
                                <tr class="headings">
                                    <th class="column-title">Company Name</th>
                                    <th class="column-title">Service offered</th>
                                    <!--<th class="column-title">Configuration</th>-->
                                    <th class="column-title">Allocated Hrs</th>
                                    <th class="column-title">Start Date</th>
                                    <th class="column-title">End Date</th>
                                    <th class="column-title">Status</th>
                                    <th class="column-title">Action</th>
                                </tr>
                            </thead>
                            <tbody>


                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="pointer">
                                    <td><?php echo e(getCompNameById($value->co_compid)); ?></td>
                                    <td><?php echo e(getCatNameById($value->co_catid)); ?></td>
                                    <!--<td><?php echo e(getConfigNameById($value->co_configid)); ?></td>-->
                                    <td><?php echo e($value->co_allctedhrs); ?></td>
                                    <td><?php echo e($value->co_cntrctstrtdte); ?></td>
                                    <td><?php echo e($value->co_cntrctenddte); ?></td>
                                    <td><?php if($value->co_status ==1): ?> Enabled  <?php else: ?> Disabled <?php endif; ?></td>
                                    <td class="last">

                                        <a href="javascript:void(0);" title="Edit" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase editCompanyOffer" data-id="<?php echo e(base64_encode($value->co_id)); ?>">View</a> 
                                        <a href="<?php echo e(route('companyoffer.delete',base64_encode($value->co_id))); ?>"  title="Delete" class="d-inline-block delete confirmation"><img src="<?php echo e(URL::asset('images/delete.svg')); ?>" alt=""></a>
                                    </td>

                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tbody>
                        </table>

                </div>

            </div>

            <div class="clearfix"></div>
            <div class="row  m-0">
                <div class="col-12 text-right pl-0 pr-0 pb-3 clearfix"><a href="javascript:void(0);" onclick="scrollToCustomerForm();" class="btn btn-outline-secondary  pl-4 pr-4 pt-2 pb-2  m-0" id="addNewRequest"> Add New Client Offer </a></div>
            </div>

        </div>
    </div>
</div>


<div class="row pt-3">
    <div class="col-lg-12 col-md-12 col-12">
        <div class="x_panel newRequestForm">
            <div class="x_title">
                <h2 id="titleText">Add New Client Offer</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="compofferRegister" action=" <?php echo e(route('companyoffer.register')); ?>"> 

                <?php echo e(csrf_field()); ?>

                <input type="hidden" name="id" id="co_id">
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
                    <div class="col-md-4 mb-3 padRig">
                        <label for="requestLocation">Company</label>
                        <select  class="form-control select" id="co_compid" name="co_compid" required="required">
                            <option value="">Select</option>
                            <?php $__currentLoopData = $custdata; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value['cust_id']); ?>"><?php echo e($value['cust_comp']); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3 padRig">
                        <label for="requestLocation">Service Offered</label>
                        <!--<select  class="form-control select" id="co_catid" name="co_catid" required="required" onchange="populateServiceConfig(0,0)">-->
                            <select  class="form-control select" id="co_catid" name="co_catid" required="required">
							<option value="">Select</option>
                            <?php $__currentLoopData = $catData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value['acat_id']); ?>"><?php echo e($value['acat_name']); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
					 <div class="col-md-4 mb-3">
                        <label for="requestMobile">Allocated Hours</label>
                        <input type="text" class="form-control" maxlength="10" onkeyup="if (/\D/g.test(this.value))
                                    this.value = this.value.replace(/\D/g, '')" id="co_allctedhrs" autocomplete="off" name="co_allctedhrs" placeholder="" value="" >
                    </div>
                    <!--<div class="col-md-4 mb-3 padRig">
                        <label for="requestLocation">Configuration</label>
                        <select  class="form-control select" id="co_configid" name="co_configid" required="required">
                            <option value="">Select Service Offered First</option>
                        </select>
                    </div>-->
                    
                </div>
                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <label for="requestMobile">Monthly Hours</label>
                        <input type="text" class="form-control" maxlength="10" onkeyup="if (/\D/g.test(this.value))
                                    this.value = this.value.replace(/\D/g, '')" id="co_allctedmnthhrs" autocomplete="off" name="co_allctedmnthhrs" placeholder="" value="" >
                    </div>

                    
                    <div class="col-md-8 mb-3 padRig">
                         <label for="requestMobile">Contract Date Range</label>
                                <?php
                                $dates = '';

                                if (!empty(app('request')->input('req_date_range'))) {
                                    $dates = explode('and', app('request')->input('req_date_range'));
                                }

                                if (!empty($dates)) {
                                    $first_day = date('m-01-Y', strtotime($dates[0])); // hard-coded '01' for first day
                                    $last_day = date('m-t-Y', strtotime($dates[1]));
                                } else {
                                    $first_day = date('m-01-Y'); // hard-coded '01' for first day
                                    $last_day = date('m-t-Y');
                                }
                                ?>
                                <div id="reportrange2" class="form-control"> <i class="glyphicon glyphicon-calendar fa fa-calendar"></i> <span><?php echo e(date("M j, Y",strtotime($first_day))); ?> - <?php echo e(date("M j, Y",strtotime($last_day))); ?></span> <b class="caret"></b> </div>
                                <input type="hidden" name="con_date_range" id="ofr_date_range" value="<?php echo e(app('request')->input('req_date_range')); ?>">
                            </div>

                </div>
				
				
				<div class="row mt-4">
                     <div class="col-md-4 mb-3 padRig">
                        <label for="requestLocation">Days</label>
                        <select  class="form-control" id="co_offerdays" name="co_offerdays[]" required="required" multiple>
                            <option value="0"> Sunday</option>
							<option value="1"> Monday</option>
							<option value="2"> Tuesday</option>
							<option value="3"> Wednesday</option>
							<option value="4"> Thrusday</option>
							<option value="5"> Friday</option>
							<option value="6"> Saturday</option>
                        </select>
                    </div>
					<?php $hrs_range = get_hours_range();?>
					 <div class="col-md-4 mb-3 padRig">
                        <label for="requestLocation">Time From</label>
                        <select  class="form-control select" id="co_ofrtimefrom" name="co_ofrtimefrom" required="required">
                           <?php $__currentLoopData = $hrs_range; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key =>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($key); ?>"> <?php echo e($value); ?></option>
						   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
					
					
					<div class="col-md-4 mb-3 padRig">
                        <label for="requestLocation">Time To </label>
                        <select  class="form-control select" id="co_ofrtimeto" name="co_ofrtimeto" required="required" >
                             <?php $__currentLoopData = $hrs_range; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key =>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($key); ?>"> <?php echo e($value); ?></option>
						   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
					
					
                </div>

                <div class="row mt-4">
                   
                    <div class="col-md-4 mb-3">
                        <label for="status">Status</label>
                        <select  class="form-control select " id="co_status" name="co_status">
                            <option value="1">Enable</option>
                            <option value="2">Disable</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-6  pb-3 clearfix">
                        <button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2   text-uppercase " id="submitCust">Submit</button> 
                        <button type="button" class="btn btn-outline-secondary pl-4 pr-4 pt-2 pb-2 ml-2 text-uppercase reset-btn" id="submitCategory">Reset</button></div>


                </div>

            </form>
        </div>
    </div>

    <?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>