@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Client</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
                    <form method="get">


                        <div class="row mt-4">
                            <div class="col-md-12 mb-3 padRig">
                                <input type="text" placeholder="Search by Name ,Email and Mobile" name="search"  autocomplete="off" class="form-control" value="{{ app('request')->input('search') }}">
                            </div>
                            <!--                            <div class="col-md-6 mb-3 padRig">
                                                            <button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2  m-0 text-uppercase">Search</button>
                                                        </div>-->
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
                                    <th class="column-title">Company Name</th>
                                    <th class="column-title">Contact Person</th>
                                    <th class="column-title">Email</th>
                                    <th class="column-title">Mobile</th>
                                    <th class="column-title">Status</th>
                                    <th class="column-title">Action</th>
                                </tr>
                            </thead>
                            <tbody>


                                @foreach($data as $key=>$value)
                                <tr class="pointer">
                                    <td>{{getCompanyName($value->cust_comp)}}</td>
                                    <td>{{$value->cust_nme}}</td>
                                    <td>{{$value->cust_email}}</td>
                                    <td>+91 {{$value->cust_mobile}}</td>
                                    <td>@if($value->comp_status ==1) Pending @elseif($value->comp_status ==2) Approved @else Disabled @endif</td>
                                    <td class="last">

                                        <a href="javascript:void(0);" title="Edit" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase editCompany" data-id="{{base64_encode($value->cust_id)}}">View</a> 
                                        <a href="{{route('company.delete',base64_encode($value->cust_id))}}"  title="Delete" class="d-inline-block delete confirmation"><img src="{{ URL::asset('images/delete.svg') }}" alt=""></a>
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

            <div class="clearfix"></div>
            <div class="row  m-0">
                <div class="col-12 text-right pl-0 pr-0 pb-3 clearfix"><a href="javascript:void(0);" onclick="scrollToCustomerForm();" class="btn btn-outline-secondary  pl-4 pr-4 pt-2 pb-2  m-0" id="addNewRequest"> Add New Client </a></div>
            </div>

        </div>
    </div>
</div>


<div class="row pt-3">
    <div class="col-lg-12 col-md-12 col-12">
        <div class="x_panel newRequestForm">
            <div class="x_title">
                <h2 id="titleText">Add New Client</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="compRegister" action=" {{route('company.register')}}"> 

                {{ csrf_field() }}
                <input type="hidden" name="id" id="cust_id">
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
                    <div class="col-md-3 mb-3 padRig">
                        <label for="requestName">Select Company</label>
                        <select  class="form-control select" id="cust_comp" name="cust_comp" required="required">
                            <option value="">Select</option>
                            @foreach($compData as $key=>$value)
                            <option value="{{$value['cc_id']}}">{{$value['cc_name']}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-3 padRig">
                        <label for="requestEmailID">Contact Person</label>
                        <input type="text" class="form-control" autocomplete="off" id="cust_nme" name="cust_nme" placeholder="Contact person" value="" >
                    </div>
                    <div class="col-md-3 mb-3 padRig">
                        <label for="requestEmailID">Email ID</label>
                        <input type="text" class="form-control" autocomplete="off" id="cust_email" name="cust_email" placeholder="Company Email Id" value="" >
                    </div>
                    
                    <div class="col-md-3 mb-3 padRig">
                        <label for="comp_desig"> Designation </label>
                        <input type="text" class="form-control" id="cust_desig" autocomplete="off" name="cust_desig" placeholder="Designation " value="" >
                    </div>
                    
                </div>
                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <label for="requestMobile">Mobile</label>
                        <input type="tel" class="form-control" maxlength="10" onkeyup="if (/\D/g.test(this.value))
                                    this.value = this.value.replace(/\D/g, '')" id="cust_mobile" autocomplete="off" name="cust_mobile" placeholder="Mobile No." value="" >
                    </div>

                    <div class="col-md-4 mb-3 padRig">
                        <label for="requestLocation">Location</label>
                        <select  class="form-control select" id="custloc" name="custloc" required="required">
                            <option value="">Select</option>
                            @foreach($locationData as $key=>$value)
                            <option value="{{$value['loc_id']}}">{{$value['loc_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-3 padRig">
                        <label for="requestAddress"> Address </label>
                        <input type="text" class="form-control" id="cust_service_add1" autocomplete="off" name="cust_service_add1" placeholder="Address " value="" >
                    </div>

                </div>

                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <label for="status">Centre</label>
                        @if ($errors->has('center_id'))
                        <span class="help-block">
                            <strong>{{ $errors->first('center_id') }}</strong>
                        </span>
                        @endif
                        <select  class="form-control select config-val"   id="cust_centre" name="cust_centre">
                            <option value="" >Select Centre</option>
							<option value="0" >All Centres</option>
                            @foreach($centreData as $key=>$value)
<?php $locnm = getLocationName($value->location); ?>
                            <option value="{{$value->centre_id}}" >{{$value->centre}} (<?php echo $locnm->loc_name; ?>)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3 padRig">
                        <label for="requestAddress"> GST </label>
                        <input type="text" class="form-control" id="comp_gst" autocomplete="off" name="comp_gst" placeholder="GST Number " value="" >
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="status">Status</label>
                        <select  class="form-control select " id="comp_status" name="comp_status">
                            <option value="2">Approved</option>
                            <option value="1">Pending</option>
                            <option value="3">Disable</option>
                        </select>
                    </div>
                </div>
				
				<div class="row mt-4">
                    
                    <div class="col-md-4 mb-3">
                        <label for="status">Bill To Company</label>
                        <select  class="form-control select " id="blc" name="blc">
                            <option value="0">Disable</option>
							<option value="1">Enable</option>
                        </select>
                    </div>
                </div>
				
				
                <div class="row mt-4">
                    <div class="col-6  pb-3 clearfix">
                        <button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2   text-uppercase " id="submitCust">Submit</button> 
                        <button type="button" class="btn btn-outline-secondary pl-4 pr-4 pt-2 pb-2 ml-2 text-uppercase btn-reset" id="submitCategory">Reset</button></div>


                </div>

            </form>
        </div>
    </div>

    @endsection