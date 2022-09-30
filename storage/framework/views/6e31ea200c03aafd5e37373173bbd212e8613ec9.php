<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Cancellation text</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
                    <table id="datatable" class="table table-striped ">
                        <thead>
                            <tr class="headings">
                                <th class="column-title">Cancellation Text</th>                  
                                <th class="column-title">Status</th>
                                <th class="column-title">Action</th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="pointer">
                                <td><?php echo e(reduceWords(strip_tags($value->ctext_inf))); ?></td>
                                <td><?php if($value->ctext_status ==1): ?> Enable <?php else: ?> Disable <?php endif; ?></td>
                                <td class="last">
                                     <a href="javascript:void(0);" title="Edit" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase editCtext" data-id="<?php echo e(base64_encode($value->ctext_id)); ?>">View</a> 
                                    <a href="<?php echo e(route('ctext.delete',base64_encode($value->ctext_id))); ?>"  title="Delete" class="d-inline-block delete confirmation"><img src="<?php echo e(URL::asset('images/delete.svg')); ?>" alt=""></a>
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
                <h2 id="titleText">Cancellation text</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="ctextRegister" action="<?php echo e(route('ctext.register')); ?> " enctype="multipart/form-data" > 
                <?php echo e(csrf_field()); ?>

                <input type="hidden" name="id" id="ctext_id">
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
                    <div class="col-lg-12 col-lg-12  mb-3"> 
                        <label for="pageName">Cancellation Text</label>
                        <?php if($errors->has('ctext_inf')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('ctext_inf')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <textarea class="form-control"  id="ctext_inf" rows="4" placeholder="eg:- Introduction" name="ctext_inf" ></textarea>
                        </select>
                    </div>

                </div>


                
                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <label for="status">Status</label>
                        <?php if($errors->has('status')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('ctext_status')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <select  class="form-control select"   id="ctext_status" name="ctext_status">

                            <option value="1" >Enable</option>
                            <option value="0" >Disable</option>


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