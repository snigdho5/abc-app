@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Queries</h2>
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
                                <th class="column-title">Name</th>
                                <th class="column-title">Email</th>
                                <th class="column-title">Mobile</th>
                                <th class="column-title">Query</th>
                                <th class="column-title">Date</th>
                                <th class="column-title">Action</th>
                            </tr>
                        </thead>
                        <tbody>


                            @foreach($data as $key=>$value)
                            <tr class="pointer">
                                <td>{{$value->q_name}}</td>
                                <td>{{$value->q_email}}</td>
                                <td>{{$value->q_phone}}</td>
                                <td>{{$value->q_text}}</td>
                                <td>{{$value->created_at}}</td>
                                
                                <td class="last">
                                    <a href="{{route('query.delete',base64_encode($value->q_id))}}"  title="Delete" class="d-inline-block delete confirmation"><img src="{{ URL::asset('images/delete.svg') }}" alt=""></a>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

            </div>
            <div class="clearfix"></div>
            <?php if (count($data) > 0) { ?>
                <div class="row  m-0">
                    <div class="col-12 text-right pl-0 pr-0 pb-3 clearfix"><a href="{{route('query.export')}}" class="btn btn-outline-secondary pl-4 pr-4 pt-2 pb-2  m-0" id="exporttoExcel"> <img src="{{ URL::asset('images/excel.svg') }}" class="pr-2" alt="Export to Excel"> Export to Excel </a></div>

                </div>
            <?php } ?>  

        </div>
    </div>
</div>

@endsection