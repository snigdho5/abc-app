

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>News</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
                    <table id="datatable" class="table table-striped ">
                        <thead>
                            <tr class="headings">
                                <th class="column-title"> ID</th>
                                <th class="column-title">News Title</th>                  
                                <th class="column-title">News URL</th>                  
                                <th class="column-title">News Image</th>                  
                                <th class="column-title">News Order</th>                  
                                <th class="column-title">Status</th>
                                <th class="column-title">Action</th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="pointer">
                                <td><?php echo e($value->n_id); ?></td>
                                <td class="whiteSpace"><?php echo e($value->n_title); ?></td>
                                <td class="whiteSpace"><?php echo e($value->n_url); ?></td>
                                <td><img src="<?php if ($_SERVER['HTTP_HOST'] == 'localhost') {
    echo env('LOCAL_IMAGE_PATH');
} else {
    echo env('LIVE_IMAGE_PATH');
} ?>upload/news/<?php echo e($value->n_img); ?>" style="height: 50px;width:50px;"></td> 
                                <td><?php echo e($value->n_ordering); ?></td>
                                <td><?php if($value->n_status ==1): ?> Enable <?php else: ?> Disable <?php endif; ?></td>
                                <td class="last">
                                    <a href="javascript:void(0);" title="Edit" class="mr-4 ml-2 d-inline-block"><img data-id="<?php echo e(base64_encode($value->n_id)); ?>" class="editNews" src="<?php echo e(URL::asset('images/edit.svg')); ?>" alt=""></a> 
                                    <a href="<?php echo e(route('news.delete',base64_encode($value->n_id))); ?>"  title="Delete" class="d-inline-block delete confirmation"><img src="<?php echo e(URL::asset('images/delete.svg')); ?>" alt=""></a>
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
                <h2 id="titleText">News</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="newsRegister" action="<?php echo e(route('news.register')); ?> " enctype="multipart/form-data" > 
                <?php echo e(csrf_field()); ?>

                <input type="hidden" name="id" id="n_id">
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
                    <div class="col-md-12 mb-3 padRig">
                        <label for="pageName">News Title</label>
                        <?php if($errors->has('n_title')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('n_title')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control " autocomplete="off" id="n_title" name="n_title" placeholder="News Title" value="" >
                        </select>
                    </div>

                </div>
                
                <div class="row mt-4">
                    <div class="col-md-12 mb-3 padRig">
                        <label for="pageName">News heading</label>
                        <?php if($errors->has('n_heading')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('n_heading')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control " autocomplete="off" id="n_heading" name="n_heading" placeholder="News Heading" value="" >
                        </select>
                    </div>

                </div>

                <div class="row mt-4">
                    <div class="col-md-12 mb-3 padRig">
                        <label for="pageName">News URL</label>
                        <?php if($errors->has('n_url')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('n_url')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control " autocomplete="off" id="n_url" name="n_url" placeholder="News URL" value="" >
                        </select>
                    </div>

                </div>



                <div class="row mt-4">
                    <div class="col-md-4 mb-3 padRig">
                        <label for="pageName">News Image</label>
                        <?php if($errors->has('n_img')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('n_img')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input  name="n_img" type="file" id="imageInput">

                    </div>

                    <div class="col-md-8 mb-3 ">
                        <label for="status">Centre</label>
                        <?php if($errors->has('n_centre_id')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('n_centre_id')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <select class="form-control" multiple  name="n_centre_id[]" id="n_centre_id">
                            <option value="0">All</option>
                            <?php $__currentLoopData = $cData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key =>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php $locnm = getLocationName($value->location); ?>
                            <option value="<?php echo e($value->centre_id); ?>"><?php echo e($value->centre); ?>(<?php echo $locnm->loc_name; ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                </div>

                <div id="imagePage"></div>
                <div class="row mt-4">

                    <div class="col-md-4 mb-3 ">
                        <label for="status">News Ordering</label>
                        <?php if($errors->has('n_ordering')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('n_ordering')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <input type="text" class="form-control onlyNum" autocomplete="off" id="n_ordering" name="n_ordering" placeholder="News Ordering" value="" >
                    </div>



                    <div class="col-md-4 mb-3">
                        <label for="status">Status</label>
                        <?php if($errors->has('n_status')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('n_status')); ?></strong>
                        </span>
                        <?php endif; ?>
                        <select  class="form-control select"   id="n_status" name="n_status">

                            <option value="1" >Enable</option>
                            <option value="0" >Disable</option>


                        </select>
                    </div>

                    <div class="col-md-4 mb-3 align-self-center mt-4">
                        <div class="custom-control custom-checkbox">

                            <input type="checkbox" name="n_featured" class="custom-control-input all_cat" id="n_featured" >
                            <label class="custom-control-label" for="n_featured">Featured</label>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">

                    <div class="col-6  pb-3 clearfix">
                        <button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2   text-uppercase " id="submitNews">Submit</button>

                        <button type="button" class="btn btn-outline-secondary pl-4 pr-4 pt-2 pb-2 ml-2 text-uppercase reset-btn" >Reset</button></div>

                </div>



            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>