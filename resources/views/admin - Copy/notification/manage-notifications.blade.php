@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0 ">
            <div class="x_title newRequestForm">
                <h2>Manage Notifications</h2>
                <div class="clearfix"></div>
                <div class="x_content pt-4 mb-3 col-12 clearfix">
                    <form method="post" id="sendNoti" action=" {{route('notification.send')}}" enctype="multipart/form-data">
                        {{ csrf_field() }}
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
                        <div class="row mt-4 m-0">

                            <div class="col-12 custom-control custom-radio ">
                                <input type="radio" id="customRadio1" name="customRadio" class="custom-control-input" id="allCust" value="allCust" checked="checked">
                                <label class="custom-control-label ml-2" for="customRadio1">All Customers</label>
                            </div>

                            <div class="col-12 pt-4 pb-4">
                                <p>or</p>
                            </div>
                            <div class="col-12 custom-control custom-radio ">

                                <input type="radio" id="customRadio2" name="customRadio" class="custom-control-input" id="selectCust" val="selectCust">
                                <label class="custom-control-label ml-2" for="customRadio2">Select Customers (Multiple)</label>
                            </div>
                        </div>
                        <div class="row mt-2 ">
                            <div class="col-md-12 mb-3">
                                <select  class="form-control select2_multiple" multiple="multiple"  name="seleCust[]" id="customers">
                                    @foreach($data as $customerKey=>$customerValue)
                                    <option value="{{$customerValue->cust_mobile."-".$customerValue->cust_id.'-'.$customerValue->cust_nme}}"  >{{$customerValue->cust_nme}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-lg-12 col-lg-12  mb-3">            
                                <label for="closedDate">Message</label>
                                <textarea class="form-control"  rows="10" name="camp_name" placeholder="Enter message" required=""></textarea>

                            </div>
                        </div>

                        <div class="row mt-4 m-0">
                            <div class="col-1 col-md-2 col-lg-2 col-xl-1  custom-control custom-checkbox">

                                <input type="checkbox" name="sms" class="custom-control-input" id="sms" checked="checked">
                                <label class="custom-control-label" for="sms">sms</label>
                            </div>
                            <div class="col-2 col-md-4  col-lg-3 col-xl-2 custom-control custom-checkbox ">

                                <input type="checkbox" name="push" class="custom-control-input" id="pushNotifications" >
                                <label class="custom-control-label" for="pushNotifications">Push Notifications</label>
                                <div class="mb-3">


                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-4 mb-3 padRig">
                                <label for="categoryId">Image</label>
                                @if ($errors->has('camp_img'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('camp_img') }}</strong>
                                </span>
                                @endif
                                <input  name="camp_img" type="file" id="catimageInput" >
                            </div>

                        </div>


                        <div class="row mt-4">
                            <div class="col-12  pb-3 clearfix"><button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2  m-0 text-uppercase" id="send">Send</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

