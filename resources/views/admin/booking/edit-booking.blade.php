@extends('admin.layouts.app')

@section('content')

<?php
$payment_opt = array('Credit/Debit Card Payment', 'Net Banking', 'Cheque Payment', 'Demand Draft', 'Cash Payment');
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage  Bookings</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
                    <form method="get">


                        <div class="row mt-4">

                            <div class="col-md-12 mb-3 padRig">
                                <label>Select date</label>
                                <input type="text" placeholder="Search by Name ,Email,Booking id" name="search"  autocomplete="off" class="form-control" value="{{ app('request')->input('search') }}">
                            </div>
                            <!--                            <div class="col-md-6 mb-3 padRig">
                                                            <button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2  m-0 text-uppercase">Search</button>
                                                        </div>-->
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6 mb-3 padRig">
                                <select class="form-control select" name="book_status">
                                    @foreach($statusArr as $key=>$value)
                                    <option value="<?php echo $key; ?>" <?php
                                    if (!empty(app('request')->input('book_status')) && app('request')->input('book_status') == $key) {
                                        echo 'selected';
                                    }
                                    ?>><?php echo $value; ?></option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3 padRig">
                                <select class="form-control select" name="service_status">
                                    @foreach($serviceArr as $key=>$value)
                                    <option value="<?php echo $key; ?>" <?php
                                    if (!empty(app('request')->input('service_status')) && app('request')->input('service_status') == $key) {
                                        echo 'selected';
                                    }
                                    ?>><?php echo $value; ?></option>
                                    @endforeach
                                </select>
                            </div>

                            {{ csrf_field() }}

                        </div>
                        <div class="row mt-4">
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


                                <div id="reportrange1" class="form-control"> <i class="glyphicon glyphicon-calendar fa fa-calendar"></i> <span>{{date("M j, Y",strtotime($first_day))}} - {{date("M j, Y",strtotime($last_day))}}</span> <b class="caret"></b> </div>
                                <input type="hidden" name="req_date_range" id="req_date_range" value="{{app('request')->input('req_date_range')}}">
                            </div>
                            <div class="col-md-4 mb-3 padRig">
                                <button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2  m-0 text-uppercase">Search</button>
                            </div>					
                        </div>

                    </form>
                    @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                    @endif
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
                                    <th class="column-title">Action</th>
                                </tr>
                            </thead>
                            <tbody>


                                @foreach($data as $key=>$value)
                                <tr class="pointer">
                                    <td class=" ">{{$value->booking_code}}</td>
                                    <td class=" ">{{$value->user_name}}</td>
                                    <td class=" ">{{$value->user_phone}}</td>
                                    <td class=" ">{{$value->created_at}}</td>
                                    <td class=" ">{{$value->tot_book_amnt}} INR</td>
                                    <td class=" ">
                                        {{getServiceIncludes($value->booking_id)}}
                                        <?php
                                        if ($value->service_type == 'Virtual Office') {
                                            $bid = getServicePackageName($value->booking_id);

                                            $bname = getPlanName($value->ser_config);

                                            echo '<br/> <strong>Package - </strong>' . getPackageName($bid);
                                            echo '<br/> <strong>Plan - </strong>' . $bname;
                                        }
                                        ?>

                                    </td>
                                    <td>
                                        <?php
                                        if ($value->book_status == 1) {
                                            echo "Pending";
                                        }
                                        if ($value->book_status == 2) {
                                            echo "Confirmed";
                                        }
                                        if ($value->book_status == 3) {
                                            echo "Pay at centre";
                                        }
                                        if ($value->book_status == 4) {
                                            echo "Cancelled";
                                        }
                                        if ($value->book_status == 5) {
                                            echo "Closed";
                                        }
                                        if ($value->book_status == 7) {
                                            echo "Bill to Company";
                                        }
                                        if ($value->book_status == 8) {
                                            echo "Bill to Company - Paid";
                                        }
                                        ?>
                                    </td>
    <!--                                    <td><a href="{{route('bookingdetails.data',base64_encode($value->booking_id))}}" title="Edit" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase" data-id="{{base64_encode($value->booking_id)}}">Booking Details</a> </td>-->
                                    <td class="last">
                                        <a href="{{route('booking.data',base64_encode($value->booking_id))}}" title="Edit" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase" data-id="{{base64_encode($value->booking_id)}}">View</a>
                                        <?php if ($value->book_status == 1 || $value->book_status == 4) { ?>
                                            <a href="{{route('booking.delete',base64_encode($value->booking_id))}}"  title="Delete" class="d-inline-block delete confirmation"><img src="{{ URL::asset('images/delete.svg') }}" alt=""></a>
                                        <?php } ?>
                                    </td>
                                </tr>
                                @endforeach


                            </tbody>
                        </table>
                    <?php } else { ?>
                        <strong style="margin-left: 35%;color: red;">No data available </strong>
                    <?php } ?>
                    {{ $data->links('common.custompagination') }}
                </div>

            </div>
        </div>
    </div>
