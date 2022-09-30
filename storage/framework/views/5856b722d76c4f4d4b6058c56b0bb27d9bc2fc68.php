<?php $__env->startSection('content'); ?>


<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Centre Configuration</h2>
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

                                     <a href="<?php echo e(route('meetingroom.data',base64_encode($value->centre_id))); ?>" title="Edit" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase ">View</a> 
                                    <a href="<?php echo e(route('meetingroom.delete',base64_encode($value->centre_id))); ?>"  title="Delete" class="d-inline-block delete confirmation"><img src="<?php echo e(URL::asset('images/delete.svg')); ?>" alt=""></a>
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