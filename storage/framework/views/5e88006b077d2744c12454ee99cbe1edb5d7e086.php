

<?php $__env->startSection('title', 'login here'); ?>


<?php $__env->startSection('content'); ?>

<div class="container pl-3 pr-3 bg-light">
<div class="row">
<div class="col-lg-12 text-center"></div></div>
    <div class="row mt-5 justify-content-center ">
        <div class="col-xl-3 col-lg-5 col-md-7 col-sm-8 col-11">
            <div class="panel panel-default row mb-5 pb-md-4">
            <div class="col-12 mb-md-5 mb-4 text-center">
            <img src="<?php echo e(URL::asset('images/abc-logo.svg')); ?>" />
            </div>
<div class="col-12 bg-white p-4 logPag">
                <div class="panel-heading size-18 pt-3 pb-2">Admin Login</div>

                <div class="panel-body">
                    <form class="form-horizontal" id="loginForm" method="POST" action="<?php echo e(route('admin.auth.loginAdmin')); ?>">
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
                        <div class="form-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
                            <label for="email" class="control-label">E-Mail Address</label>

                            
                                <input id="email" autocomplete="off" type="email" class="form-control" name="email" value="<?php echo e(old('email')); ?>" required autofocus>

                                <?php if($errors->has('email')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('email')); ?></strong>
                                    </span>
                                <?php endif; ?>
                        
                        </div>

                        <div class="form-group<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
                            <label for="password" class=" control-label">Password</label>

                  
                                <input id="password" type="password" class="form-control" name="password" required>

                                <?php if($errors->has('password')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('password')); ?></strong>
                                    </span>
                                <?php endif; ?>
                          
                        </div>

                        <div class="form-group">
                           
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>> Remember Me
                                    </label>
                            
                            </div>
                        </div>
                        <div class="form-group">
                      
                                <button type="submit" class="sbmt_btn form-control"> Login</button>

                                
                          
</div>

                          </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

 <script>
 $(document).ready(function(){
	 $('#loginForm').validate({ // initialize the plugin
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 6
            },
        }
    });
 })
 </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>