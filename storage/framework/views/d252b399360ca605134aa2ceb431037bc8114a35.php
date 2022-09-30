<?php $__env->startSection('content'); ?>
<div class="row pt-3">
    <div class="col-lg-12 col-md-12 col-12">
        <div class="x_panel newRequestForm">
            <div class="x_title">	
                <h2>Change Password</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="changePwdAdmin" action=" <?php echo e(route('admin.changePwd')); ?>"> 
                <?php echo e(csrf_field()); ?>

                <input type="hidden" name="submit" value="1">


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
                        <label for="categoryId">Previous Password</label>
                        <input type="password" class="form-control "  name="old_password"  value="<?php echo e(old('old_password')); ?>" required="">
                    </div>
                    <div class="col-md-4 mb-3 padRig">
                        <label for="categoryName">New Password</label>
                        <input type="password" class="form-control " name="new_password"  value="" required="">
                    </div>
                    <div class="col-md-4 mb-3 padRig">
                        <label for="categoryName">Confirm Password</label>
                        <input type="password" class="form-control " name="cnf_password"  value="" required="">
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-6  pb-3 clearfix"><button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2 mr-3 text-uppercase" id="editCategory">Change Password</button> </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>