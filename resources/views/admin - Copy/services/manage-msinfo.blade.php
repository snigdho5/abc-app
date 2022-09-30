@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Service Configuration</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
                    <table id="datatable" class="table table-striped ">
                        <thead>
                            <tr class="headings">
                                <th class="column-title">No</th>
                                <th class="column-title">Service Config</th>
                                <th class="column-title">Category</th>
                                <th class="column-title">Seater</th>
                                <th class="column-title">Status</th>
                                <th class="column-title">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php $i = 1;?>
                            @foreach($data as $key=>$value)
                            <tr class="pointer">
                                <td><?php echo $i++;?></td>
                                <td>{{$value->ms_name}}</td>
                                <td>{{getCatName($value->ms_cat)}}</td>
                                <td>{{$value->ms_type}}</td>
                                 <td>@if($value->ms_status ==1) Enable @else Disable @endif</td>
                                <td class="last">
                                    <a href="javascript:void(0);" title="Edit" class="mr-4 ml-2 d-inline-block"><img data-id="{{base64_encode($value->ms_id)}}" class="editMsinfo" src="{{ URL::asset('images/edit.svg') }}" alt=""></a> 
                                    <a href="{{route('msinfo.delete',base64_encode($value->ms_id))}}"  title="Delete" class="d-inline-block delete confirmation"><img src="{{ URL::asset('images/delete.svg') }}" alt=""></a>
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
                <h2 id="titleText"> Add Service Configuration</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="msinfoRegister" action="{{route('msinfo.register')}} "> 

                {{ csrf_field() }}
                <input type="hidden" name="ms_id" id="ms_id">
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
                        <label for="locationName">Service Category</label>
                        @if ($errors->has('ms_cat'))
                        <span class="help-block">
                            <strong>{{ $errors->first('ms_cat') }}</strong>
                        </span>
                        @endif
                        <select  class="form-control select " id="ms_cat" name="ms_cat" onchange="check_serflag(0)">
                            <option value="" >Select Service  Category</option>
                            @foreach($cdata as $key=>$value)
                            <option value="{{$value->acat_id}}" >{{$value->acat_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3 padRig">
                        <label for="locationName">Title</label>
                        @if ($errors->has('ms_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('ms_name') }}</strong>
                        </span>
                        @endif
                        <input type="text" class="form-control " autocomplete="off" id="ms_name" name="ms_name" placeholder=""  >

                    </div>
                    
                    <div class="col-md-4 mb-3 padRig">
                        <label for="locationName">Seater</label>
                        @if ($errors->has('ms_type'))
                        <span class="help-block">
                            <strong>{{ $errors->first('ms_type') }}</strong>
                        </span>
                        @endif
                        <input type="text" class="form-control onlyNum" autocomplete="off" id="ms_type" name="ms_type" placeholder=""  >

                    </div>
                    
                </div>
                <div class="row mt-4">
                    
                    <div class="col-md-4 mb-3 padRig ms_hour">
                        <label for="locationName">Rate/hour</label>
                        @if ($errors->has('ms_hour'))
                        <span class="help-block">
                            <strong>{{ $errors->first('ms_hour') }}</strong>
                        </span>
                        @endif
                        <input type="text" class="form-control onlyDec" autocomplete="off" id="ms_hour" name="ms_hour" placeholder=""  >

                    </div>
                    
                    <div class="col-md-4 mb-3 padRig ms_half">
                        <label for="locationName">Half Day</label>
                        @if ($errors->has('ms_half'))
                        <span class="help-block">
                            <strong>{{ $errors->first('ms_half') }}</strong>
                        </span>
                        @endif
                        <input type="text" class="form-control onlyDec " autocomplete="off" id="ms_half" name="ms_half" placeholder=""  >

                    </div>
                    <div class="col-md-4 mb-3 padRig ms_full">
                        <label for="locationName">Full Day</label>
                        @if ($errors->has('ms_full'))
                        <span class="help-block">
                            <strong>{{ $errors->first('ms_full') }}</strong>
                        </span>
                        @endif
                        <input type="text" class="form-control onlyDec" autocomplete="off" id="ms_full" name="ms_full" placeholder=""  >

                    </div>
                    
                </div>
                
                <div class="row mt-4">
                    
                    <div class="col-md-4 mb-3 padRig ms_month">
                        <label for="locationName">Month</label>
                        @if ($errors->has('ms_month'))
                        <span class="help-block">
                            <strong>{{ $errors->first('ms_month') }}</strong>
                        </span>
                        @endif
                        <input type="text" class="form-control onlyDec" autocomplete="off" id="ms_month" name="ms_month" placeholder=""  >

                    </div>
                    <div class="col-md-4 mb-3 padRig ms_quart">
                        <label for="locationName">Quarterly</label>
                        @if ($errors->has('ms_quart'))
                        <span class="help-block">
                            <strong>{{ $errors->first('ms_quart') }}</strong>
                        </span>
                        @endif
                        <input type="text" class="form-control onlyDec" autocomplete="off" id="ms_quart" name="ms_quart" placeholder=""  >

                    </div>
                    <div class="col-md-4 mb-3 padRig ms_hy">
                        <label for="locationName">Half Yearly</label>
                        @if ($errors->has('ms_hy'))
                        <span class="help-block">
                            <strong>{{ $errors->first('ms_hy') }}</strong>
                        </span>
                        @endif
                        <input type="text" class="form-control onlyDec" autocomplete="off" id="ms_hy" name="ms_hy" placeholder=""  >

                    </div>
                    
                </div>
                <div class="row mt-4">
                    
                    <div class="col-md-4 mb-3 padRig ms_year">
                        <label for="locationName">Year</label>
                        @if ($errors->has('ms_year'))
                        <span class="help-block">
                            <strong>{{ $errors->first('ms_year') }}</strong>
                        </span>
                        @endif
                        <input type="text" class="form-control onlyDec" autocomplete="off" id="ms_year" name="ms_year" placeholder=""  >

                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="status">Status</label>
                        @if ($errors->has('ms_status'))
                        <span class="help-block">
                            <strong>{{ $errors->first('ms_status') }}</strong>
                        </span>
                        @endif
                        <select  class="form-control select " id="ms_status" name="ms_status">
                            <option value="1"  >Enable</option>
                            <option value="0" >Disable</option>
                        </select>
                    </div>
                </div>

                
                 <div class="row mt-4">
                	<div class="col-6  pb-3 clearfix">
                    	<button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2   text-uppercase " id="submitMsinfo">Submit</button>
                    	<button type="button" class="btn btn-outline-secondary pl-4 pr-4 pt-2 pb-2 ml-2 text-uppercase btn-reset reset-btn " id="submitCategory">Reset</button></div>
            	</div>

            </form>
        </div>
    </div>
</div>
@endsection