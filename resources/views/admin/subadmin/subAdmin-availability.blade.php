@extends('admin.layouts.app')

@section('content')


<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Manager</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
                    <table id="datatable" class="table table-striped ">
                        <thead>
                            <tr class="headings">
                                <th class="column-title">Manager ID</th>
                                <th class="column-title">Manager Name</th>
                                <th class="column-title">Centre Name</th>
                                <th class="column-title">Email</th>
                                <th class="column-title">Mobile</th>
                                <th class="column-title">Date</th>
                                <th class="column-title">Status</th>
                                <th class="column-title">Action</th>
                            </tr>
                        </thead>
                        <tbody>


                            @foreach($data as $key=>$value)
                            <tr class="pointer">
                                <td>{{$value->code}}</td>
                                <td class="whiteSpace">{{$value->user_name}}</td>
                                <td class="whiteSpace"><a href="{{route('centre.manage')}}">{{getCentreForManager($value->id)}}</a></td>
                                <td class="whiteSpace">{{$value->email}}</td>
                                <td class="whiteSpace"> {{$value->mobile}}</td>
                                <td class="whiteSpace">
                                    {!! date("d M Y",strtotime($value->created_at))!!}
                                </td>
                                <td>
                                    @if($value->status ==0) Enable @else Disable @endif
                                </td>
                                <td class="last">
                                    <a href="javascript:void(0);" title="Edit" class="mr-4 ml-2 d-inline-block"><img data-id="{{base64_encode($value->id)}}"  class="editSubAdmin" src="{{ URL::asset('images/edit.svg') }}" alt=""></a> 
                                    <a href="{{route('subAdmin.delete',base64_encode($value->id))}}"  title="Delete" class="d-inline-block delete confirmation"><img src="{{ URL::asset('images/delete.svg') }}" alt=""></a>
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
                <h2 id="titleText" >Add New Manager</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="subAdminRegister" action="{{route('subadmin.subAdmin_register')}}"> 

                {{ csrf_field() }}
                <input type="hidden" name="id" id="subadmin_id" >
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
                        <label for="SubAdmin">Manager Name</label>
                        <input type="text" class="form-control " autocomplete="off" id="subadminName" name="user_name" placeholder="Your Name" value="" >
                    </div>
                    <div class="col-md-4 mb-3 padRig">
                        <label for="requestEmailID">Email ID</label>
                        <input type="text" class="form-control" id="requestEmailID" autocomplete="off" name="email" placeholder="Your Email ID" value="" >
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="requestMobile">Mobile</label>
                        <input type="tel" class="form-control" id="requestMobile"  maxlength="10" autocomplete="off" name="mobile" placeholder="Your Mobile No" value="" >
                    </div>
                </div>
                <div class="row mt-2">

                    <div class="col-md-6 mb-3 padRig">
                        <label for="requestLocation">Center (Multiple)</label>
                        <select  class="form-control select config-val select-multiple"   id="center_id" name="center_id[]" multiple>
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
                        <label for="commentsubadmin ">Comment(Any)</label>
                        <textarea class="form-control" id="commentsubadmin" name="cmt"></textarea>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-4 mb-3">
                        <label for="status">Status</label>
                        <select  class="form-control select"  id="status" name="status">
                            <option value="0"  >Enable</option>
                            <option value="1" >Disable</option>
                        </select>
                    </div>

                </div>
                <div class="row mt-4">
                    <div class="col-12  pb-3 clearfix">
                        <button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2  m-0 text-uppercase" id="submitSubAdmin">Add Manager</button>
                        <a style="margin-top: 6px"href="#" class="btn btn-outline-secondary pl-4 pr-4 pt-2 pb-2 size-16   text-uppercase reset-btn resetbtn" id="back">RESET </a>
                    </div>

                </div>

            </form>
        </div>
    </div>
</div>
@endsection