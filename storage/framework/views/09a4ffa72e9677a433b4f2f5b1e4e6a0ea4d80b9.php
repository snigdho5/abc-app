<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Client</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
                    <form method="get">


                        <div class="row mt-4">
                            <div class="col-md-12 mb-3 padRig">
                                <input type="text" placeholder="Search by Name ,Email and Mobile" name="search"  autocomplete="off" class="form-control" value="<?php echo e(app('request')->input('search')); ?>">
                            </div>
                            <!--                            <div class="col-md-6 mb-3 padRig">
                                                            <button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2  m-0 text-uppercase">Search</button>
                                                        </div>-->
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6 mb-3 padRig">
                                <?php
                                $dates = '';

                                if (!empty(app('request')->input('req_date_range'))) {
                                    $dates = explode('and', app('request')->input('req_date_range'));
                                }
                                //dd($dates);
                                if (!empty($dates)) {
                                    $first_day = date('m-01-Y', strtotime($dates[0])); // hard-coded '01' for first day
                                    $last_day = date('m-t-Y', strtotime($dates[1]));
                                } else {
                                    $first_day = date('m-01-Y'); // hard-coded '01' for first day
                                    $last_day = date('m-t-Y');
                                }
                                ?>
                                <div id="reportrange1" class="form-control"> <i class="glyphicon glyphicon-calendar fa fa-calendar"></i> <span><?php echo e(date("M j, Y",strtotime($first_day))); ?> - <?php echo e(date("M j, Y",strtotime($last_day))); ?></span> <b class="caret"></b> </div>
                                <input type="hidden" name="req_date_range" id="req_date_range" value="<?php echo e(app('request')->input('req_date_range')); ?>">
                            </div>

                            <?php echo e(csrf_field()); ?>

                            <div class="col-md-4 mb-3 padRig">
                                <button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2  m-0 text-uppercase">Search</button>
                            </div>
                        </div>

                    </form>
                    <?php if (count($data) > 0) { ?>
                        <table id="datatable1" class="table table-striped ">
                            <thead>
                                <tr class="headings">
                                    <th class="column-title">Company Name</th>
                                    <th class="column-title">Contact Person</th>
                                    <th class="column-title">Email</th>
                                    <th class="column-title">Mobile</th>
                                    <th class="column-title">Status</th>
                                    <th class="column-title">Action</th>
                                </tr>
                            </thead>
                            <tbody>


                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="pointer">
                                    <td><?php echo e(getCompanyName($value->cust_comp)); ?></td>
                                    <td><?php echo e($value->cust_nme); ?></td>
                                    <td><?php echo e($value->cust_email); ?></td>
                                    <td>+91 <?php echo e($value->cust_mobile); ?></td>
                                    <td><?php if($value->comp_status ==1): ?> Pending <?php elseif($value->comp_status ==2): ?> Approved <?php else: ?> Disabled <?php endif; ?></td>
                                    <td class="last">

                                        <a href="javascript:void(0);" title="Edit" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase editCompany" data-id="<?php echo e(base64_encode($value->cust_id)); ?>">View</a> 
                                        <a href="<?php echo e(route('company.delete',base64_encode($value->cust_id))); ?>"  title="Delete" class="d-inline-block delete confirmation"><img src="<?php echo e(URL::asset('images/delete.svg')); ?>" alt=""></a>
                                    </td>

                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tbody>
                        </table>
                    <?php } else { ?>
                        <strong style="margin-left: 35%;color: red;">No data available </strong>
                    <?php } ?>
                    <?php echo e($data->links('common.custompagination')); ?>

                </div>

            </div>

            <div class="clearfix"></div>
            <div class="row  m-0">
                <div class="col-12 text-right pl-0 pr-0 pb-3 clearfix"><a href="javascript:void(0);" onclick="scrollToCustomerForm();" class="btn btn-outline-secondary  pl-4 pr-4 pt-2 pb-2  m-0" id="addNewRequest"> Add New Client </a></div>
            </div>

        </div>
    </div>
</div>


