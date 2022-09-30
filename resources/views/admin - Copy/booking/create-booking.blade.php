@extends('admin.layouts.app')

@section('content')
<div class="row pt-3">
    <div class="col-lg-12 col-md-12 col-12">
        <div class="x_panel newSerRequestForm">

            <div class="x_title">
                <h2 id="titleText" >Create Booking Request {{$ms_name}}</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="serviceReqRegister" action="" enctype="multipart/form-data"> 

                {{ csrf_field() }}
                <input type="hidden" name="ms_cat" id="ms_cat" value="{{$ms_cat}}">
                <input type="hidden" name="centre_id" id="centre_id" value="{{Auth::guard('admin')->user()->center_id}}">
                <input type="hidden" name="loc_id" id="loc_id" value="{{getCentreLocation(Auth::guard('admin')->user()->center_id)}}">
                <input type="hidden" name="addonselect" id="addonselect" >
                <input type="hidden" name="addonhrselect" id="addonhrselect" >
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

                <div class="row mt-4 d-flex">
                    <div class="col-md-8 mb-3">
                        <label for="selectLocation ">Search Customer</label>
                        <input type="text" class="form-control "  autocomplete="off"  id="customerser" name="customerser" placeholder="Search By mobile,name,email" value="" required="">
                        <div id="custList"></div>

                    </div>
                    <!--                    <div class="col-md-4 mb-3 align-self-start mt-4 pt-2">
                    
                                            <input type="button" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2  m-0 text-uppercase "  autocomplete="off"  id="addcust" name="addcust"  value="Add Customer" >
                                        </div>-->
                </div>
                <div class="custDetail">
                    <div class="row mt-4 ">
                        <input type="hidden" id="cust_id" name="cust_id">
                        <div class="col-md-4 mb-3 padRig">
                            <label for="requestName">Name</label>
                            @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                            <input type="text" class="form-control"  autocomplete="off" id="requestName" name="cust_name" placeholder="Customer Name" readonly >
                        </div>
                        <div class="col-md-4 mb-3 padRig">
                            <label for="requestEmailID">Email ID</label>
                            @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                            <input type="text" class="form-control" autocomplete="off" id="requestEmailID" name="email" placeholder="Email ID" value="" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="requestMobile">Mobile</label>
                            @if ($errors->has('mobile'))
                            <span class="help-block">
                                <strong>{{ $errors->first('mobile') }}</strong>
                            </span>
                            @endif
                            <input type="tel" class="form-control" autocomplete="off" maxlength="10" onkeyup="if (/\D/g.test(this.value))
                                        this.value = this.value.replace(/\D/g, '')" id="requestMobile" name="mobile" placeholder="Mobile" value="" readonly>
                        </div>
                    </div>

                    <!--                    <div class="row mt-4 regcustbutton">
                                            <div class="col-12  pb-3 clearfix">
                    
                                                <button type="button" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2  m-0 text-uppercase" id="registerCust">Register Customer</button></div>
                    
                                        </div>-->
                </div>
                @if($ms_cat == 4)
                <div class="row mt-4">

                    <div class="col-md-12 mb-3 padRig">                      

                        <label for="pageName">Select Configuration</label>
                        <select class="form-control select " id="ser_config" name="ser_config"> 
                            <?php echo html_entity_decode($configHtml); ?>
                        </select>

                    </div>
                    <div class="col-md-12 mb-3 padRig">
                        <label for="pageName">Duration</label>
                        @if ($errors->has('e_from'))
                        <span class="help-block">
                            <strong>{{ $errors->first('e_from') }}</strong>
                        </span>
                        @endif
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
                        <div id="reportrange1" class="form-control"> <i class="glyphicon glyphicon-calendar fa fa-calendar"></i> <span id="event_daterange">{{date("M j, Y",strtotime($first_day))}} - {{date("M j, Y",strtotime($last_day))}}</span> <b class="caret"></b> </div>
                        <input type="hidden" name="event_date_range" id="req_date_range" value="{{app('request')->input('req_date_range')}}">

                        <input type="hidden" name="book_date_range" id="book_date_range" value="{{app('request')->input('req_date_range')}}">
                        </select>
                    </div>
                </div>
                @else

                <div class="row mt-4">
                    <div class="col-md-8 mb-3 padRig">                      

                        <label for="pageName">Select Plan</label>
                        <select class="form-control select " id="ser_config" name="ser_config"> 
                            <?php echo html_entity_decode($configHtml); ?>
                        </select>

                    </div>
                    <div class="col-md-4 mb-3 padRig">
                        <label for="requestEmailID">Booking Preferred Date</label>
                        <input type="text" class="form-control date-picker" autocomplete="off" id="book_date_range" name="book_date_range" placeholder="Booking date" value="" required="">
                    </div>

                </div>
                @endif
                
                
                <div class="row mt-4 months-duration" style="display: none;">
                    <div class="col-md-8 mb-3 padRig">                      

                        <label for="pageName">Select Month</label>
                        <select class="form-control select " id="months-duration" name="months-duration"> 
                            <?php echo html_entity_decode($durhtml); ?>
                        </select>

                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-4 mb-3 padRig">
                        <label for="requestName" >Time From</label>
                        <select id="time-from" class="form-control select">
                            <option> Select time from </option>
                        </select>
                    </div>
                    @if($ms_cat == 4)
                    <div class="col-md-4 mb-3 padRig">
                        <label for="requestName">Time To</label>
                        <select id="time-to" class="form-control select">
                            <option> Select time to </option>
                        </select>
                    </div>
                    @endif
                </div>

                @if($addonhtml !='' && ($ms_cat == 3 || $ms_cat == 4 || $ms_cat == 1))
                <div class="row mt-4">
                    <div class="col-md-4 mb-3 padRig">
                        <label for="requestName">Select Ad on</label>
                        <select id="addons-select" class="form-control select" >
                            <option>Select Ad on</option>
                            <?php echo html_entity_decode($addonhtml); ?>
                        </select>

                    </div>
                </div>

                <div class="row mt-4 addoncheck" style="display: none;">
                    <div class="col-md-4 mb-3 padRig">
                        <label for="requestName">Time interval</label>
                        <select id="addons-time-interval-select" class="form-control select" >
                            <option>Time interval</option>
                            <option value="by_hr" selected="">By hour</option>
                            <option value="by_hday">Half Day</option>
                            <option value="by_fday">Full Day</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3 padRig by-hr-val-select">
                        <label for="requestName">Time interval</label>
                        <select id="by-hr-val-select" class="form-control select" >
                            <option selected="">By hour</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </div>
                </div>
                @endif



                <!--<div class="availablemsg" style="color: red;"></div>-->
                <div class="availablemsg">
                    
                </div>

                <div class="row mt-4">

                    <div class="col-12  pb-3 clearfix"><button type="button" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2  m-0 text-uppercase" id="submitBooking" onclick="validateBookingAvailability()">Check Booking Availability</button></div>

                </div>

            </form>
        </div>
    </div>
</div>
@endsection