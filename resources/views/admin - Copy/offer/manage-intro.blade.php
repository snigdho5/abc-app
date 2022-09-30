@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Introduction </h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
                    <table id="datatable" class="table table-striped ">
                        <thead>
                            <tr class="headings">
                                <th class="column-title">Text</th>                  
                                <th class="column-title">Banner</th>                                 
                                <th class="column-title">URL</th>                             
                                <th class="column-title">Status</th>
                                <th class="column-title">Action</th>
                            </tr>
                        </thead>
                        <tbody>


                            @foreach($data as $key=>$value)
                            <tr class="pointer">
                                <td class="whiteSpace">{{strip_tags($value->intro_text)}}</td>
                                <td><img src="<?php if ($_SERVER['HTTP_HOST'] == 'localhost') {
    echo env('LOCAL_IMAGE_PATH');
} else {
    echo env('LIVE_IMAGE_PATH');
} ?>upload/intro/{{$value->intro_image}}" style="height: 50px;width:50px;"></td> 
                                <td class="whiteSpace">{{$value->intro_url}}</td>
                                <td>@if($value->intro_status ==1) Enable @else Disable @endif</td>
                                <td class="last">
                                    <a href="javascript:void(0);" title="Edit" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase editIntro" data-id="{{base64_encode($value->intro_id)}}">View</a> 
                                    <a href="{{route('introdata.delete',base64_encode($value->intro_id))}}"  title="Delete" class="d-inline-block delete confirmation"><img src="{{ URL::asset('images/delete.svg') }}" alt=""></a>
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
                <h2 id="titleText">Intro Data</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="introRegister" action="{{route('introdata.register')}} " enctype="multipart/form-data" > 
                {{ csrf_field() }}
                <input type="hidden" name="id" id="intro_id">
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
                    <div class="col-md-12 mb-3">
                        <label for="pageName">Intro text</label>
                        @if ($errors->has('intro_text'))
                        <span class="help-block">
                            <strong>{{ $errors->first('intro_text') }}</strong>
                        </span>
                        @endif
                        <textarea class="form-control " autocomplete="off" id="intro_text" name="intro_text" placeholder="Intro text" value="">
                            
                        </textarea>
                    </div>
                </div>
                <div class="row mt-4">


                    <div class="col-md-6 mb-3">
                        <label for="pageName" class="d-block">Intro Image</label>
                        @if ($errors->has('intro_image'))
                        <span class="help-block">
                            <strong>{{ $errors->first('intro_image') }}</strong>
                        </span>
                        @endif
                        <div  class="position-relative">
                            <span class="input-group-btn d-flex ">
                                <input id="customFile" type="file" class="d-none" name="intro_image" onchange="$(this).parent().parent().find('.form-control').html($(this).val().split(/[\\|/]/).pop());" style="display: none;">
                            </span>
                            <label for="customFile" class=" form-control formFile custom-file-label rounded-pill" onclick="$(this).parent().find('input[type=file]').click();">Choose file</label>

                        </div>
                    </div>



                </div>

                
                <div class="pt-5 pb-5"id="imagePage"></div>

                <div class="row mt-4">
                    <div class="col-md-12 mb-3">
                        <label for="pageName">Intro URL</label>
                        @if ($errors->has('intro_url'))
                        <span class="help-block">
                            <strong>{{ $errors->first('intro_url') }}</strong>
                        </span>
                        @endif
                        <input type="text" class="form-control " autocomplete="off" id="intro_url" name="intro_url" placeholder="Intro URL" value="" >
                    </div>
                </div>

                <div class="row mt-4">
                   
                    <div class="col-md-4 mb-3">
                        <label for="status">Status</label>
                        @if ($errors->has('intro_status'))
                        <span class="help-block">
                            <strong>{{ $errors->first('intro_status') }}</strong>
                        </span>
                        @endif
                        <select  class="form-control select"   id="intro_status" name="intro_status">

                            <option value="1" >Enable</option>
                            <option value="0" >Disable</option>


                        </select>
                    </div>

                </div>

                <div class="row mt-4">

                    <div class="col-6  pb-3 clearfix">
                        <button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2   text-uppercase " id="submitOffer">Submit</button> 
                        <button type="button" class="btn btn-outline-secondary pl-4 pr-4 pt-2 pb-2 ml-2 text-uppercase reset-btn" >Reset</button></div>

                </div>

            </form>
        </div>
    </div>
</div>

@endsection