<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Service Categories</h2>
		
		        <a href="<?php echo e(route('meetingroom.managecenterconfig')); ?>" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase pull-right" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a> 
		
                <div class="clearfix"></div>
            </div>

            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
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
                    <table id="datatable" class="table table-striped ">
                        <thead>
                            <tr class="headings">
                                <th class="column-title">Service Category</th>
                                <th class="column-title">Status</th>
				 <th class="column-title">Action</th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="pointer">
                                <td><?php echo e(getCatName($value->cat_id)); ?></td>
				   <td><?php if($value->tstatus ==1): ?> Enable <?php else: ?> Disable <?php endif; ?></td>
				  <td class="last">
                                        <?php if($value->tstatus==1): ?>
                                        <a href="<?php echo e(route('center-service.changestatus',base64_encode($value->id))); ?>"  class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase" >Disable</a> 
                                        <?php else: ?>
                                        <a href="<?php echo e(route('center-service.changestatus',base64_encode($value->id))); ?>" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase" >Enable</a> 
                                        <?php endif; ?>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>