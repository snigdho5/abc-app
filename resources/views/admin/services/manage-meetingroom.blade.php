@extends('admin.layouts.app')

@section('content')


<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Centre Configuration</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
                    <table id="datatable" class="table table-striped ">
                        <thead>
                            <tr class="headings">
                                <th class="column-title"> ID</th>
                                <th class="column-title">Centre</th>                  
                                <th class="column-title">Centre Address</th>                  
                                <th class="column-title">Location</th>                             
                                <th class="column-title">Status</th>
                                <th class="column-title">Action</th>
                            </tr>
                        </thead>
                        <tbody>


                            @foreach($data as $key=>$value)

                            <?php $locnm = getLocationName($value->location); ?>
                            <tr class="pointer">
                                <td>{{$value->centre_id}}</td>
                                <td class="whiteSpace">{{$value->centre}}</td>
                                <td class="whiteSpace">{{$value->centre_address}}</td>
                                <td class="whiteSpace"><?php echo $locnm->loc_name; ?></td>
                                <td>@if($value->status ==1) Enable @else Disable @endif</td>
                                <td class="last">

                                     <a href="{{route('meetingroom.data',base64_encode($value->centre_id))}}" title="Edit" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase ">View</a> 
									 
									 @if (Auth::guard('admin')->user()->admin_role == 1) 
                                    <a href="{{route('meetingroom.delete',base64_encode($value->centre_id))}}"  title="Delete" class="d-inline-block delete confirmation"><img src="{{ URL::asset('images/delete.svg') }}" alt=""></a>
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