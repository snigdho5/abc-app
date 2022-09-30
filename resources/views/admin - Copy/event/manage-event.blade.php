@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Events</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
                    <table id="datatable" class="table table-striped ">
                        <thead>
                            <tr class="headings">
                                <th class="column-title"> ID</th>
                                <th class="column-title">Name</th>                  
                                <th class="column-title">Duration</th>                  
                                <th class="column-title">Details</th>                  
                                <th class="column-title">Image</th>                       
                                <th class="column-title">Status</th>
                                <th class="column-title">Action</th>
                            </tr>
                        </thead>
                        <tbody>


                            @foreach($data as $key=>$value)
                            <tr class="pointer">
                                <td>{{$value->e_id}}</td>
                                <td class="whiteSpace">{{$value->e_name}}</td>
                                <td class="whiteSpace">{{date('F j Y',strtotime($value->e_from)) .' to '.date('F j Y',strtotime($value->e_to))}}</td>
                                <td class="whiteSpace">{{$value->e_detail}}</td>
                                <td><img src="<?php
                                    if ($_SERVER['HTTP_HOST'] == 'localhost') {
                                        echo env('LOCAL_IMAGE_PATH');
                                    } else {
                                        echo env('LIVE_IMAGE_PATH');
                                    }
                                    ?>upload/event/{{$value->e_img}}" style="height: 50px;width:50px;"></td> 
                                <td>@if($value->e_status ==1) Enable @else Disable @endif</td>
                                <td class="last">
                                    <a href="javascript:void(0);" title="Edit" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase editEvent" data-id="{{base64_encode($value->e_id)}}">View</a> 
                                    <a href="{{route('event.delete',base64_encode($value->e_id))}}"  title="Delete" class="d-inline-block delete confirmation"><img src="{{ URL::asset('images/delete.svg') }}" alt=""></a>
                                </td>
                            </tr>
                            @endforeach

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
                <h2 id="titleText">Events</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="eventRegister" action="{{route('event.register')}} " enctype="multipart/form-data" > 
                {{ csrf_field() }}
                <input type="hidden" name="id" id="e_id">
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
                    <div class="col-md-12 mb-3 padRig">
                        <label for="pageName">Name</label>
                        @if ($errors->has('e_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('e_name') }}</strong>
                        </span>
                        @endif
                        <input type="text" class="form-control " autocomplete="off" id="e_name" name="e_name" placeholder="Event Name" value="" >
                        </select>
                    </div>

                </div>

                <div class="row mt-4">
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
                        </select>
                    </div>

                </div>

                <div class="row mt-4">
                    <div class="col-md-12 mb-3 padRig">
                        <label for="pageName">Details</label>
                        @if ($errors->has('e_detail'))
                        <span class="help-block">
                            <strong>{{ $errors->first('e_detail') }}</strong>
                        </span>
                        @endif
                        <textarea class="form-control " autocomplete="off" id="e_detail" name="e_detail" placeholder="Event Details" value="" rows="4"></textarea>
                        </select>
                    </div>

                </div>



                <div class="row mt-4">
                    <div class="col-md-4 mb-3 padRig">
                        <label for="pageName">Image</label>
                        @if ($errors->has('e_img'))
                        <span class="help-block">
                            <strong>{{ $errors->first('e_img') }}</strong>
                        </span>
                        @endif
                        <div  class="position-relative">
                            <span class="input-group-btn d-flex ">
                                <input id="customFile" type="file" class="d-none" name="e_img" onchange="$(this).parent().parent().find('.form-control').html($(this).val().split(/[\\|/]/).pop());" style="display: none;">
                            </span>
                            <label for="customFile" class=" form-control formFile custom-file-label rounded-pill" >Choose file</label>

                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3 padRig">
                        <label for="pageName">Gallery</label>
                        @if ($errors->has('e_gallery'))
                        <span class="help-block">
                            <strong>{{ $errors->first('e_gallery') }}</strong>
                        </span>
                        @endif
                        <div  class="position-relative">
                            <span class="input-group-btn d-flex ">
                                <input id="customFile1" type="file" multiple class=" d-none" name="e_gallery[]" onchange="$(this).parent().parent().find('.form-control1').html($(this).val().split(/[\\|/]/).pop());" style="display: none;">
                            </span>
                            <label for="customFile1" class=" form-control1 formFile custom-file-label rounded-pill" >Choose files</label>

                        </div>
                    </div>

                    <div class="col-md-4 mb-3 ">
                        <label for="status">Centre</label>
                        @if ($errors->has('n_centre_id'))
                        <span class="help-block">
                            <strong>{{ $errors->first('n_centre_id') }}</strong>
                        </span>
                        @endif
                        <select class="form-control" multiple  name="e_centre_id[]" id="e_centre_id">
                            <option value="0">All</option>
                            @foreach($cData as $key =>$value)
                            <?php $locnm = getLocationName($value->location); ?>
                            <option value="{{$value->centre_id}}">{{$value->centre}}(<?php echo $locnm->loc_name; ?>)</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div id="imagePage"></div>
                <div class="row mt-4">

                    <div class="col-md-4 mb-3">
                        <label for="status">Status</label>
                        @if ($errors->has('e_status'))
                        <span class="help-block">
                            <strong>{{ $errors->first('e_status') }}</strong>
                        </span>
                        @endif
                        <select  class="form-control select"   id="e_status" name="e_status">

                            <option value="1" >Enable</option>
                            <option value="0" >Disable</option>


                        </select>
                    </div>
                </div>

                <div class="row mt-4">

                    <div class="col-6  pb-3 clearfix">
                        <button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2   text-uppercase " id="submitEvent">Submit</button>

                        <button type="button" class="btn btn-outline-secondary pl-4 pr-4 pt-2 pb-2 ml-2 text-uppercase reset-btn" >Reset</button></div>

                </div>



            </form>
        </div>
    </div>
</div>

@endsection