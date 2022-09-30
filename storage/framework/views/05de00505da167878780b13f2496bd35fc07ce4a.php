

<?php $__env->startSection('content'); ?>


<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Discount</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
                    <table id="datatable" class="table table-striped ">
                        <thead>
                            <tr class="headings">
                                <th class="column-title">Promo Code</th>
                                <th class="column-title">Customer</th>
                                <th class="column-title">Category</th>
                                <th class="column-title">Discount(%)</th>
                                <th class="column-title">Max Offer Amount</th>
                                <th class="column-title">Min Order Amount</th>
                                <th class="column-title">Max Consumed Amount</th>
                                <th class="column-title">Status</th>
                                <th class="column-title">Action</th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="pointer">
                                <td class="whiteSpace"><?php echo e($value->d_code); ?></td>
                                <td class="whiteSpace"><a href="<?php echo e(route('discount.detail',base64_encode($value->d_id))); ?>"><?php if($value->d_cust_type ==1): ?> All Customer <?php elseif($value->d_cust_type ==2): ?> Selected Customer <?php else: ?> New Customers <?php endif; ?></a></td>
                                <td class="whiteSpace">
                                    <?php
                                    $catStr = '';
                                    $catArr = explode(',', $value->d_cat);
                                    foreach ($catArr as $k => $v) {
                                        if ($v) {
                                            $catStr .= '<strong>' . getCatName($v) . "</strong>, ";
                                        }else if($v==0){
                                            $catStr .= "<strong> All Categories</strong>, ";
                                        }
                                    }
                                    echo rtrim($catStr, ' ,');
                                    ?>
                                </td>
                                <td class="whiteSpace"><?php echo e($value->d_amnt); ?></td>
                                <td class="whiteSpace"><?php echo e($value->d_max_ofr_amnt); ?></td>
                                <td class="whiteSpace"><?php echo e($value->d_min_ordr_amnt); ?></td>
                                <td class="whiteSpace"><?php echo e($value->d_max_consumed); ?></td>

                                <td class="whiteSpace">
                                    <?php if($value->d_status ==1): ?> Available <?php else: ?> Not Available <?php endif; ?>
                                </td>
                                <td class="last">
                                    <a href="<?php echo e(route('discount.delete',base64_encode($value->d_id))); ?>"  title="Delete" class="d-inline-block delete confirmation"><img src="<?php echo e(URL::asset('images/delete.svg')); ?>" alt=""></a>
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
                <h2 id="titleText" >Add New Discount</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="discountRegister" action="<?php echo e(route('discount.register')); ?>"> 

                <?php echo e(csrf_field()); ?>

                <input type="hidden" name="d_id" id="d_id" >
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
                        <label for="d_code">Promo Code</label>
                        <input type="text" class="form-control " autocomplete="off" id="d_code" name="d_code" placeholder="" value="<?php echo e($discountCode); ?>" >
                    </div>
                </div>


                <div class="row mt-4">
                    <div class="col-md-6 mb-3 padRig">
                        <label for="d_code">Customer</label>
                        <select  class="form-control select"  id="d_cust" name="d_cust" >

                            <option value="all">All Customer</option>
                            <option value="select_cust">Select Customer</option>
                            <option value="enter_mob">Enter Mobile</option>

                        </select>
                    </div>
                    <div class="col-md-6 mb-3 padRig " >
                        <label for="d_code">Customer Mobile (in case of multiple please use comma)</label>
                        <input type="text" class="form-control" autocomplete="off" id="cust_mob" name="cust_mob" placeholder=""  disabled >

                    </div>
                </div>

                <div class="row mt-4 sel-cust-multiple" style="display: none;">
                    <div class="col-md-12 mb-3">
                        <label for="selectServices ">Select Customer (Multiple)</label>
                        <select  class="form-control select2_multiple" multiple="multiple"  id="d_catid" name="d_custid[]">
                            <?php $__currentLoopData = $custData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value['cust_id']); ?>"><?php echo e($value['cust_nme'] .'('.$value['cust_mobile'].')'); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-12 mb-3">
                        <label for="selectServices ">Select Category (Multiple)</label>
                        <select  class="form-control select2_multiple" multiple="multiple"  id="d_catid" name="d_catid[]">
                            <?php $__currentLoopData = $catData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value['acat_id']); ?>"><?php echo e($value['acat_name']); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select><br />
                        Note: Please select only, If you want to restrict the offer to particular category.
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-3 mb-3">
                        <label for="commentTech">Discount Amount (In %)</label>
                        <input type="text" class="form-control number" autocomplete="off" id="d_amnt" name="d_amnt" placeholder="" value=""  >
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="commentTech">Maximum Offer Amount(INR)</label>
                        <input type="text" class="form-control number" autocomplete="off" id="d_max_ofr_amnt" name="d_max_ofr_amnt" placeholder="" value=""  >
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="commentTech">Minimum Order Amount(INR)</label>
                        <input type="text" class="form-control number" autocomplete="off" id="d_min_ordr_amnt" name="d_min_ordr_amnt" placeholder="" value=""  >
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="commentTech">Max Consumed Count</label>
                        <input type="text" class="form-control number" autocomplete="off" id="d_max_consumed" name="d_max_consumed" placeholder="" value=""  required="required" >
                    </div>

                </div>

                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <label for="status">Status</label>
                        <select  class="form-control select"  id="d_status" name="d_status">
                            <option value="1"> Available</option>
                            <option value="0"> Not Available</option>
                        </select>
                    </div>

                </div>
                <div class="row mt-4">

                    <div class="col-6  pb-3 clearfix">
                        <button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2   text-uppercase " id="submitNews">Submit</button>

                        <button type="button" class="btn btn-outline-secondary pl-4 pr-4 pt-2 pb-2 ml-2 text-uppercase" >Reset</button></div>

                </div>

            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>