</div>

<div class="row pt-3" >
    <div class="col-lg-12 col-md-12 col-12">
        <div class="x_panel newRequestForm">
            <div class="x_title">
                <h2 id="titleText"> Bookings</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="bookingEdit" action="{{route('booking.edit')}} " enctype="multipart/form-data" >
                {{ csrf_field() }}
                <input type="hidden" name="booking_id" id="booking_id" value="{{$bookingData['booking_id']}}">

                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.
                    @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                    @endforeach
                </div>
                @endif
                @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
                @endif

                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <label for="status">Booking ID</label>
                        <input type="text" class="form-control" autocomplete="off" id="booking_code" name="booking_code"   value="{{$bookingData['booking_code']}}" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="status">Booking Amount (INR)</label>
                        <input type="text" class="form-control" autocomplete="off" id="tot_book_amnt" name="tot_book_amnt"   value="{{$bookingData['tot_book_amnt']}}" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="status">Booking Date</label>
                        <input type="text" class="form-control" autocomplete="off" id="created_at" name="created_at"   value="{{$bookingData['created_at']}}" readonly>
                    </div>



                </div>

                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <label for="status">City</label>
                        <input type="text" class="form-control" autocomplete="off" id="loc_name" name="loc_name"   value="{{$bookingData['loc_name']}}" readonly >
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="status">Center</label>
                        <input type="text" class="form-control" autocomplete="off" id="centre_name" name="centre_name"   value="{{$bookingData['centre_name']}}" readonly >
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <label for="status">Name</label>
                        <input type="text" class="form-control" autocomplete="off" id="user_name" name="user_name"   value="{{$bookingData['user_name']}}" readonly >
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="status">Email ID</label>
                        <input type="text" class="form-control" autocomplete="off" id="user_email" name="user_email"   value="{{$bookingData['user_email']}}" readonly >
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="status">Phone No.</label>
                        <input type="text" class="form-control" autocomplete="off" id="user_phone" name="user_phone"   value="{{$bookingData['user_phone']}}" readonly >
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <label for="status">Date From</label>
                        <input type="text" class="form-control" autocomplete="off" id="book_date_from" name="book_date_from"   value="{{$bookingData['book_date_from']}}" readonly >
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="status">Date To</label>
                        <input type="text" class="form-control" autocomplete="off" id="book_date_to" name="book_date_to"   value="{{$bookingData['book_date_to']}}" readonly >
                    </div>
                </div>
                <?php
                if (isset($bookingData['book_time_from']) && $bookingData['book_time_from'] != '') {
                    $time_from = $bookingData['book_time_from'];
                } else {
                    $time_from = '12:00';
                }
                ?>	

                <?php
                if (trim($bookingData['service_type']) == 'Virtual Office') {
                    $time_to = '23:59';
                } else {
                    $time_to = $bookingData['book_time_to'];
                }
                ?>
                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <label for="status">Time In</label>
                        <input type="text" class="form-control" autocomplete="off" id="book_time_from" name="book_time_from"   value="{{$time_from}}" readonly >
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="status">Time Out</label>
                        <input type="text" class="form-control" autocomplete="off" id="book_time_to" name="book_time_to"   value="{{$time_to}}" readonly >
                    </div>
                </div>

                <input type="hidden" id="centre_id" value="{{$bookingData['centre_id']}}" >
                <div class="row mt-4">

                    <div class="col-md-3 mb-3">
                        <label for="payment_option">Booking Status</label>
                        @if ($errors->has('book_status'))
                        <span class="help-block">
                            <strong>{{ $errors->first('book_status') }}</strong>
                        </span>
                        @endif
