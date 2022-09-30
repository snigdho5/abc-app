@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Centre Location Links</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
                    <table id="datatable" class="table table-striped ">
                        <thead>
                            <tr class="headings">
                                <th class="column-title">Centre</th>
                                <th class="column-title">Mobile</th>
                                <th class="column-title">Centre Link</th>
                                <th class="column-title">Action</th>
                            </tr>
                        </thead>
                        <tbody>

<?php 
$break = 25; 

?>
                            @foreach($data as $key=>$value)
                            <tr class="pointer">
                                <td class="whiteSpace">{{getCentreName($value->centre_id)}}</td>
                                <td class="whiteSpace">{{$value->mobile}}</td>
                                <td class="whiteSpace">{{implode(PHP_EOL, str_split($value->link, $break))}}</td>
                               
                                <td class="last">
                                    <a href="{{route('centreloc.delete',base64_encode($value->id))}}"  title="Delete" class="d-inline-block delete confirmation"><img src="{{ URL::asset('images/delete.svg') }}" alt=""></a>
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
                <h2 id="titleText"> Send Centre Location Link</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="applinkRegister" action="{{route('centreloc.register')}} " enctype="multipart/form-data"> 

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
                
                <div class="row mt-4">
                    @if(Auth::guard('admin')->user()->admin_role == 1 || count($centreData) > 1)
                    <div class="col-md-6 mb-3">
                        <label for="status">Centre</label>
                        @if ($errors->has('center_id'))
                        <span class="help-block">
                            <strong>{{ $errors->first('center_id') }}</strong>
                        </span>
                        @endif
                        
                        <select  class="form-control select config-val"   id="center_id_link" name="centre_id">
                            <option value="" >Select Centre</option>
                            @foreach($centreData as $key=>$value)
<?php $locnm = getLocationName($value->location); ?>
                            <option value="{{$value->centre_id}}" >{{$value->centre}} (<?php echo $locnm->loc_name; ?>)</option>
                            @endforeach
                        </select>
                    </div>
                    
                    @else 
                    <input type="hidden" name="centre_id" value="{{$centreData[0]['centre_id']}}" >
                    @endif
                    <div class="col-md-6 mb-3 padRig">
                        <label for="locationName">Mobile</label>
                        @if ($errors->has('mobile'))
                        <span class="help-block">
                            <strong>{{ $errors->first('mobile') }}</strong>
                        </span>
                        @endif
                        <input type="text" class="form-control onlyNum" autocomplete="off" id="mobile" name="mobile" placeholder=" Mobile Number"  maxlength="10" minlength="10" required>
                    </div>
                    
                    
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-12 mb-3 padRig">
                        <label for="locationName">Centre Location Link</label>
                        @if ($errors->has('link'))
                        <span class="help-block">
                            <strong>{{ $errors->first('link') }}</strong>
                        </span>
                        @endif
						<?php
							$linksend = '';
							if(count($centreData) == 1){
								$linksend = $centreData[0]['centre_url'];
							}
						?>
                        <textarea class="form-control " autocomplete="off" id="link" name="link" placeholder="Centre Link" rows="4" required=""><?php if($linksend!=''){echo $linksend ;}?></textarea>

                    </div>
                    
                </div>
<div class="row mt-4">
                	<div class="col-6  pb-3 clearfix">
                    	<button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2   text-uppercase " id="submitCategory">Send</button>
                    	<button type="button" class="btn btn-outline-secondary pl-4 pr-4 pt-2 pb-2 ml-2 text-uppercase btn-reset reset-btn" id="submitCategory">Reset</button></div>
  </div>


            </form>
        </div>
    </div>
</div>
@endsection