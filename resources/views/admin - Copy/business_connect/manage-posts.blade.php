@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Posts</h2>
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
                                <th class="column-title">Post content</th>
                                <th class="column-title">User name</th>
                                <th class="column-title">User mobile</th>
                                <th class="column-title">User email</th>
                                <th class="column-title">Published date</th>
                                <th class="column-title">Status</th>
                                <th class="column-title">Action</th>
                            </tr>
                        </thead>
                        <tbody>


                            @foreach($data as $key=>$value)
                            <tr class="pointer">
                                <td>{{$value->post_content}}</td>
                                <td>{{$value->user_name}}</td>
                                <td>{{$value->user_mobile}}</td>
                                <td>{{$value->user_email}}</td>
                                <td>{{$value->published_date}}</td>
                                <td>@if($value->post_status ==1) Published @else Unpublished @endif</td>
                                <td class="last">
                                    <a href="javascript:void(0);" title="Edit" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase editPost" data-id="{{base64_encode($value->post_id)}}">View</a> 
                                    <a href="{{route('post.delete',base64_encode($value->post_id))}}"  title="Delete" class="d-inline-block delete confirmation"><img src="{{ URL::asset('images/delete.svg') }}" alt=""></a>
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

<div class="row pt-3 postDetail" style="display: none;">
    <div class="col-lg-12 col-md-12 col-12">
        <div class="x_panel newRequestForm">
            <div class="x_title">
                <h2 id="titleText"> Edit Post</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="editPosts" action="{{route('post.edit')}} "> 

                {{ csrf_field() }}
                <input type="hidden" name="post_id" id="post_id">
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
                    <div class="col-md-12 mb-3 padRig">
                        <label for="locationName">Post content</label>
                        @if ($errors->has('post_content'))
                        <span class="help-block">
                            <strong>{{ $errors->first('post_content') }}</strong>
                        </span>
                        @endif
                        <input class="form-control " autocomplete="off" id="post_content" name="post_content" placeholder="" >
                    </div>


                </div>

                <div class="row mt-4">
                    <div class="col-md-4 mb-3 padRig">
                        <label for="locationName">Customer name</label>
                        @if ($errors->has('user_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('user_name') }}</strong>
                        </span>
                        @endif
                        <input type="text" class="form-control" id="user_name" readonly>

                    </div>
                    <div class="col-md-4 mb-3 padRig">
                        <label for="locationName">Customer mobile</label>
                        @if ($errors->has('user_mobile'))
                        <span class="help-block">
                            <strong>{{ $errors->first('user_mobile') }}</strong>
                        </span>
                        @endif
                        <input type="text" class="form-control" id="user_mobile" readonly>

                    </div>
                    <div class="col-md-4 mb-3 padRig">
                        <label for="locationName">Customer email</label>
                        @if ($errors->has('user_email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('user_email') }}</strong>
                        </span>
                        @endif
                        <input type="text" class="form-control" id="user_email" readonly>

                    </div>

                </div>
                <div class="row mt-4">
                    <div class="col-md-4 mb-3 padRig">
                        <label for="locationName">Submitted on</label>
                        @if ($errors->has('published_date'))
                        <span class="help-block">
                            <strong>{{ $errors->first('published_date') }}</strong>
                        </span>
                        @endif
                        <input type="text" class="form-control" id="published_date" readonly>

                    </div>
                    <div class="col-md-4 mb-3 padRig">
                        <label for="status">Status</label>
                        @if ($errors->has('post_status'))
                        <span class="help-block">
                            <strong>{{ $errors->first('post_status') }}</strong>
                        </span>
                        @endif
                        <select  class="form-control select " id="post_status" name="post_status">
                            <option value="1"  >Published</option>
                            <option value="0" >UnPublished</option>
                        </select>
                    </div>
                </div>
                <div id="imagePage"></div>
                <div class="row mt-4">
                    <div class="col-6  pb-3 clearfix">
                        <button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2   text-uppercase " id="submitPost">Submit</button> 
                        <button type="button" class="btn btn-outline-secondary pl-4 pr-4 pt-2 pb-2 ml-2 text-uppercase btn-reset reset-btn">Reset</button></div>


                </div>



            </form>
        </div>
    </div>
</div>
@endsection