<?php if ($bookingData['book_status'] == 5) { ?>
                            : Closed

<?php } else if ($bookingData['book_status'] == 4) { ?>
                            : Cancelled

<?php } else { ?>
                            <select  class="form-control select"   id="book_status" name="book_status">
                                <option value="" >Select Status</option>
                                <option value="2" <?php if ($bookingData['book_status'] == 2) echo "selected"; ?>>Paid</option>
                                <option value="3" <?php if ($bookingData['book_status'] == 3) echo "selected"; ?> >Pay at Centre</option>
                                <option value="7" <?php if ($bookingData['book_status'] == 7) echo "selected"; ?> >Bill to Company</option>
                                <option value="8" <?php if ($bookingData['book_status'] == 8) echo "selected"; ?> >Bill to Company - Paid</option>
                                <option value="4" <?php if ($bookingData['book_status'] == 4) echo "selected"; ?> >Cancelled</option>
                            </select>
<?php } ?>
                    </div>
                    <div class="col-md-3 mb-3" style="display: none;" id="status_remarks_div">
                        <label for="payment_option">Status Remarks</label>
                        <input type="text" class="form-control" autocomplete="off" id="status_remarks" name="status_remarks"   value="" >

                    </div>
<?php if ($bookingData['book_status'] == 2) { ?>
                        <div class="col-md-3 mb-3">
                            <label for="payment_option">Add on</label>
                            <select  class="form-control select" id="book_add_on" name="book_add_on">
                                <option value="" >Select Ad on</option>
                                @foreach($catData as $key=>$value)
    <?php
    if (!in_array($value->acat_id, $catIdArr)) {
        ?>
                                    <option value="{{$value->acat_id}}" >{{$value->acat_name}}</option>

    <?php } ?>
                                @endforeach
                            </select>
                        </div>

                        <div id="add_on_div" style="display: none;">
                            <input type="hidden" name="centre_id" value="{{$bookingData['centre_id']}}" >
                            <div class="col mb-3 align-self-center mt-4">
                                <div class="custom-control custom-checkbox">

                                    <input type="checkbox" name="flag_hour" class="custom-control-input all_flag_book" id="flag_hour" >
                                    <label class="custom-control-label" for="flag_hour">By Hour</label>
                                </div>
                                <select class="hour_check" name="book_hour">
    <?php for ($i = 1; $i <= 20; $i++) { ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col mb-3 align-self-center mt-4">
                                <div class="custom-control custom-checkbox">

                                    <input type="checkbox" name="flag_half" class="custom-control-input all_flag_book" id="flag_half" >
                                    <label class="custom-control-label" for="flag_half">Half day</label>
                                </div>
                            </div>
                            <div class="col mb-3 align-self-center mt-4">
                                <div class="custom-control custom-checkbox">

                                    <input type="checkbox" name="flag_full" class="custom-control-input all_flag_book" id="flag_full" >
                                    <label class="custom-control-label" for="flag_full">Full day</label>
                                </div>
                            </div>
                        </div>
<?php } ?>
                </div>

        </div>

        <span class="pt-5 d-block"><strong>Booking Details</strong></span><hr>
        <div class="row mt-4" id="amt_opt" >
            <div class="col">
                <table id="datatable1" class="table table-striped ">
                    <thead>
                        <tr class="headings">
                            <th class="column-title">Service Name</th>
                            @if($bdetails[0]['service_name'] == 'Virtual Office')
                            <th class="column-title">Package Name</th>
                            <th class="column-title">Plan</th>
                            @endif
                            <th class="column-title">Start Date</th>
                            <th class="column-title">End Date</th>
<!--                            <th class="column-title">Time From</th>
                            <th class="column-title">Time To</th>-->
                            @if($bdetails[0]['service_name'] != 'Virtual Office')
                            <th class="column-title">Hours</th>
                            @endif
                            <th class="column-title">Total</th>
                            <!--<th class="column-title">Action</th>-->
                        </tr>
                    </thead>
                    <tbody>
<?php
$service_tot_amnt = 0;
$view_package = 0;
//dd($bdetails); 
?>
                        @foreach($bdetails as $key=>$value)
                        <?php
                        if ($value->service_name == 'Virtual Office') {
                            $view_package = 1;
                        }
                        ?>
                    <input type="hidden" name="ser_name" id="ser_name" value="{{$value->service_name}}">
                    <?php $service_tot_amnt = $service_tot_amnt + (float) ($value->service_tot_amnt); ?>
                    <tr class="pointer">
                        <td class=" ">{{$value->service_name}}</td>
                        @if($value->service_name == 'Virtual Office')
                        <td class=" ">{{getPackageName($value->service_id)}}</td>
                        <td class=" ">{{getPlanName($bookingData['ser_config'])}}</td>
                        @endif
                        <td class=" ">{{$value->book_date_from}}</td>
                        <td class=" ">{{$value->book_date_to}}</td>
<!--                            <td class=" ">{{$value->book_time_from}}</td>
                        <td class=" ">{{$value->book_time_to}}</td>-->
                        @if($value->service_name != 'Virtual Office')
                        <td class=" ">{{$value->book_hrs}}</td>
                        @endif
                        <td class=" ">INR {{number_format((float)$value->service_tot_amnt,2)}}</td>
                    </tr>
                    @endforeach
                    <?php
                    $tax_val = 0;
                    $tot_after_tax = 0;
                    if ($tax > 0) {
                        $tax_amnt = (float) ($tax / 100);
                        $tax_val = round($service_tot_amnt * $tax_amnt, 2);
                        $only_txt_amnt = abs($tax_val - $service_tot_amnt);
                    }
                    $tot_after_tax = $tax_val + $service_tot_amnt;
                    ?>
                    <tr>
                        <td colspan="6" class="text-right pr-6"> <strong class="">Tax </strong>:  {{number_format($tax_val,2)}} INR</td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-right pr-6 "> <strong>Total</strong>:{{number_format($tot_after_tax,2)}} INR</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if (($bookingData['book_status'] != 4)) { ?>
            <span><strong>Payment Details</strong></span><hr>

            <div class="row mt-4" id="amt_opt" >
                <?php /* if($view_package == 1) { ?>
                  <div class="col-md-3 mb-3">
                  <label for="package_option">Select Package</label>
                  @if ($errors->has('package_option'))
                  <span class="help-block">
                  <strong>{{ $errors->first('package_option') }}</strong>
                  </span>
                  @endif
                  <select  class="form-control select"   id="package_option" name="package_option">
                  <option value="" >Select Package</option>
                  <option value="Daily" >Daily</option>
                  <option value="Monthly" >Monthly</option>
                  <option value="Quaterly" >Quaterly</option>
                  <option value="Yearly" >Yearly</option>
                  <option value="Other" >Other</option>
                  </select>
                  </div>
                  <?php } */ ?>

                <div class="col-md-3 mb-3">
                    <label for="booking_amount"> Amount</label>
                    <input type="text" class="form-control onlyDec" autocomplete="off" id="book_user_amnt" name="book_user_amnt"   >
                </div>
                <div class="col-md-3 mb-3">
                    <label for="payment_option">Payment Option</label>
                    @if ($errors->has('payment_option'))
                    <span class="help-block">
                        <strong>{{ $errors->first('payment_option') }}</strong>
                    </span>
                    @endif
                    <select  class="form-control select"   id="payment_option" name="payment_option">
                        <option value="" >Select Type</option>
                        @foreach($payment_opt as $value)
                        <option value="{{$value}}" >{{$value}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label for="admin_cmnt"> Remark</label>
                    @if ($errors->has('admin_cmnt'))
                    <span class="help-block">
                        <strong>{{ $errors->first('admin_cmnt') }}</strong>
                    </span>
                    @endif
                    <input type="text" class="form-control " autocomplete="off" id="admin_cmnt" name="admin_cmnt"   value="" >
                </div>
            </div> 

        <?php } ?>
        @if(count($commentData) >0 )
        <span><strong>Payment History</strong></span><hr>
        <table id="datatable1" class="table table-striped ">
            <thead>
                <tr class="headings">
                    <th class="column-title">Amount</th>
                    <th class="column-title">Payment Type</th>
                    <th class="column-title">Comment</th>
                    <th class="column-title">Comment Date</th>

                </tr>
            </thead>
            <tbody>
                @foreach($commentData as $key=>$value)
                <tr class="pointer">
                    <td>
                        @if($value->payment !='')
                        {{number_format($value->payment_amnt,2)}}
                        @else
                        0
                        @endif
                    </td>
                    <td>{{$value->payment_type}}</td>
                    <td>{{$value->admin_cmnt}}</td>
                    <td>{{$value->created_at}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif


        <?php if (($bookingData['book_status'] != 4)) { ?>
            <div class="row mt-4">

                <div class="col-6  pb-3 clearfix">
                    <button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2   text-uppercase " id="submitBooking">Submit</button>


                    <!--                <button type="button" class="btn btn-outline-secondary pl-4 pr-4 pt-2 pb-2 ml-2 text-uppercase reset-btn" >Reset</button></div>-->

                </div>


            </div>
        <?php } ?>
        </form>
    </div>
</div>

@endsection
