@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Service Categories</h2>
		
		        <a href="{{route('meetingroom.managecenterconfig')}}" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase pull-right" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a> 
		
                <div class="clearfix"></div>
            </div>

            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
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
                    <table id="datatable" class="table table-striped ">
                        <thead>
                            <tr class="headings">
                                <th class="column-title">Service Category</th>
                                <th class="column-title">Status</th>
				 <th class="column-title">Action</th>
                            </tr>
                        </thead>
                        <tbody>


                            @foreach($data as $key=>$value)
                            <tr class="pointer">
                                <td>{{getCatName($value->cat_id)}}</td>
				   <td>@if($value->tstatus ==1) Enable @else Disable @endif</td>
				  <td class="last">
                                        @if($value->tstatus==1)
                                        <a href="{{route('center-service.changestatus',base64_encode($value->id))}}"  class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase" >Disable</a> 
                                        @else
                                        <a href="{{route('center-service.changestatus',base64_encode($value->id))}}" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase" >Enable</a> 
                                        @endif
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
@endsection