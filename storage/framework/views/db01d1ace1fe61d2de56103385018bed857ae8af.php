<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Centres</h2>
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
                                <td><?php if($value->status ==1): ?> Enable <?php else: ?> Disable <?php endif; ?></td>
                                <td class="last">

                                    <a href="javascript:void(0);" title="Edit" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase editCentre" data-id="<?php echo e(base64_encode($value->centre_id)); ?>">View</a> 
                                    <a href="<?php echo e(route('centre.delete',base64_encode($value->centre_id))); ?>"  title="Delete" class="d-inline-block delete confirmation"><img src="<?php echo e(URL::asset('images/delete.svg')); ?>" alt=""></a>
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
                <h2 id="titleText">Centres</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="centreRegister" action="<?php echo e(route('centre.register')); ?> " enctype="multipart/form-data" > 
                <?php echo e(csrf_field()); ?>

                <input type="hidden" name="id" id="centre_id">
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
                    <div class="col-md-4 mb-3">
                        <label for="status">Location</label>
                        <?php if($errors->has('location')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('location')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <select  class="form-control select"   id="location" name="location">
                            <option value="" >Select Location</option>
                            <?php $__currentLoopData = $locationData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value->loc_id); ?>" ><?php echo e($value->loc_name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="pageName">Centre</label>
                        <?php if($errors->has('centre')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('centre')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control " autocomplete="off" id="centre" name="centre" placeholder="Centre name" value="" >
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="pageName">Address</label>
                        <?php if($errors->has('centre_address')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('centre_address')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control " autocomplete="off" id="centre_address" name="centre_address" placeholder="Centre address" value="" >
                    </div>

                </div>

                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <label for="pageName">Email</label>
                        <?php if($errors->has('centre_email')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('centre_email')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control " autocomplete="off" id="centre_email" name="centre_email" placeholder="Centre email" value="" >
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="pageName">Mobile</label>
                        <?php if($errors->has('centre_mobile')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('centre_mobile')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control " autocomplete="off" id="centre_mobile" name="centre_mobile" placeholder="Centre mobile" maxlength="10" value="" >
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="pageName">Phone</label>
                        <?php if($errors->has('centre_phone')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('centre_phone')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control " autocomplete="off" id="centre_phone" name="centre_phone" placeholder="Centre phone" value="" >
                    </div>

                </div>
                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <label for="pageName">Centre Virtual tour link</label>
                        <?php if($errors->has('centre_vtlink')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('centre_vtlink')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control " autocomplete="off" id="centre_vtlink" name="centre_vtlink" placeholder="Centre Virtual tour link" value="" >
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="pageName" class="d-block">Image</label>
                        <?php if($errors->has('centre_image')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('centre_image')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <div  class="position-relative">
                            <span class="input-group-btn d-flex ">
                                <input id="customFile" type="file" class=" d-none" name="centre_image" onchange="$(this).parent().parent().find('.form-control').html($(this).val().split(/[\\|/]/).pop());" style="display: none;">
                            </span>
                            <label for="customFile" class=" form-control formFile custom-file-label rounded-pill" onclick="$(this).parent().find('input[type=file]').click();">Choose file</label>

                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="pageName" class="d-block">Gallery</label>
                        <?php if($errors->has('centre_image')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('centre_image')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <div  class="position-relative">
                            <span class="input-group-btn d-flex ">
                                <input id="customFile" type="file" multiple class=" d-none" name="centre_gallery[]" onchange="$(this).parent().parent().find('.form-control').html($(this).val().split(/[\\|/]/).pop());" style="display: none;">
                            </span>
                            <label for="customFile" class=" form-control formFile custom-file-label rounded-pill" onclick="$(this).parent().find('input[type=file]').click();">Choose files</label>

                        </div>
                    </div>


                </div>



                <div id="imagePage"></div>
                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <label for="pageName">Centre latitude</label>
                        <?php if($errors->has('centre_lat')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('centre_lat')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control onlyDec" autocomplete="off" id="centre_lat" name="centre_lat" placeholder="Centre latitude"  value="" >
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="pageName">Centre longitude</label>
                        <?php if($errors->has('centre_long')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('centre_long')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control onlyDec" autocomplete="off" id="centre_long" name="centre_long" placeholder="Centre longitude"  value="" >
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="pageName">Content</label>
                        <?php if($errors->has('centre_content')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('centre_content')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <textarea name="centre_content" class="form-control" id="centre_content"></textarea>
                    </div>
                </div>

                Servive Includes :- 
                <div class="row mt-4">
                    <div class="col mb-3 align-self-center mt-4">
                        <div class="custom-control custom-checkbox">

                            <input type="checkbox" name="flag_meeting_room" class="custom-control-input all_flag" id="flag_meeting_room" >
                            <label class="custom-control-label" for="flag_meeting_room">Meeting room</label>
                        </div>
                    </div>

                    <div class="col mb-3 align-self-center mt-4">
                        <div class="custom-control custom-checkbox">

                            <input type="checkbox" name="flag_co_working" class="custom-control-input all_flag" id="flag_co_working" >
                            <label class="custom-control-label" for="flag_co_working">Co-Working</label>
                        </div>
                    </div>

                    <div class="col mb-3 align-self-center mt-4">
                        <div class="custom-control custom-checkbox">

                            <input type="checkbox" name="flag_ser_office" class="custom-control-input all_flag" id="flag_ser_office" >
                            <label class="custom-control-label" for="flag_ser_office">Serviced office</label>
                        </div>
                    </div>

                    <div class="col mb-3 align-self-center mt-4">
                        <div class="custom-control custom-checkbox">

                            <input type="checkbox" name="flag_virtual_office" class="custom-control-input all_flag" id="flag_virtual_office" >
                            <label class="custom-control-label" for="flag_virtual_office">Virtual office</label>
                        </div>
                    </div>

                    <div class="col mb-3 align-self-center mt-4">
                        <div class="custom-control custom-checkbox">

                            <input type="checkbox" name="flag_built_to_suit" class="custom-control-input all_flag" id="flag_built_to_suit" >
                            <label class="custom-control-label" for="flag_built_to_suit">Built to suit office</label>
                        </div>
                    </div>

                    <div class="col mb-3 align-self-center mt-4">
                        <div class="custom-control custom-checkbox">

                            <input type="checkbox" name="flag_abc_lounge" class="custom-control-input all_flag" id="flag_abc_lounge" >
                            <label class="custom-control-label" for="flag_abc_lounge">ABC lounge</label>
                        </div>
                    </div>


                </div>

                Payment  Options:- 
                <div class="row mt-4">
                    <div class="col mb-3 align-self-center mt-4">
                        <div class="custom-control custom-checkbox">

                            <input type="checkbox" name="flag_payment_online" class="custom-control-input all_flag" id="flag_payment_online" >
                            <label class="custom-control-label" for="flag_payment_online">Online</label>
                        </div>
                    </div>
                    
                    <div class="col mb-3 align-self-center mt-4">
                        <div class="custom-control custom-checkbox">

                            <input type="checkbox" name="flag_payment_offline" class="custom-control-input all_flag" id="flag_payment_offline" >
                            <label class="custom-control-label" for="flag_payment_offline">Offline</label>
                        </div>
                    </div>

                    


                </div>
                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <label for="pageName">Centre map URL</label>
                        <?php if($errors->has('centre_url')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('centre_url')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control " autocomplete="off" id="centre_url" name="centre_url" placeholder="Centre map URL" value="" >
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="status">Status</label>
                        <?php if($errors->has('status')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('status')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <select  class="form-control select"   id="status" name="status">

                            <option value="1" >Enable</option>
                            <option value="0" >Disable</option>


                        </select>
                    </div>

                </div>

                <div class="row mt-4">

                    <div class="col-6  pb-3 clearfix">
                        <button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2   text-uppercase " id="submitCentre">Submit</button> 
                        <button type="button" class="btn btn-outline-secondary pl-4 pr-4 pt-2 pb-2 ml-2 text-uppercase reset-btn" >Reset</button></div>

                </div>

            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>