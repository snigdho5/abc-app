<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Customers</h2>
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
                                    <th class="column-title">Customers ID</th>
                                    <th class="column-title">Name</th>
                                    <th class="column-title">Mobile</th>
                                    <th class="column-title">Address</th>
                                    <th class="column-title">Landmark</th>
                                    <th class="column-title">Pin</th>
                                    <th class="column-title">Status</th>
                                    <th class="column-title">Action</th>
                                </tr>
                            </thead>
                            <tbody>


                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="pointer">
                                    <td><?php echo e($value->cust_code); ?></td>
                                    <td><?php echo e($value->cust_nme); ?></td>
                                    <td>+91 <?php echo e($value->cust_mobile); ?></td>
                                    <td class="whiteSpace"><?php echo e($value->cust_service_add1); ?></td>
                                    <td class="whiteSpace"><?php echo e($value->cust_landmark); ?> </td>
                                    <td><?php echo e($value->cust_pin); ?></td>
                                    <td><?php if($value->cust_status ==1): ?> Approved <?php elseif($value->cust_status ==2): ?> Pending <?php else: ?> Disabled <?php endif; ?></td>
                                    <td class="last">

                                        <a href="javascript:void(0);" title="Edit" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase editCustomer" data-id="<?php echo e(base64_encode($value->cust_id)); ?>">View</a> 
                                        <a href="<?php echo e(route('customer.del',base64_encode($value->cust_id))); ?>"  title="Delete" class="d-inline-block delete confirmation"><img src="<?php echo e(URL::asset('images/delete.svg')); ?>" alt=""></a>
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
                <div class="col-12 text-right pl-0 pr-0 pb-3 clearfix"><a href="javascript:void(0);" onclick="scrollToCustomerForm();" class="btn btn-outline-secondary  pl-4 pr-4 pt-2 pb-2  m-0" id="addNewRequest"> Add New Customer </a></div>
            </div>

        </div>
    </div>
</div>


<div class="row pt-3">
    <div class="col-lg-12 col-md-12 col-12">
        <div class="x_panel newRequestForm">
            <div class="x_title">
                <h2 id="titleText">Add New Customer</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="custRegister" action=" <?php echo e(route('customer.register')); ?>"> 

                <?php echo e(csrf_field()); ?>

                <input type="hidden" name="cust_id" id="cust_id">
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
                        <label for="requestName">Name</label>
                        <input type="text" class="form-control" autocomplete="off" id="requestName" name="cust_nme" placeholder="Your Name" value="" >
                    </div>
                    <div class="col-md-4 mb-3 padRig">
                        <label for="requestEmailID">Email ID</label>
                        <input type="text" class="form-control" autocomplete="off" id="requestEmailID" name="cust_email" placeholder="Your Email Id" value="" >
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="requestMobile">Mobile</label>
                        <input type="tel" class="form-control" maxlength="10" onkeyup="if (/\D/g.test(this.value))
                                    this.value = this.value.replace(/\D/g, '')" id="requestMobile" autocomplete="off" name="cust_mobile" placeholder="Your Mobile No." value="" >
                    </div>
                </div>
                <div class="row mt-4">
                     <div class="col-md-4 mb-3">
                        <label for="requestAddress2">Date of Birth</label>
                        <input id="cust_dob" type="text"  value="1990-01-01" name="cust_dob" autocomplete="off">
                    </div>
                    
                    <div class="col-md-4 mb-3 padRig">
                        <label for="requestLocation">Location</label>
                        <select  class="form-control select" id="requestLocation" name="custloc" required="required">
                            <option value="">Select</option>
                            <?php $__currentLoopData = $locationData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value['loc_id']); ?>"><?php echo e($value['loc_name']); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    
                     <div class="col-md-4 mb-3 padRig">
                        <label for="requestAddress">Company Name</label>
                        <input type="text" class="form-control" id="cust_comp" autocomplete="off" name="cust_comp" placeholder="Company name" value="" >
                    </div>
                    

                </div>
                <div class="row mt-4">

                   <div class="col-md-3 mb-3 padRig">
                        <label for="requestAddress"> Address 1</label>
                        <input type="text" class="form-control" id="requestAddress" autocomplete="off" name="cust_service_add1" placeholder="Address 1" value="" >
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="requestAddress2"> Address 2</label>
                        <input type="text" class="form-control" id="requestAddress2" autocomplete="off" name="cust_service_add2" placeholder="Address 2" value="" >
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="requestAddress2">Landmark</label>
                        <input type="text" class="form-control" id="cust_landmark" autocomplete="off" name="cust_landmark" placeholder="Landmark" value="" >
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="requestAddress2">PIN</label>
                        <input type="text" class="form-control" id="cust_pin" maxlength="6" autocomplete="off" name="cust_pin" placeholder="PIN" value="" >
                    </div>

                </div>


                <div class="row mt-4">
                     <div class="col-md-3 mb-3">
                        <label for="requestAddress2">Designation</label>
                        <input type="text" class="form-control" id="cust_desig" autocomplete="off" name="cust_desig" placeholder="Designation" value="" >
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="status">Status</label>
                        <select  class="form-control select " id="cstatus" name="cust_status">
                            <option value="1">Approved</option>
                            <option value="2">Pending</option>
                            <option value="3">Disable</option>
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