<div class="row pt-3">
    <div class="col-lg-12 col-md-12 col-12">
        <div class="x_panel newRequestForm">
            <div class="x_title">
                <h2 id="titleText">Add New Client</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="compRegister" action=" <?php echo e(route('company.register')); ?>"> 

                <?php echo e(csrf_field()); ?>

                <input type="hidden" name="id" id="cust_id">
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
                    <div class="col-md-3 mb-3 padRig">
                        <label for="requestName">Select Company</label>
                        <select  class="form-control select" id="cust_comp" name="cust_comp" required="required">
                            <option value="">Select</option>
                            <?php $__currentLoopData = $compData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value['cc_id']); ?>"><?php echo e($value['cc_name']); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3 padRig">
                        <label for="requestEmailID">Contact Person</label>
                        <input type="text" class="form-control" autocomplete="off" id="cust_nme" name="cust_nme" placeholder="Contact person" value="" >
                    </div>
                    <div class="col-md-3 mb-3 padRig">
                        <label for="requestEmailID">Email ID</label>
                        <input type="text" class="form-control" autocomplete="off" id="cust_email" name="cust_email" placeholder="Company Email Id" value="" >
                    </div>
                    
                    <div class="col-md-3 mb-3 padRig">
                        <label for="comp_desig"> Designation </label>
                        <input type="text" class="form-control" id="cust_desig" autocomplete="off" name="cust_desig" placeholder="Designation " value="" >
                    </div>
                    
                </div>
                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <label for="requestMobile">Mobile</label>
                        <input type="tel" class="form-control" maxlength="10" onkeyup="if (/\D/g.test(this.value))
                                    this.value = this.value.replace(/\D/g, '')" id="cust_mobile" autocomplete="off" name="cust_mobile" placeholder="Mobile No." value="" >
                    </div>

                    <div class="col-md-4 mb-3 padRig">
                        <label for="requestLocation">Location</label>
                        <select  class="form-control select" id="custloc" name="custloc" required="required">
                            <option value="">Select</option>
                            <?php $__currentLoopData = $locationData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value['loc_id']); ?>"><?php echo e($value['loc_name']); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-3 padRig">
                        <label for="requestAddress"> Address </label>
                        <input type="text" class="form-control" id="cust_service_add1" autocomplete="off" name="cust_service_add1" placeholder="Address " value="" >
                    </div>

                </div>

                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <label for="status">Centre</label>
                        <?php if($errors->has('center_id')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('center_id')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <select  class="form-control select config-val"   id="cust_centre" name="cust_centre">
                            <option value="" >Select Centre</option>
							<option value="0" >All Centres</option>
                            <?php $__currentLoopData = $centreData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php $locnm = getLocationName($value->location); ?>
                            <option value="<?php echo e($value->centre_id); ?>" ><?php echo e($value->centre); ?> (<?php echo $locnm->loc_name; ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3 padRig">
                        <label for="requestAddress"> GST </label>
                        <input type="text" class="form-control" id="comp_gst" autocomplete="off" name="comp_gst" placeholder="GST Number " value="" >
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="status">Status</label>
                        <select  class="form-control select " id="comp_status" name="comp_status">
                            <option value="2">Approved</option>
                            <option value="1">Pending</option>
                            <option value="3">Disable</option>
                        </select>
                    </div>
                </div>
				
				<div class="row mt-4">
                    
                    <div class="col-md-4 mb-3">
                        <label for="status">Bill To Company</label>
                        <select  class="form-control select " id="blc" name="blc">
                            <option value="0">Disable</option>
							<option value="1">Enable</option>
                        </select>
                    </div>
                </div>
				
				
                <div class="row mt-4">
                    <div class="col-6  pb-3 clearfix">
                        <button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2   text-uppercase " id="submitCust">Submit</button> 
                        <button type="button" class="btn btn-outline-secondary pl-4 pr-4 pt-2 pb-2 ml-2 text-uppercase btn-reset" id="submitCategory">Reset</button></div>


                </div>

            </form>
        </div>
    </div>

    <?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>