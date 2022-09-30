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
                                <input type="text" placeholder="Search by Service name" name="search"  autocomplete="off" class="form-control" value="{{ app('request')->input('search') }}">
                            </div>
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

                            {{ csrf_field() }}
                            <div class="col-md-4 mb-3 padRig">
                                <button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2  m-0 text-uppercase">Search</button>
                            </div>
                        </div>

                    </form>
                     <?php if (count($data) > 0) { ?>
                    <table id="datatable1" class="table table-striped ">
                        <thead>
                            <tr class="headings">
                                <th class="column-title">Service Name</th>
                                <th class="column-title">Date From</th>
                                <th class="column-title">Date To</th>
                                <th class="column-title">Time From</th>
                                <th class="column-title">Time To</th>
                                <th class="column-title">Amount</th>
                                <th class="column-title">Tax</th>
                                <th class="column-title">Total</th>
                                <!--<th class="column-title">Action</th>-->
                            </tr>
                        </thead>
                        <tbody>


                            @foreach($data as $key=>$value)
                            <tr class="pointer">
                                <td class=" ">{{$value->service_name}}</td>
                                <td class=" ">{{$value->book_date_from}}</td>
                                <td class=" ">{{$value->book_date_to}}</td>
                                <td class=" ">{{$value->book_time_from}}</td>
                                <td class=" ">{{$value->book_time_to}}</td>
                                <td class=" ">{{$value->service_amnt}}</td>
                                <td class=" ">{{$value->service_tax_amnt}}</td>
                                <td class=" ">{{$value->service_tot_amnt}}</td>
<!--                                <td class="last">
                                    <a href="{{route('bookingdetails.data',base64_encode($value->booking_details_id))}}" title="Edit" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase" data-id="{{base64_encode($value->booking_details_id)}}">View</a> 
                                </td>-->
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



@endsection