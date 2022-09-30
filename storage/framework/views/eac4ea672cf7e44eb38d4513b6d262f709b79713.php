<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Service Configuration</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
                    <table id="datatable" class="table table-striped ">
                        <thead>
                            <tr class="headings">
                                <th class="column-title">No</th>
                                <th class="column-title">Service Config</th>
                                <th class="column-title">Category</th>
                                <th class="column-title">Seater</th>
                                <th class="column-title">Status</th>
                                <th class="column-title">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php $i = 1;?>
                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="pointer">
                                <td><?php echo $i++;?></td>
                                <td><?php echo e($value->ms_name); ?></td>
                                <td><?php echo e(getCatName($value->ms_cat)); ?></td>
                                <td><?php echo e($value->ms_type); ?></td>
                                 <td><?php if($value->ms_status ==1): ?> Enable <?php else: ?> Disable <?php endif; ?></td>
                                <td class="last">
                                    <a href="javascript:void(0);" title="Edit" class="mr-4 ml-2 d-inline-block"><img data-id="<?php echo e(base64_encode($value->ms_id)); ?>" class="editMsinfo" src="<?php echo e(URL::asset('images/edit.svg')); ?>" alt=""></a> 
                                    <a href="<?php echo e(route('msinfo.delete',base64_encode($value->ms_id))); ?>"  title="Delete" class="d-inline-block delete confirmation"><img src="<?php echo e(URL::asset('images/delete.svg')); ?>" alt=""></a>
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
                <h2 id="titleText"> Add Service Configuration</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="msinfoRegister" action="<?php echo e(route('msinfo.register')); ?> "> 

                <?php echo e(csrf_field()); ?>

                <input type="hidden" name="ms_id" id="ms_id">
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
                        <label for="locationName">Service Category</label>
                        <?php if($errors->has('ms_cat')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('ms_cat')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <select  class="form-control select " id="ms_cat" name="ms_cat" onchange="check_serflag(0)">
                            <option value="" >Select Service  Category</option>
                            <?php $__currentLoopData = $cdata; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value->acat_id); ?>" ><?php echo e($value->acat_name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3 padRig">
                        <label for="locationName">Title</label>
                        <?php if($errors->has('ms_name')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('ms_name')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control " autocomplete="off" id="ms_name" name="ms_name" placeholder=""  >

                    </div>
                    
                    <div class="col-md-4 mb-3 padRig">
                        <label for="locationName">Seater</label>
                        <?php if($errors->has('ms_type')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('ms_type')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control onlyNum" autocomplete="off" id="ms_type" name="ms_type" placeholder=""  >

                    </div>
                    
                </div>
                <div class="row mt-4">
                    
                    <div class="col-md-4 mb-3 padRig ms_hour">
                        <label for="locationName">Rate/hour</label>
                        <?php if($errors->has('ms_hour')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('ms_hour')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control onlyDec" autocomplete="off" id="ms_hour" name="ms_hour" placeholder=""  >

                    </div>
                    
                    <div class="col-md-4 mb-3 padRig ms_half">
                        <label for="locationName">Half Day</label>
                        <?php if($errors->has('ms_half')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('ms_half')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control onlyDec " autocomplete="off" id="ms_half" name="ms_half" placeholder=""  >

                    </div>
                    <div class="col-md-4 mb-3 padRig ms_full">
                        <label for="locationName">Full Day</label>
                        <?php if($errors->has('ms_full')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('ms_full')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control onlyDec" autocomplete="off" id="ms_full" name="ms_full" placeholder=""  >

                    </div>
                    
                </div>
                
                <div class="row mt-4">
                    
                    <div class="col-md-4 mb-3 padRig ms_month">
                        <label for="locationName">Month</label>
                        <?php if($errors->has('ms_month')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('ms_month')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control onlyDec" autocomplete="off" id="ms_month" name="ms_month" placeholder=""  >

                    </div>
                    <div class="col-md-4 mb-3 padRig ms_quart">
                        <label for="locationName">Quarterly</label>
                        <?php if($errors->has('ms_quart')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('ms_quart')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control onlyDec" autocomplete="off" id="ms_quart" name="ms_quart" placeholder=""  >

                    </div>
                    <div class="col-md-4 mb-3 padRig ms_hy">
                        <label for="locationName">Half Yearly</label>
                        <?php if($errors->has('ms_hy')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('ms_hy')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control onlyDec" autocomplete="off" id="ms_hy" name="ms_hy" placeholder=""  >

                    </div>
                    
                </div>
                <div class="row mt-4">
                    
                    <div class="col-md-4 mb-3 padRig ms_year">
                        <label for="locationName">Year</label>
                        <?php if($errors->has('ms_year')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('ms_year')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control onlyDec" autocomplete="off" id="ms_year" name="ms_year" placeholder=""  >

                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="status">Status</label>
                        <?php if($errors->has('ms_status')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('ms_status')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <select  class="form-control select " id="ms_status" name="ms_status">
                            <option value="1"  >Enable</option>
                            <option value="0" >Disable</option>
                        </select>
                    </div>
                </div>

                
                 <div class="row mt-4">
                	<div class="col-6  pb-3 clearfix">
                    	<button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2   text-uppercase " id="submitMsinfo">Submit</button>
                    	<button type="button" class="btn btn-outline-secondary pl-4 pr-4 pt-2 pb-2 ml-2 text-uppercase btn-reset reset-btn " id="submitCategory">Reset</button></div>
            	</div>

            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>