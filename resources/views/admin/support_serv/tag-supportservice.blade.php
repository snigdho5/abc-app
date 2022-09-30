@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Tag Support Service to Centre</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
                    <table id="datatable" class="table table-striped ">
                        <thead>
                            <tr class="headings">
                                <th class="column-title">Support Service</th>                  
                                <th class="column-title">Centre</th>                                 
                                <th class="column-title">Action</th>
                            </tr>
                        </thead>
                        <tbody>


                            @foreach($data as $key=>$value)
                            <tr class="pointer">
                                <td class="whiteSpace">{{getSupportName($value->ssid)}}</td>
                                <td class="whiteSpace">{{getCentreName($value->centreid)}}</td>
                                <td class="last">
                                    <a href="javascript:void(0);" title="Edit" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase editTagSupportService" data-id="{{base64_encode($value->centreid)}}">View</a> 
                                    <a href="{{route('sprtservtocomp.delete',base64_encode($value->id))}}"  title="Delete" class="d-inline-block delete confirmation"><img src="{{ URL::asset('images/delete.svg') }}" alt=""></a>
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
                <h2 id="titleText">Tag Support Service to Centre</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="tagsupportservRegister" action="{{route('sprtservtocomp.register')}} " enctype="multipart/form-data" > 
                {{ csrf_field() }}
                <input type="hidden" name="id" id="ss_id">
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
                    <div class="col-md-6 mb-3">
                        <label for="status">Centre</label>
                        @if ($errors->has('centreid'))
                        <span class="help-block">
                            <strong>{{ $errors->first('centreid') }}</strong>
                        </span>
                        @endif
                        <select  class="form-control select config-val"   id="centreid" name="centreid">
                            <option value="" >Select Centre</option>
                            @foreach($centreData as $key=>$value)
                            <?php $locnm = getLocationName($value->location); ?>
                            <option value="{{$value->centre_id}}" >{{$value->centre}} (<?php echo $locnm->loc_name; ?>)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3 padRig">
                        <label for="status">Types</label>
                        @if ($errors->has('ssid'))
                        <span class="help-block">
                            <strong>{{ $errors->first('ssid') }}</strong>
                        </span>
                        @endif
                        <select  class="form-control select2 " id="ssid" name="ssid[]" multiple>
                            @foreach($supportserviceData as $key=>$value)
                            <option value="{{$value->ss_id}}" >{{$value->ss_text}} </option>
                            @endforeach
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