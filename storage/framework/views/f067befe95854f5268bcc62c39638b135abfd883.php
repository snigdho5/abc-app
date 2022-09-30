<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Business Connect Customers</h2>
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
                        <table id="datatable1" class="table table-striped ">
                            <thead>
                                <tr class="headings">
                                    <th class="column-title">Customers ID</th>
                                    <th class="column-title">Name</th>
                                    <th class="column-title">Mobile</th>
                                    <th class="column-title">Centre</th>
                                    <th class="column-title">Skills</th>
                                    <th class="column-title">Reg date</th>
                                    <th class="column-title">Status</th>
                                    <th class="column-title">Approval Date</th>
                                    <th class="column-title">Action</th>
                                </tr>
                            </thead>
                            <tbody>


                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="pointer">
                                    <td><?php echo e($value->cust_code); ?></td>
                                    <td><?php echo e($value->cust_nme); ?></td>
                                    <td>+91 <?php echo e($value->cust_mobile); ?></td>
                                    <td class="whiteSpace"><?php echo e(getCentreName($value->centre_id)); ?></td>
                                    <td class="whiteSpace"><?php echo e($value->sc_skills); ?> </td>
                                    <td class="whiteSpace"><?php echo e($value->sc_reg_date); ?> </td>
                                    <td><?php if($value->sc_status ==2): ?> Approved <?php elseif($value->sc_status ==1): ?> Pending <?php else: ?> Not registered <?php endif; ?></td>
                                    <td class="whiteSpace"><?php echo e($value->sc_appr_date); ?> </td>

                                    <td class="last">
                                        <?php if($value->sc_status>0): ?>
                                        <a href="<?php echo e(route('scustomer.changestatus',base64_encode($value->cust_id))); ?>"  class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase" ><?php if($value->sc_status ==2): ?> Un approve <?php elseif($value->sc_status ==1): ?> Approve  <?php endif; ?></a> 
                                        <?php else: ?>
                                        <a href="#" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase" >Not Applied</a> 
                                        <?php endif; ?>
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


        </div>
    </div>
</div>




<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>