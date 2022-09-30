<?php $__env->startSection('content'); ?>


<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Virtual Office Package</h2>
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
                                <th class="column-title">Package</th>                             
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
                                <td class="whiteSpace">
                                    <?php
                                    $cat_name = '';
                                    $rr_id = '';
                                    $status = 0;
                                    foreach ($value['RateDetail'] as $key => $value1) {
                                        $cat_name = getCatName($value1->ms_cat);
                                        $rr_id = $value1->rr_id;
                                        $status = $value1->ms_status;
                                    }
                                    echo $cat_name;
                                    ?>
                                </td>
                                <td><?php if($status ==1): ?> Enable <?php else: ?> Disable <?php endif; ?></td>
                                <td class="last">
                                    
                                    
                                    <a href="javascript:void(0);" title="View" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase editVOPackage" data-id="<?php echo e(base64_encode($rr_id)); ?>">View</a> 
                                    
                                    <a href="<?php echo e(route('vopackage.delete',base64_encode($rr_id))); ?>"  title="Delete" class="d-inline-block delete confirmation"><img src="<?php echo e(URL::asset('images/delete.svg')); ?>" alt=""></a>
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

<div class="row pt-3">

    <div class="col-lg-12 col-md-12 col-12">
        <div class="x_panel newRequestForm">
            <div class="x_title">
                <h2 id="titleText">Add Virtual Office Package</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="vopRegister" action="<?php echo e(route('vopackage.register')); ?> " enctype="multipart/form-data" > 
                <?php echo e(csrf_field()); ?>

                <input type="hidden" name="id" id="rr_id">
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
                    <div class="col-md-6 mb-3">
                        <label for="status">Centre</label>
                        <?php if($errors->has('center_id')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('center_id')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <select  class="form-control select config-val"   id="center_id" name="center_id">
                            <option value="" >Select Centre</option>
                            <?php $__currentLoopData = $centreData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php $locnm = getLocationName($value->location); ?>
                            <option value="<?php echo e($value->centre_id); ?>" ><?php echo e($value->centre); ?> (<?php echo $locnm->loc_name; ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="status">Category</label>
                        <?php if($errors->has('ms_cat')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('ms_cat')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <select  class="form-control select config-val"   id="ms_cat" name="ms_cat">
                            <option value="" >Select Category</option>
                            <?php $__currentLoopData = $minfoData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value->acat_id); ?>" ><?php echo e($value->acat_name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>


                </div>

                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <label for="pageName">Quarterly plan</label>
                        <?php if($errors->has('ms_pln_quart')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('ms_pln_quart')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control config-val onlyDec" autocomplete="off" id="ms_pln_quart" name="ms_pln_quart" placeholder="Quarterly plan" value="" >
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="pageName">Half Yearly plan</label>
                        <?php if($errors->has('ms_pln_hy')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('ms_pln_hy')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control config-val onlyDec" autocomplete="off" id="ms_pln_hy" name="ms_pln_hy" placeholder="Half Yearly plan" value="" >
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="pageName">Yearly plan</label>
                        <?php if($errors->has('ms_pln_yr')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('ms_pln_yr')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control config-val onlyDec" autocomplete="off" id="ms_pln_yr" name="ms_pln_yr" placeholder="Yearly plan" value="" >
                    </div>




                </div>




                One Time Activation Charges (Applicable for all packages)


                <div class="row mt-4">

                    <div class="col-md-4 mb-3">
                        <label for="pageName">Activation Fee</label>
                        <?php if($errors->has('activation_fee')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('activation_fee')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control config-val onlyDec" autocomplete="off" id="activation_fee" name="activation_fee" placeholder="Activation Fee" value="" >
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="pageName">Security Deposit</label>
                        <?php if($errors->has('security_deposit')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('security_deposit')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control config-val onlyDec" autocomplete="off" id="security_deposit" name="security_deposit" placeholder="Security Deposit" value="" >
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="status">Status</label>
                        <?php if($errors->has('status')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('status')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <select  class="form-control select"   id="ms_status" name="ms_status">

                            <option value="1" >Enable</option>
                            <option value="0" selected>Disable</option>


                        </select>
                    </div>

                </div>

                <div class="row mt-4">
                    <div class="col-6  pb-3 clearfix">
                        <button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2   text-uppercase " id="submitCategory">Submit</button>
                        <button type="button" class="btn btn-outline-secondary pl-4 pr-4 pt-2 pb-2 ml-2 text-uppercase btn-reset reset-btn" >Reset</button></div>


                </div>


            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>