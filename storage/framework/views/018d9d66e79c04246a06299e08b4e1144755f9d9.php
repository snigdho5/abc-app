<?php $__env->startSection('content'); ?>

<?php
$co_type = array('3' => 'Monthly', '6' => 'Night Watch (Monthly)', '7' => 'Flexi Seats (Monthly)');
$payment_opt = array('Credit/Debit Card Payment', 'Net Banking', 'Cheque Payment', 'Demand Draft', 'Cash Payment');

?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Cancelled Bookings</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
                    <form method="get">


                        <div class="row mt-4">
                            <div class="col-md-12 mb-3 padRig">
                                <input type="text" placeholder="Search by Name ,Email,Booking id" name="search"  autocomplete="off" class="form-control" value="<?php echo e(app('request')->input('search')); ?>">
                            </div>
                            <!--                            <div class="col-md-6 mb-3 padRig">
                                                            <button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2  m-0 text-uppercase">Search</button>
                                                        </div>-->
                        </div>

                        <div class="row mt-4">
                           <div class="col-md-6 mb-3 padRig">
                                <select class="form-control select" name="book_status">
                                    <?php for ($i = 1; $i <= count($statusArr); $i++) { ?>
                                        <option value="<?php echo $i; ?>" <?php if (!empty(app('request')->input('book_status')) && app('request')->input('book_status') == $i) {
                                        echo 'selected';
                                    } ?>><?php echo $statusArr[$i]; ?></option>
<?php } ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3 padRig">
                                <?php
                                $dates = '';

                                if (!empty(app('request')->input('req_date_range'))) {
                                    $dates = explode('and', app('request')->input('req_date_range'));
                                }
                                //dd($dates);
                                if (!empty($dates)) {
                                    $first_day = date('m-01-Y', strtotime($dates[0])); // hard-coded '01' for first day
                                    $last_day = date('m-t-Y', strtotime($dates[1]));
                                } else {
                                    $first_day = date('m-01-Y'); // hard-coded '01' for first day
                                    $last_day = date('m-t-Y');
                                }
                                ?>
                                <div id="reportrange1" class="form-control"> <i class="glyphicon glyphicon-calendar fa fa-calendar"></i> <span><?php echo e(date("M j, Y",strtotime($first_day))); ?> - <?php echo e(date("M j, Y",strtotime($last_day))); ?></span> <b class="caret"></b> </div>
                                <input type="hidden" name="req_date_range" id="req_date_range" value="<?php echo e(app('request')->input('req_date_range')); ?>">
                            </div>

                            <?php echo e(csrf_field()); ?>

                            <div class="col-md-4 mb-3 padRig">
                                <button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2  m-0 text-uppercase">Search</button>
                            </div>
                        </div>

                    </form>
                     <?php if (count($data) > 0) { ?>
                    <table id="datatable1" class="table table-striped ">
                        <thead>
                            <tr class="headings">
                                <th class="column-title">Booking ID</th>
                                <th class="column-title">Customer Name</th>
                                <th class="column-title">Mobile</th>
                                <th class="column-title">Booking Date</th>
                                <th class="column-title">Booking Amount</th>
                                <th class="column-title">Service Includes</th>
                                <th class="column-title">Status</th>
                                <th class="column-title"></th>
                                <th class="column-title">Action</th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="pointer">
                                <td class=" "><?php echo e($value->booking_code); ?></td>
                                <td class=" "><?php echo e($value->user_name); ?></td>
                                <td class=" "><?php echo e($value->user_phone); ?></td>
                                <td class=" "><?php echo e($value->created_at); ?></td>
                                <td class=" "><?php echo e($value->tot_book_amnt); ?></td>
                                <td class=" "><?php echo e(getServiceIncludes($value->booking_id)); ?></td>
                                <td>
                                        <?php 
                                        if($value->book_status ==1){
                                           echo "Pending"; 
                                        }
                                        if($value->book_status ==2){
                                           echo "Confirmed"; 
                                        }
                                        if($value->book_status ==3){
                                           echo "Pay at centre"; 
                                        }
                                        if($value->book_status ==4){
                                           echo "Cancelled"; 
                                        }
?>
                                    </td>
                                <td><a href="<?php echo e(route('bookingdetails.data',base64_encode($value->booking_id))); ?>" title="Edit" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase" data-id="<?php echo e(base64_encode($value->booking_id)); ?>">Booking Details</a> </td>
                                <td class="last">
<!--                                    <a href="<?php echo e(route('booking.data',base64_encode($value->booking_id))); ?>" title="Edit" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase" data-id="<?php echo e(base64_encode($value->booking_id)); ?>">View</a> -->
                                    <a href="<?php echo e(route('booking.delete',base64_encode($value->booking_id))); ?>"  title="Delete" class="d-inline-block delete confirmation"><img src="<?php echo e(URL::asset('images/delete.svg')); ?>" alt=""></a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                        </tbody>
                    </table>
                     <?php } else { ?>
                        <strong style="margin-left: 35%;color: red;">No data available </strong>
                    <?php } ?>
                    <?php echo e($data->links('common.custompagination')); ?>

                </div>

            </div>
        </div>
    </div>
</div>



<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>