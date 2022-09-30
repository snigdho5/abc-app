<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Support Service</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
                    <table id="datatable" class="table table-striped ">
                        <thead>
                            <tr class="headings">
                                <th class="column-title">Text</th>                  
                                <th class="column-title">Image</th>                                  
                                <th class="column-title">Status</th>
                                <th class="column-title">Action</th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="pointer">
                                <td class="whiteSpace"><?php echo e(($value->ss_text)); ?></td>
                                <td><img src="<?php if ($_SERVER['HTTP_HOST'] == 'localhost') {
    echo env('LOCAL_IMAGE_PATH');
} else {
    echo env('LIVE_IMAGE_PATH');
} ?>upload/supportservice/<?php echo e($value->ss_img); ?>" style="height: 50px;width:50px;"></td> 
                                <td><?php if($value->ss_status ==1): ?> Enable <?php else: ?> Disable <?php endif; ?></td>
                                <td class="last">
                                    <a href="javascript:void(0);" title="Edit" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase editSupportService" data-id="<?php echo e(base64_encode($value->ss_id)); ?>">View</a> 
                                    <a href="<?php echo e(route('sprtserv.delete',base64_encode($value->ss_id))); ?>"  title="Delete" class="d-inline-block delete confirmation"><img src="<?php echo e(URL::asset('images/delete.svg')); ?>" alt=""></a>
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
                <h2 id="titleText">Support Service</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="supportservRegister" action="<?php echo e(route('sprtserv.register')); ?> " enctype="multipart/form-data" > 
                <?php echo e(csrf_field()); ?>

                <input type="hidden" name="id" id="ss_id">
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
                    <div class="col-md-12 mb-3">
                        <label for="pageName">Text</label>
                        <?php if($errors->has('ss_text')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('ss_text')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control " autocomplete="off" id="ss_text" name="ss_text" placeholder=" " value="">
                    </div>
                </div>
                <div class="row mt-4">


                    <div class="col-md-6 mb-3">
                        <label for="pageName" class="d-block">Image</label>
                        <?php if($errors->has('ss_img')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('ss_img')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <div  class="position-relative">
                            <span class="input-group-btn d-flex ">
                                <input id="customFile" type="file" class="d-none" name="ss_img" onchange="$(this).parent().parent().find('.form-control').html($(this).val().split(/[\\|/]/).pop());" style="display: none;">
                            </span>
                            <label for="customFile" class=" form-control formFile custom-file-label rounded-pill" onclick="$(this).parent().find('input[type=file]').click();">Choose file</label>

                        </div>
                    </div>



                </div>

                
                <div class="pt-5 pb-5"id="imagePage"></div>


                <div class="row mt-4">
 
                    <div class="col-md-4 mb-3">
                        <label for="status">Status</label>
                        <?php if($errors->has('ss_status')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('ss_status')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <select  class="form-control select"   id="ss_status" name="ss_status">

                            <option value="1" >Enable</option>
                            <option value="0" >Disable</option>


                        </select>
                    </div>

                </div>

                <div class="row mt-4">

                    <div class="col-6  pb-3 clearfix">
                        <button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2   text-uppercase " id="submitOffer">Submit</button> 
                        <button type="button" class="btn btn-outline-secondary pl-4 pr-4 pt-2 pb-2 ml-2 text-uppercase reset-btn" >Reset</button></div>

                </div>

            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>