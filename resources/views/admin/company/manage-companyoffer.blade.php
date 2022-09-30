@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Client Offer</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
                    
                        <table id="datatable" class="table table-striped ">
                            <thead>
                                <tr class="headings">
                                    <th class="column-title">Company Name</th>
                                    <th class="column-title">Service offered</th>
                                    <!--<th class="column-title">Configuration</th>-->
                                    <th class="column-title">Allocated Hrs</th>
                                    <th class="column-title">Start Date</th>
                                    <th class="column-title">End Date</th>
                                    <th class="column-title">Status</th>
                                    <th class="column-title">Action</th>
                                </tr>
                            </thead>
                            <tbody>


                                @foreach($data as $key=>$value)
                                <tr class="pointer">
                                    <td>{{getCompanyName($value->co_compid)}}</td>
                                    <td>{{getCatNameById($value->co_catid)}}</td>
                                    <!--<td>{{getConfigNameById($value->co_configid)}}</td>-->
                                    <td>{{$value->co_allctedhrs}}</td>
                                    <td>{{$value->co_cntrctstrtdte}}</td>
                                    <td>{{$value->co_cntrctenddte}}</td>
                                    <td>@if($value->co_status ==1) Enabled  @else Disabled @endif</td>
                                    <td class="last">

                                        <a href="javascript:void(0);" title="Edit" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase editCompanyOffer" data-id="{{base64_encode($value->co_id)}}">View</a> 
                                        <a href="{{route('companyoffer.delete',base64_encode($value->co_id))}}"  title="Delete" class="d-inline-block delete confirmation"><img src="{{ URL::asset('images/delete.svg') }}" alt=""></a>
                                    </td>

                                </tr>
                                @endforeach

                            </tbody>
                        </table>

                </div>

            </div>

            <div class="clearfix"></div>
            <div class="row  m-0">
                <div class="col-12 text-right pl-0 pr-0 pb-3 clearfix"><a href="javascript:void(0);" onclick="scrollToCustomerForm();" class="btn btn-outline-secondary  pl-4 pr-4 pt-2 pb-2  m-0" id="addNewRequest"> Add New Client Offer </a></div>
            </div>

        </div>
    </div>
</div>


<div class="row pt-3">
    <div class="col-lg-12 col-md-12 col-12">
        <div class="x_panel newRequestForm">
            <div class="x_title">
                <h2 id="titleText">Add New Client Offer</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="compofferRegister" action=" {{route('companyoffer.register')}}"> 

                {{ csrf_field() }}
                <input type="hidden" name="id" id="co_id">
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
                    <div class="col-md-4 mb-3 padRig">
                        <label for="requestLocation">Company</label>
                        <select  class="form-control select" id="co_compid" name="co_compid" required="required">
                            <option value="">Select</option>
                            @foreach($custdata as $key=>$value)
                            <option value="{{$value['cc_id']}}">{{$value['cc_name']}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3 padRig">
                        <label for="requestLocation">Service Offered</label>
                        <!--<select  class="form-control select" id="co_catid" name="co_catid" required="required" onchange="populateServiceConfig(0,0)">-->
                            <select  class="form-control select" id="co_catid" name="co_catid" required="required">
							<option value="">Select</option>
                            @foreach($catData as $key=>$value)
                            <option value="{{$value['acat_id']}}">{{$value['acat_name']}}</option>
                            @endforeach
                        </select>
                    </div>
					 <div class="col-md-4 mb-3">
                        <label for="requestMobile">Allocated Hours</label>
                        <input type="text" class="form-control" maxlength="10" onkeyup="if (/\D/g.test(this.value))
                                    this.value = this.value.replace(/\D/g, '')" id="co_allctedhrs" autocomplete="off" name="co_allctedhrs" placeholder="" value="" >
                    </div>
                    <!--<div class="col-md-4 mb-3 padRig">
                        <label for="requestLocation">Configuration</label>
                        <select  class="form-control select" id="co_configid" name="co_configid" required="required">
                            <option value="">Select Service Offered First</option>
                        </select>
                    </div>-->
                    
                </div>
                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <label for="requestMobile">Monthly Hours</label>
                        <input type="text" class="form-control" maxlength="10" onkeyup="if (/\D/g.test(this.value))
                                    this.value = this.value.replace(/\D/g, '')" id="co_allctedmnthhrs" autocomplete="off" name="co_allctedmnthhrs" placeholder="" value="" >
                    </div>

                    
                    <div class="col-md-8 mb-3 padRig">
                         <label for="requestMobile">Contract Date Range</label>
                                <?php
                                $dates = '';

                                if (!empty(app('request')->input('req_date_range'))) {
                                    $dates = explode('and', app('request')->input('req_date_range'));
                                }

                                if (!empty($dates)) {
                                    $first_day = date('m-01-Y', strtotime($dates[0])); // hard-coded '01' for first day
                                    $last_day = date('m-t-Y', strtotime($dates[1]));
                                } else {
                                    $first_day = date('m-01-Y'); // hard-coded '01' for first day
                                    $last_day = date('m-t-Y');
                                }
                                ?>
                                <div id="reportrange2" class="form-control"> <i class="glyphicon glyphicon-calendar fa fa-calendar"></i> <span>{{date("M j, Y",strtotime($first_day))}} - {{date("M j, Y",strtotime($last_day))}}</span> <b class="caret"></b> </div>
                                <input type="hidden" name="con_date_range" id="ofr_date_range" value="{{app('request')->input('req_date_range')}}">
                            </div>

                </div>
				
				
				<div class="row mt-4">
                     <div class="col-md-4 mb-3 padRig">
                        <label for="requestLocation">Days</label>
                        <select  class="form-control" id="co_offerdays" name="co_offerdays[]" required="required" multiple>
                            <option value="0"> Sunday</option>
							<option value="1"> Monday</option>
							<option value="2"> Tuesday</option>
							<option value="3"> Wednesday</option>
							<option value="4"> Thrusday</option>
							<option value="5"> Friday</option>
							<option value="6"> Saturday</option>
                        </select>
                    </div>
					<?php $hrs_range = get_hours_range();?>
					 <div class="col-md-4 mb-3 padRig">
                        <label for="requestLocation">Time From</label>
                        <select  class="form-control select" id="co_ofrtimefrom" name="co_ofrtimefrom" required="required">
                           @foreach($hrs_range as $key =>$value)
							<option value="{{$key}}"> {{$value}}</option>
						   @endforeach
                        </select>
                    </div>
					
					
					<div class="col-md-4 mb-3 padRig">
                        <label for="requestLocation">Time To </label>
                        <select  class="form-control select" id="co_ofrtimeto" name="co_ofrtimeto" required="required" >
                             @foreach($hrs_range as $key =>$value)
							<option value="{{$key}}"> {{$value}}</option>
						   @endforeach
                        </select>
                    </div>
					
					
                </div>

                <div class="row mt-4">
                   
                    <div class="col-md-4 mb-3">
                        <label for="status">Status</label>
                        <select  class="form-control select " id="co_status" name="co_status">
                            <option value="1">Enable</option>
                            <option value="2">Disable</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-6  pb-3 clearfix">
                        <button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2   text-uppercase " id="submitCust">Submit</button> 
                        <button type="button" class="btn btn-outline-secondary pl-4 pr-4 pt-2 pb-2 ml-2 text-uppercase reset-btn" id="submitCategory">Reset</button></div>


                </div>

            </form>
        </div>
    </div>

    @endsection