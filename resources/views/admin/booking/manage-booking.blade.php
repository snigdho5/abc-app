@extends('admin.layouts.app')

@section('content')

<?php
$co_type = array('3' => 'Monthly', '6' => 'Night Watch (Monthly)', '7' => 'Flexi Seats (Monthly)');
$payment_opt = array('Credit/Debit Card Payment', 'Net Banking', 'Cheque Payment', 'Demand Draft', 'Cash Payment');
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Bookings</h2>
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
											if($value->service_type == 'Virtual Office'){
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
                <div class="clearfix"></div>

                <?php if (count($data) > 0) { ?>
                    <div class="row  m-0">
                        <div class="col-6"><span>Total  {{ $data->total() }} records</span></div>
                <?php $routeValues = 'search=' . app('request')->input('search').'&book_status='.app('request')->input('book_status').'&req_date_range='.app('request')->input('req_date_range'); ?>
                        <div class="col-12 text-right pl-0 pr-0 pb-3 clearfix"><a href="{{route('booking.export',$routeValues)}}" class="btn btn-outline-secondary pl-4 pr-4 pt-2 pb-2  m-0" id="exporttoExcel"> <img src="{{ URL::asset('images/excel.svg') }}" class="pr-2" alt="Export to Excel"> Export to CSV </a></div>

                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>



@endsection
