@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Virtual Tour</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
                    <table id="datatable" class="table table-striped ">
                        <thead>
                            <tr class="headings">
                                <th class="column-title">Centre</th>                  
                                <th class="column-title">Title</th>                  
                                <th class="column-title">Sub-Title</th>                  
                                <th class="column-title">Map</th>                                    
                                <th class="column-title">Status</th>
                                <th class="column-title">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $key=>$value)
                            <tr class="pointer">
                                <td class="whiteSpace">{{getCentreName($value->vt_centre_id)}}</td>
                                <td class="whiteSpace">{{strip_tags($value->vt_title)}}</td>
                                <td class="whiteSpace">{{strip_tags($value->vt_subtitle)}}</td>
                                <td class="whiteSpace">{{$value->vt_embed_map}}</td>
                                <td>@if($value->vt_status ==1) Enable @else Disable @endif</td>
                                <td class="last">
                                    <a href="javascript:void(0);" title="Edit" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase editVtour" data-id="{{base64_encode($value->vt_id)}}">View</a> 
                                    <a href="{{route('virtualtour.delete',base64_encode($value->vt_id))}}"  title="Delete" class="d-inline-block delete confirmation"><img src="{{ URL::asset('images/delete.svg') }}" alt=""></a>
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
                <h2 id="titleText">Virtual Tour</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="vtourRegister" action="{{route('virtualtour.register')}} " enctype="multipart/form-data" > 
                {{ csrf_field() }}
                <input type="hidden" name="id" id="vt_id">
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
                        <label for="status">Centre</label>
                        @if ($errors->has('center_id'))
                        <span class="help-block">
                            <strong>{{ $errors->first('center_id') }}</strong>
                        </span>
                        @endif
                        <select  class="form-control select "   id="vt_centre_id" name="vt_centre_id">
                            <option value="" >Select Centre</option>
                            @foreach($centreData as $key=>$value)
<?php $locnm = getLocationName($value->location); ?>
                            <option value="{{$value->centre_id}}" >{{$value->centre}} (<?php echo $locnm->loc_name; ?>)</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-12 mb-3">
                        <label for="pageName">Title</label>
                        @if ($errors->has('vt_title'))
                        <span class="help-block">
                            <strong>{{ $errors->first('vt_title') }}</strong>
                        </span>
                        @endif
                        <textarea class="form-control " autocomplete="off" id="vt_title" name="vt_title" placeholder="Title" value="">
                            
                        </textarea>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-12 mb-3">
                        <label for="pageName">Sub Title</label>
                        @if ($errors->has('vt_subtitle'))
                        <span class="help-block">
                            <strong>{{ $errors->first('vt_subtitle') }}</strong>
                        </span>
                        @endif
                        <textarea class="form-control " autocomplete="off" id="vt_subtitle" name="vt_subtitle" placeholder="Sub Title" value="">
                            
                        </textarea>
                    </div>
                </div>
                <div class="row mt-4">


                    <div class="col-md-6 mb-3">
                        <label for="pageName" class="d-block">Thumb Image</label>
                        @if ($errors->has('vt_th_img'))
                        <span class="help-block">
                            <strong>{{ $errors->first('vt_th_img') }}</strong>
                        </span>
                        @endif
                        <div  class="position-relative">
                            <span class="input-group-btn d-flex ">
                                <input id="customFile" type="file" class="d-none" name="vt_th_img" onchange="$(this).parent().parent().find('.form-control').html($(this).val().split(/[\\|/]/).pop());" style="display: none;">
                            </span>
                            <label for="customFile" class=" form-control formFile custom-file-label rounded-pill" >Choose file</label>

                        </div>
                    </div>



                </div>

                
                <div class="pt-5 pb-5"id="imagePage"></div>

                
                <div class="row mt-4">
                    <div class="col-md-12 mb-3">
                        <label for="pageName">Embedded Map</label>
                        @if ($errors->has('vt_embed_map'))
                        <span class="help-block">
                            <strong>{{ $errors->first('vt_embed_map') }}</strong>
                        </span>
                        @endif
                        <input type="text" class="form-control " autocomplete="off" id="vt_embed_map" name="vt_embed_map" placeholder="Embedded Map" value="" >
                    </div>
                </div>

                <div class="row mt-4">
                    
                    <div class="col-md-4 mb-3">
                        <label for="status">Status</label>
                        @if ($errors->has('vt_status'))
                        <span class="help-block">
                            <strong>{{ $errors->first('vt_status') }}</strong>
                        </span>
                        @endif
                        <select  class="form-control select"   id="vt_status" name="vt_status">

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