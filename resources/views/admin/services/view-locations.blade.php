@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Location</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
                    <table id="datatable" class="table table-striped ">
                        <thead>
                            <tr class="headings">
                                <th class="column-title">Location</th>
                                <th class="column-title">Status</th>
                                <th class="column-title">Action</th>
                            </tr>
                        </thead>
                        <tbody>


                            @foreach($data as $key=>$value)
                            <tr class="pointer">
                                <td>{{$value->loc_name}}</td>
                                 <td>@if($value->loc_status ==1) Enable @else Disable @endif</td>
                                <td class="last">
                                   
                                    <a href="javascript:void(0);" title="Edit" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase editLocation" data-id="{{base64_encode($value->loc_id)}}">View</a> 
<!--                                    <a href="{{route('location.delete',base64_encode($value->loc_id))}}"  title="Delete" class="d-inline-block delete confirmation"><img src="{{ URL::asset('images/delete.svg') }}" alt=""></a>-->
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
                <h2 id="titleText"> Add Location</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="locationRegister" action="{{route('location.register')}} " enctype="multipart/form-data"> 

                {{ csrf_field() }}
                <input type="hidden" name="loc_id" id="loc_id">
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
                        <label for="locationName">Location</label>
                        @if ($errors->has('loc_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('loc_name') }}</strong>
                        </span>
                        @endif
                        <input type="text" class="form-control " autocomplete="off" id="locationName" name="loc_name" placeholder="eg:-Noida"  >
                    </div>
                   
                    
                    <div class="col-md-4 mb-3">
                        <label for="pageName" class="d-block">Image</label>
                        @if ($errors->has('loc_img'))
                        <span class="help-block">
                            <strong>{{ $errors->first('loc_img') }}</strong>
                        </span>
                        @endif
                        <div  class="position-relative">
                            <span class="input-group-btn d-flex ">
                                <input id="customFile" type="file" class=" d-none" name="loc_img" onchange="$(this).parent().parent().find('.form-control').html($(this).val().split(/[\\|/]/).pop());" style="display: none;">
                            </span>
                            <label for="customFile" class=" form-control formFile custom-file-label rounded-pill" >Choose file</label>

                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="status">Status</label>
                        @if ($errors->has('status'))
                        <span class="help-block">
                            <strong>{{ $errors->first('status') }}</strong>
                        </span>
                        @endif
                        <select  class="form-control select " id="cstatus" name="loc_status">
                            <option value="1"  >Enable</option>
                            <option value="0" >Disable</option>
                        </select>
                    </div>
                </div>
                 <div id="imagePage"></div>
                <div class="row mt-4">
                    
                    <div class="col-6  pb-3 clearfix">
                        <button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2   text-uppercase " id="submitLocation">Submit</button> 
                        <button type="button" class="btn btn-outline-secondary pl-4 pr-4 pt-2 pb-2 ml-2 text-uppercase reset-btn" >Reset</button></div>
                </div>



            </form>
        </div>
    </div>
</div>
@endsection