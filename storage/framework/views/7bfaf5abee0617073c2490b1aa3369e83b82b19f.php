<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Company</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
                    <form method="get">


                        <div class="row mt-4">
                            <div class="col-md-12 mb-3 padRig">
                                <input type="text" placeholder="Search by Name " name="search"  autocomplete="off" class="form-control" value="<?php echo e(app('request')->input('search')); ?>">
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
                                    <th class="column-title">Status</th>
                                    <th class="column-title">Action</th>
                                </tr>
                            </thead>
                            <tbody>


                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="pointer">
                                    <td><?php echo e($value->cc_name); ?></td>
                                    <td><?php if($value->cc_status ==1): ?> Enable <?php else: ?> Disabled <?php endif; ?></td>
                                    <td class="last">

                                        <a href="javascript:void(0);" title="Edit" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase editCompanyClient" data-id="<?php echo e(base64_encode($value->cc_id)); ?>">View</a> 
                                        <a href="<?php echo e(route('companyclient.delete',base64_encode($value->cc_id))); ?>"  title="Delete" class="d-inline-block delete confirmation"><img src="<?php echo e(URL::asset('images/delete.svg')); ?>" alt=""></a>
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
                <div class="col-12 text-right pl-0 pr-0 pb-3 clearfix"><a href="javascript:void(0);" onclick="scrollToCustomerForm();" class="btn btn-outline-secondary  pl-4 pr-4 pt-2 pb-2  m-0" id="addNewRequest"> Add New Company </a></div>
            </div>

        </div>
    </div>
</div>


<div class="row pt-3">
    <div class="col-lg-12 col-md-12 col-12">
        <div class="x_panel newRequestForm">
            <div class="x_title">
                <h2 id="titleText">Add New Company</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="compRegister" action=" <?php echo e(route('companyclient.register')); ?>"> 

                <?php echo e(csrf_field()); ?>

                <input type="hidden" name="id" id="cc_id">
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
                    <div class="col-md-6 mb-3 padRig">
                        <label for="requestName">Name</label>
                        <input type="text" class="form-control" autocomplete="off" id="cc_name" name="cc_name" placeholder="Company Name" value="" >
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="status">Status</label>
                        <select  class="form-control select " id="cc_status" name="cc_status">
                            <option value="1">Enable</option>
                            <option value="0">Disable</option>
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