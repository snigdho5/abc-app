<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Centre Location Links</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
                    <table id="datatable" class="table table-striped ">
                        <thead>
                            <tr class="headings">
                                <th class="column-title">Centre</th>
                                <th class="column-title">Mobile</th>
                                <th class="column-title">Centre Link</th>
                                <th class="column-title">Action</th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="pointer">
                                <td><?php echo e(getCentreName($value->centre_id)); ?></td>
                                <td><?php echo e($value->mobile); ?></td>
                                <td><?php echo e($value->link); ?></td>
                               
                                <td class="last">
                                    <a href="<?php echo e(route('centreloc.delete',base64_encode($value->id))); ?>"  title="Delete" class="d-inline-block delete confirmation"><img src="<?php echo e(URL::asset('images/delete.svg')); ?>" alt=""></a>
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
                <h2 id="titleText"> Send Centre Location Link</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="applinkRegister" action="<?php echo e(route('centreloc.register')); ?> " enctype="multipart/form-data"> 

                <?php echo e(csrf_field()); ?>

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
                    <?php if(Auth::guard('admin')->user()->admin_role == 1): ?>
                    <div class="col-md-6 mb-3">
                        <label for="status">Centre</label>
                        <?php if($errors->has('center_id')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('center_id')); ?></strong>
                        </span>
                        <?php endif; ?>
                        
                        <select  class="form-control select config-val"   id="center_id_link" name="centre_id">
                            <option value="" >Select Centre</option>
                            <?php $__currentLoopData = $centreData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php $locnm = getLocationName($value->location); ?>
                            <option value="<?php echo e($value->centre_id); ?>" ><?php echo e($value->centre); ?> (<?php echo $locnm->loc_name; ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    
                    <?php else: ?> 
                    <input type="hidden" name="centre_id" value="<?php echo e(Auth::guard('admin')->user()->center_id); ?>" >
                    <?php endif; ?>
                    <div class="col-md-6 mb-3 padRig">
                        <label for="locationName">Mobile</label>
                        <?php if($errors->has('mobile')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('mobile')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control onlyNum" autocomplete="off" id="mobile" name="mobile" placeholder=" Mobile Number"  maxlength="10" minlength="10" required>
                    </div>
                    
                    
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-12 mb-3 padRig">
                        <label for="locationName">Centre Location Link</label>
                        <?php if($errors->has('link')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('link')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <textarea class="form-control " autocomplete="off" id="link" name="link" placeholder="Centre Link" rows="4" required=""></textarea>

                    </div>
                    
                </div>
<div class="row mt-4">
                	<div class="col-6  pb-3 clearfix">
                    	<button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2   text-uppercase " id="submitCategory">Send</button>
                    	<button type="button" class="btn btn-outline-secondary pl-4 pr-4 pt-2 pb-2 ml-2 text-uppercase btn-reset reset-btn" id="submitCategory">Reset</button></div>
  </div>


            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>