@extends('admin.layouts.app')

@section('content')


<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel m-0">
            <div class="row"> 
                <div class="col-lg-6 col-md-6 col-12">	
                    <h2>Manage Service </h2>
                </div>
                <div class="col-lg-6 col-md-6 col-12 ml-auto text-right">	
                    <h2 >Welcome <?php echo Auth::guard('admin')->user()->user_name; ?></h2>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<div class="row pt-3">

    <div class="col-lg-12 col-md-12 col-12">
        <form method="post" id="centreRegister" action="{{route('meetingroom.editmanager')}} " enctype="multipart/form-data" > 
            <div class="x_panel newRequestForm">
                <div class="x_title">
                    <h2 id="titleText">Center Info</h2>
                    <div class="clearfix"></div>
                </div>

                {{ csrf_field() }}
                <input type="hidden" name="id" id="centre_id" value="{{$cid}}">
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
                        <label for="status">Location</label>
                        <h6>{{$locationData->loc_name}}</h6>

                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="pageName">Centre</label>
                        <h6>{{$cinfoData->centre}}</h6>

                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="pageName">Address</label>
                        <h6>{{$cinfoData->centre_address}}</h6>
                    </div>

                </div>

                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <label for="pageName">Email</label>
                        <h6>{{$cinfoData->centre_email}}</h6>

                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="pageName">Mobile</label>
                        <h6>{{$cinfoData->centre_mobile}}</h6>

                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="pageName">Phone</label>
                        <h6>{{$cinfoData->centre_phone}}</h6>  
                    </div>

                </div>

            </div>
            <div class="x_panel newRequestForm">
                <div class="x_title">
                    <h2 id="titleText">Your Details</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <label for="status">Name</label>
                        <h6><?php echo Auth::guard('admin')->user()->user_name; ?></h6> 

                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="pageName">Email</label>
                        <h6><?php echo Auth::guard('admin')->user()->email; ?></h6> 

                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="pageName">Phone</label>
                        <h6><?php echo Auth::guard('admin')->user()->mobile; ?></h6> 
                    </div>

                </div>
            </div>


            <div class="x_panel newRequestForm">
                <div class="x_title">
                    <h2 id="titleText">Additional Email/Phone for Receive Communication</h2>
                    <div class="clearfix"></div>
                </div>

                <div class="row mt-4">

                    <div class="col-md-1 mb-3">
                        <label for="pageName">S.No</label>

                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="status">Name</label>

                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="pageName">Email</label>

                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="pageName">Phone</label>

                    </div>

                </div>
                @foreach($emaildata as $key=>$value1)
                <div class="row mt-4">

                    <div class="col-md-1 mb-3">

                        <span class="help-block d-block pt-2 ">
                            <strong >{{$key+1}}</strong>
                        </span>
                    </div>

                    <div class="col-md-3 mb-3">

                        <input type="text" class="form-control config-val" autocomplete="off" id="user_name" name="user_name" placeholder="User name" value="{{$value1->em_per}}" readonly >
                    </div>
                    <div class="col-md-4 mb-3">


                        <input type="text" class="form-control config-val" autocomplete="off" id="email" name="email" placeholder="Email" value="{{$value1->em_email}}" readonly >
                    </div>
                    <div class="col-md-3 mb-3">


                        <input type="text" class="form-control config-val" autocomplete="off" id="mobile" name="mobile" placeholder="Mobile" value="{{$value1->em_phone}}" readonly >
                    </div>
                    <div class="col-md-1  help-block d-block pt-2 align-self-center ">
                        <a href="{{route('emailmatrix.delete',base64_encode($value1->em_id))}}"  title="Delete" class="d-inline-block delete confirmation"><img src="{{ URL::asset('images/delete.svg') }}" alt=""></a>


                    </div>
                </div>
                @endforeach
            </div>


            <div class="x_panel newRequestForm">

                <div class="row mt-4">

                    <div class="row  m-0 pb-3">
                        <div class="col-md-6 text-right  pr-0 pb-3 clearfix"><a href="{{route('emailmatrix.manage')}}" class="btn btn-outline-secondary  pl-4 pr-4 pt-2 pb-2  m-0" id="addNewRequest"> Add/Edit Additional Info</a></div>
                        <div class="col-md-6 text-right  pr-0 pb-3 clearfix"><a href="{{route('servicecategory.manage')}}" class="btn btn-outline-secondary  pl-4 pr-4 pt-2 pb-2  m-0" id="addNewRequest"> Manage Service Categories</a></div>
                    </div>    
                </div>
            </div>		     

            <div class="x_panel newRequestForm">
                <div class="row mt-4 configdiv">
                    <div class="col-md-12 mb-3">
                        <div class="accordion" id="accordionExample">
                            <?php $i = 0; ?>
                            @foreach($minfoData as $key=>$value)
                            <?php
                            $i++;
                            $cls = '';
                            if (isset($cat_changed) && $cat_changed != '') {
                                if ($value->acat_id == $cat_changed) {
                                    $cls = 'show';
                                }
                            } else {
                                if ($i == 1) {
                                    $cls = 'show';
                                }
                            }
                            if ($value->acat_id == $cinfoData->flag_abc_lounge || $value->acat_id == $cinfoData->flag_built_to_suit || $value->acat_id == $cinfoData->flag_virtual_office || $value->acat_id == $cinfoData->flag_ser_office || $value->acat_id == $cinfoData->flag_co_working || $value->acat_id == $cinfoData->flag_meeting_room || $value->acat_id == 7 || $value->acat_id == 8) {
                                ?>
                                <div class="card">
                                    <div class="card-header" id="headingOne">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne_{{$value->acat_id}}" aria-expanded="true" aria-controls="collapseOne_{{$value->acat_id}}">
                                                {{$value->acat_name}}
                                            </button>
                                        </h2>
                                    </div>

                                    <div id="collapseOne_{{$value->acat_id}}" class="collapse <?php echo $cls; ?>" aria-labelledby="headingOne" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <div id="loader" style="display:none;"></div>
                                            <button class="form-control addConfig" data-id="{{$value->acat_id}}" data-name="{{$value->acat_name}}" data-centerid="{{$cid}}"> Add new Configuration</button>
                                            <table  class="table table-striped table-responsive">
                                                <thead>
                                                    <tr class="headings">
                                                        <th class="column-title"> Configuration Type</th>
                                                        <th class="column-title">Seater</th>
                                                        @if($value->flag_hour == 1)
                                                        <th class="column-title">Rate/hour</th>
                                                        @endif
                                                        @if($value->flag_halfday == 1)
                                                        <th class="column-title">Half Day</th>
                                                        @endif
                                                        @if($value->flag_fullday == 1)
                                                        <th class="column-title">Full Day</th>
                                                        @endif
                                                        @if($value->flag_month == 1)
                                                        <th class="column-title">Rate/Month</th>
                                                        @endif
                                                        @if($value->flag_quart == 1)
                                                        <th class="column-title">Rate/Qtrly</th>
                                                        @endif
                                                        @if($value->flag_halfyear == 1)
                                                        <th class="column-title">Rate/Half Yearly</th>
                                                        @endif
                                                        @if($value->flag_year == 1)
                                                        <th class="column-title">Rate/Yearly</th>
                                                        @endif
                                                        <th class="column-title">Add Inventory</th>  
                                                        <th class="column-title">Action</th>  

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $rate_hour = 0.00;
                                                    $rate_half = 0.00;
                                                    $rate_full = 0.00;
                                                    $rate_month = 0.00;
                                                    $rate_qtr = 0.00;
                                                    $rate_hy = 0.00;
                                                    $rate_yr = 0.00;
                                                    foreach ($value['MsInfoDetail'] as $key => $value2) {



                                                        $rate_hour = $value2->ms_hour;
                                                        $rate_half = $value2->ms_half;
                                                        $rate_full = $value2->ms_full;
                                                        $rate_month = $value2->ms_month;
                                                        $rate_qtr = $value2->ms_quart;
                                                        $rate_hy = $value2->ms_hy;
                                                        $rate_yr = $value2->ms_year;
                                                    }
                                                    ?>
                                                    @foreach($value['MeetingroomDetail'] as $key=>$value1)
                                                    @if($value1->center_id == $cid)
                                                    <tr class="pointer">
                                                <input type="hidden" name="rr_id[]" value="{{$value1->rr_id}}">
                                                <td>{{$value1->ms_name}}</td>

                                                <td>{{$value1->ms_type}}</td>
                                                @if($value->flag_hour == 1)
                                                <td>
                                                    <input type="text" class="form-control onlyDec" name="ratehour_{{$value1->rr_id}}" autocomplete="off" value="{{$value1->ms_hour}}">
                                                    <span>Rate/hour : <?php echo $rate_hour; ?></span>
                                                </td>
                                                @endif
                                                @if($value->flag_halfday == 1)
                                                <td>
                                                    <input type="text" class="form-control onlyDec" name="ratehalf_{{$value1->rr_id}}" autocomplete="off" value="{{$value1->ms_half}}">
                                                    <span>Half Day : <?php echo $rate_half; ?></span>
                                                </td>
                                                @endif
                                                @if($value->flag_fullday == 1)
                                                <td>
                                                    <input type="text" class="form-control onlyDec" name="ratefull_{{$value1->rr_id}}" autocomplete="off" value="{{$value1->ms_full}}">
                                                    <span>Full Day : <?php echo $rate_full; ?></span>
                                                </td>
                                                @endif
                                                @if($value->flag_month == 1)
                                                <td>
                                                    <input type="text" class="form-control onlyDec" name="ratemonth_{{$value1->rr_id}}" autocomplete="off" value="{{$value1->ms_month}}">
                                                    <span>Month : <?php echo $rate_month; ?></span>
                                                </td>
                                                @endif
                                                @if($value->flag_quart == 1)
                                                <td>
                                                    <input type="text" class="form-control onlyDec" name="ratequart_{{$value1->rr_id}}" autocomplete="off" value="{{$value1->ms_pln_quart}}">
                                                    <span>Quarterly : <?php echo $rate_qtr; ?></span>
                                                </td>
                                                @endif
                                                @if($value->flag_halfyear == 1)
                                                <td>
                                                    <input type="text" class="form-control onlyDec" name="ratehy_{{$value1->rr_id}}" autocomplete="off" value="{{$value1->ms_pln_hy}}">
                                                    <span>Half Yearly : <?php echo $rate_hy; ?></span>
                                                </td>
                                                @endif
                                                @if($value->flag_year == 1)
                                                <td>
                                                    <input type="text" class="form-control onlyDec" name="rateyr_{{$value1->rr_id}}" autocomplete="off" value="{{$value1->ms_pln_yr}}">
                                                    <span>Yearly: <?php echo $rate_yr; ?></span>
                                                </td>
                                                @endif
                                                <td>
                                                    <input type="text" class="form-control onlyDec" name="countinv_{{$value1->rr_id}}" autocomplete="off" value="{{$value1->rr_no}}">
                                                </td>
                                                <td>

                                                    <a href="{{route('meetingroom.delete',base64_encode($value1->rr_id))}}"  title="Delete" class="d-inline-block delete confirmation"><img src="{{ URL::asset('images/delete.svg') }}" alt=""></a>
                                                </td>

                                                </tr>
                                                @endif
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            @endforeach

                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12  pb-3 clearfix"><button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2  m-0 text-uppercase" id="submitCentre">Update Service</button></div>

                </div>
            </div>
        </form>

    </div>
</div>
<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title config-title"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <table  class="table table-striped ">
                    <thead>
                        <tr class="headings">
                            <th class="column-title"></th>
                            <th class="column-title"> Configuration Type</th>
                            <th class="column-title">Seater</th>
                        </tr>
                    </thead>
                    <tbody class="populateConfiguration">



                    </tbody>
                </table>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary text-uppercase add-config-modal" data-dismiss="modal">Add Configuration</button>
            </div>

        </div>
    </div>
</div>
@endsection