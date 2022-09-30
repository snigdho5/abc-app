@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Offers</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
                    <table id="datatable" class="table table-striped ">
                        <thead>
                            <tr class="headings">
                                <th class="column-title">Text</th>                  
                                <th class="column-title">Banner</th>                  
                                <th class="column-title">URL Type</th>                             
                                <th class="column-title">URL</th>                             
                                <th class="column-title">Status</th>
                                <th class="column-title">Action</th>
                            </tr>
                        </thead>
                        <tbody>


                            @foreach($data as $key=>$value)
                            <tr class="pointer">
                                <td class="whiteSpace">{{strip_tags($value->offer_text)}}</td>
                                <td><img src="<?php if ($_SERVER['HTTP_HOST'] == 'localhost') {
    echo env('LOCAL_IMAGE_PATH');
} else {
    echo env('LIVE_IMAGE_PATH');
} ?>upload/offer/{{$value->offer_banner}}" style="height: 50px;width:50px;"></td> 
                                <td>@if($value->offer_url_flg ==1) Internal @else External @endif</td>
                                <td class="whiteSpace">{{$value->offer_url}}</td>
                                <td>@if($value->offer_status ==1) Enable @else Disable @endif</td>
                                <td class="last">
                                    <a href="javascript:void(0);" title="Edit" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase editOffer" data-id="{{base64_encode($value->offer_id)}}">View</a> 
                                    <a href="{{route('offer.delete',base64_encode($value->offer_id))}}"  title="Delete" class="d-inline-block delete confirmation"><img src="{{ URL::asset('images/delete.svg') }}" alt=""></a>
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
                <h2 id="titleText">Offers</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="offerRegister" action="{{route('offer.register')}} " enctype="multipart/form-data" > 
                {{ csrf_field() }}
                <input type="hidden" name="id" id="offer_id">
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
                        <label for="pageName">Offer text</label>
                        @if ($errors->has('offer_text'))
                        <span class="help-block">
                            <strong>{{ $errors->first('offer_text') }}</strong>
                        </span>
                        @endif
                        <textarea class="form-control " autocomplete="off" id="offer_text" name="offer_text" placeholder="Offer text" value="">
                            
                        </textarea>
                    </div>
                </div>
                <div class="row mt-4">


                    <div class="col-md-6 mb-3">
                        <label for="pageName" class="d-block">Offer Image</label>
                        @if ($errors->has('offer_banner'))
                        <span class="help-block">
                            <strong>{{ $errors->first('offer_banner') }}</strong>
                        </span>
                        @endif
                        <div  class="position-relative">
                            <span class="input-group-btn d-flex ">
                                <input id="customFile" type="file" class="d-none" name="offer_banner" onchange="$(this).parent().parent().find('.form-control').html($(this).val().split(/[\\|/]/).pop());" style="display: none;">
                            </span>
                            <label for="customFile" class=" form-control formFile custom-file-label rounded-pill" onclick="$(this).parent().find('input[type=file]').click();">Choose file</label>

                        </div>
                    </div>



                </div>

                
                <div class="pt-5 pb-5"id="imagePage"></div>

                URL Type :-
                <div class="row mt-4">
                    <div class="col mb-3 align-self-center mt-4">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="flag_internal_url" class="custom-control-input all_ofr_flag" id="flag_internal_url" >
                            <label class="custom-control-label" for="flag_internal_url">Internal</label>
                        </div>
                    </div>

                    <div class="col mb-3 align-self-center mt-4">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="flag_external_url" class="custom-control-input all_ofr_flag" id="flag_external_url" >
                            <label class="custom-control-label" for="flag_external_url">External</label>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-12 mb-3">
                        <label for="pageName">Offer URL</label>
                        @if ($errors->has('offer_url'))
                        <span class="help-block">
                            <strong>{{ $errors->first('offer_url') }}</strong>
                        </span>
                        @endif
                        <input type="text" class="form-control " autocomplete="off" id="offer_url" name="offer_url" placeholder="Offer URL" value="" >
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
                        <select  class="form-control select internal_url_check"   id="offer_centre_id" name="offer_centre_id" readonly>
                            <option value="" >Select Centre</option>
                            @foreach($centreData as $key=>$value)
<?php $locnm = getLocationName($value->location); ?>
                            <option value="{{$value->centre_id}}" >{{$value->centre}} (<?php echo $locnm->loc_name; ?>)</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="status">Category</label>
                        @if ($errors->has('offer_category_id'))
                        <span class="help-block">
                            <strong>{{ $errors->first('offer_category_id') }}</strong>
                        </span>
                        @endif
                        <select  class="form-control select internal_url_check"   id="offer_category_id" name="offer_category_id" readonly>
                            <option value="" >Select Category</option>
                            @foreach($categoryData as $key=>$value)
                            <option value="{{$value->acat_id}}" >{{$value->acat_name}} </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="status">Status</label>
                        @if ($errors->has('status'))
                        <span class="help-block">
                            <strong>{{ $errors->first('status') }}</strong>
                        </span>
                        @endif
                        <select  class="form-control select"   id="offer_status" name="offer_status">

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