<?php $__env->startSection('content'); ?>


<div class="row pt-3">
    <div class="col-lg-12 col-md-12 col-12">
        <div class="x_panel newRequestForm">
            <div class="x_title">
                <h2 id="titleText" >Upload Corporate Client</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="uploadCompany" action="<?php echo e(route('corporatecust.upload')); ?> " enctype="multipart/form-data">
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
<!--                <div class="row mt-4">                   
                    <div class="col-md-4 mb-3 ">
                        <label for="application_file"> File</label>
                        <input  name="application_file" type="file" id="application_file" >
                    </div>
                </div>-->
                <div class="row mt-4">
                    <div class="col-12  pb-3 clearfix">
                        <div class="d-block filedetail mb-4">
                        <label for="corporate_file"> File</label>
                        <input  name="corporate_file" type="file" id="corporate_file" >  
                        
                        </div>
                        
                        <button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2  m-0 text-uppercase" id="submitapplication">Upload </button>
                        
                       
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>