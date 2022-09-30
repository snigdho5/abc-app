<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Partnership Opportunities</h2>
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
                                <th class="column-title">Name</th>
                                <th class="column-title">Email</th>
                                <th class="column-title">Mobile</th>
                                <th class="column-title">Location</th>
                                <th class="column-title">Query</th>
                                <th class="column-title">Date</th>
                                <th class="column-title">Action</th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php $loc = getLocationName($value->po_loc); ?>
                            <tr class="pointer">
                                <td><?php echo e($value->po_name); ?></td>
                                <td><?php echo e($value->po_email); ?></td>
                                <td><?php echo e($value->po_phone); ?></td>
                                <td><?php echo e($loc->loc_name); ?></td>
                                <td><?php echo e($value->po_text); ?></td>
                                <td><?php echo e($value->created_at); ?></td>

                                <td class="last">
                                    <a href="<?php echo e(route('pquote.delete',base64_encode($value->po_id))); ?>"  title="Delete" class="d-inline-block delete confirmation"><img src="<?php echo e(URL::asset('images/delete.svg')); ?>" alt=""></a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>
                    </table>
                </div>

            </div>
            <div class="clearfix"></div>
            <?php if (count($data) > 0) { ?>
                <div class="row  m-0">
                    <div class="col-12 text-right pl-0 pr-0 pb-3 clearfix"><a href="<?php echo e(route('pquote.export')); ?>" class="btn btn-outline-secondary pl-4 pr-4 pt-2 pb-2  m-0" id="exporttoExcel"> <img src="<?php echo e(URL::asset('images/excel.svg')); ?>" class="pr-2" alt="Export to Excel"> Export to Excel </a></div>

                </div>
            <?php } ?>  

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>