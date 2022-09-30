@extends('admin.layouts.app')

@section('content')


<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Email Matrix</h2>
                <?php if (Auth::guard('admin')->user()->admin_role != 1) { ?>
                    <a href="{{route('meetingroom.managecenterconfig')}}" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase pull-right" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a> 
                <?php } ?>



                <div class="clearfix"></div>
            </div>

            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
                    <table id="datatable" class="table table-striped ">
                        <thead>
                            <tr class="headings">
                                <th class="column-title">Centre</th>                  
                                <th class="column-title">Person</th>                  
                                <th class="column-title">Email</th>                             
                                <th class="column-title">Phone</th>                             
                                <th class="column-title">Status</th>
                                <th class="column-title">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                                @foreach($data as $key=>$value)

                                <tr class="pointer">
                                    <td class="whiteSpace">{{getCentreName($value->centre_id)}}</td>
                                    <td class="whiteSpace">{{$value->em_per}}</td>
                                    <td class="whiteSpace">{{$value->em_email}}</td>
                                    <td class="whiteSpace">{{$value->em_phone}}</td>

                                    <td>@if($value->em_status ==1) Enable @else Disable @endif</td>
                                    <td class="last">

                                        <a href="javascript:void(0);" title="View" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase editEmailMatrix" data-id="{{base64_encode($value->centre_id)}}">View</a> 

                                        <a href="{{route('emailmatrix.delete',base64_encode($value->em_id))}}"  title="Delete" class="d-inline-block delete confirmation"><img src="{{ URL::asset('images/delete.svg') }}" alt=""></a>
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
                <h2 id="titleText">Add Email Matrix</h2>
                <div class="clearfix"></div>
            </div>

            <form method="post" id="emailMatrixRegister" action="{{route('emailmatrix.register')}} " enctype="multipart/form-data" > 
                {{ csrf_field() }}
                <input type="hidden" name="id[]" id="em_id" >
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

                <?php if ( count($centreList) > 1 || count($centreList) == 0) {  ?>

                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <label for="status">Centre</label>
                            @if ($errors->has('center_id'))
                            <span class="help-block">
                                <strong>{{ $errors->first('center_id') }}</strong>
                            </span>
                            @endif
                            <select class="form-control select config-val" id="centre_id_emailmatrix" name="centre_id" required>
                                <option value="">Select Centre</option>
                                @foreach($centreData as $key=>$value)
                                <?php $locnm = getLocationName($value->location); ?>
                                <option value="{{$value->centre_id}}">{{$value->centre}} (<?php echo $locnm->loc_name; ?>)</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                <?php } else { ?>
                    <input type="hidden" name="centre_id" value="<?php echo $centreList[0]; ?>">
                <?php } ?>


                <div class="row mt-4 onerow">
                    <div class="col-md-1 mb-3">
                        <label for="pageName">S.No</label>
                        <span class="help-block d-block pt-2 ">
                            <strong >1</strong>
                        </span>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="pageName">Contact Person</label>
                        <input type="hidden" name="em_id[]" id="em_id1" >
                        
						<?php $cperson = '';if(count($centreList) == 1) { $cperson = $centreData[0]['centre']; }?>
                        <input type="text" class="form-control config-val pname" autocomplete="off" id="em_per1" name="em_per[]" placeholder="Contact Person" value="{{$cperson}}"  readonly>
                    </div>



                    <div class="col-md-4 mb-3">
                        <label for="pageName">Contact Email</label>
						<?php $cemail = '';if(count($centreList) == 1) { $cemail = $centreData[0]['centre_email']; }?>
                        <input type="text" class="form-control config-val " autocomplete="off" id="em_email1" name="em_email[]" placeholder="Contact Email" value="{{$cemail}}" readonly>
                     
                    </div>



                    <div class="col-md-3 mb-3">
                        <label for="pageName">Contact Phone</label>
                       <?php $cphone = '';if(count($centreList) == 1) { $cphone = $centreData[0]['centre_mobile']; }?>
                        <input type="text" class="form-control config-val onlyNum" autocomplete="off" id="em_phone1" name="em_phone[]" maxlength="10" placeholder="Contact Phone" value="{{$cphone}}" readonly>
                        
                    </div>

                </div>

                <div class="row mt-4 tworow">
                    <div class="col-md-1 mb-3">
                        <span class="help-block d-block pt-2 ">
                            <strong >2</strong>
                        </span>
                    </div>
                    <div class="col-md-4 mb-3">
                        <input type="hidden" name="em_id[]" id="em_id2" >

                        <input type="text" class="form-control config-val pname" autocomplete="off" id="em_per2" name="em_per[]" placeholder="Contact Person" value="" >
                    </div>



                    <div class="col-md-4 mb-3">

                        <input type="text" class="form-control config-val " autocomplete="off" id="em_email2" name="em_email[]" placeholder="Contact Email" value="" >
                    </div>



                    <div class="col-md-3 mb-3">

                        <input type="text" class="form-control config-val onlyNum" autocomplete="off" id="em_phone2" maxlength="10" name="em_phone[]" placeholder="Contact Phone" value="" >
                        
                    </div>

                </div>
                <div class="row mt-4 threerow">
                    <div class="col-md-1 mb-3">
                        <span class="help-block d-block pt-2 ">
                            <strong >3</strong>
                        </span>
                    </div>
                    <div class="col-md-4 mb-3">
                        
                        <input type="hidden" name="em_id[]" id="em_id3" >

                        <input type="text" class="form-control config-val pname" autocomplete="off" id="em_per3" name="em_per[]" placeholder="Contact Person" value="" >
                  
                    </div>



                    <div class="col-md-4 mb-3">

                        <input type="text" class="form-control config-val " autocomplete="off" id="em_email3" name="em_email[]" placeholder="Contact Email" value="" >
                        
                    </div>



                    <div class="col-md-3 mb-3">


                        <input type="text" class="form-control config-val onlyNum" autocomplete="off" id="em_phone3" maxlength="10" name="em_phone[]" placeholder="Contact Phone" value="" >
                       
                    </div>

                </div>	
                <div class="row mt-4">


                    <div class="col-md-4 mb-3">
                        <label for="status">Status</label>
                        @if ($errors->has('em_status'))
                        <span class="help-block">
                            <strong>{{ $errors->first('em_status') }}</strong>
                        </span>
                        @endif
                        <select  class="form-control select"   id="em_status" name="em_status">

                            <option value="1" >Enable</option>
                            <option value="0" >Disable</option>


                        </select>
                    </div>

                </div>

                <div class="row mt-4">
                    <div class="col-6  pb-3 clearfix">
                        <button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2   text-uppercase " id="submitCategory">Submit</button> 
                        <button type="button" class="btn btn-outline-secondary pl-4 pr-4 pt-2 pb-2 ml-2 text-uppercase reset-btn" >Reset</button></div>


                </div>

            </form>
        </div>
    </div>
</div>

@endsection