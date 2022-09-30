@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Tax</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
                    <table id="datatable" class="table table-striped ">
                        <thead>
                            <tr class="headings">
                                <th class="column-title">CGST Rate(in %)</th>
<!--                                <th class="column-title">CGST Amount</th>-->
                                <th class="column-title">SGST Rate(in %)</th>
                                <!--<th class="column-title">SGST Amount</th>-->
                                <th class="column-title">Status</th>
                                <th class="column-title">Action</th>
                            </tr>
                        </thead>
                        <tbody>


                            @foreach($data as $key=>$value)
                            <tr class="pointer">
                                <td>{{$value->tax_cgst_rate}}</td>

                                <td>{{$value->tax_sgst_rate}}</td>

                                 <td>@if($value->tax_status ==1) Enable @else Disable @endif</td>
                                <td class="last">
                                    <a href="javascript:void(0);" title="Edit" class="mr-4 ml-2 d-inline-block btn btn-secondary btnp text-uppercase editTax" data-id="{{base64_encode($value->tax_id)}}">View</a> 
                                    <a href="{{route('tax.delete',base64_encode($value->tax_id))}}"  title="Delete" class="d-inline-block delete confirmation"><img src="{{ URL::asset('images/delete.svg') }}" alt=""></a>
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
                <h2 id="titleText"> Add Tax</h2>
                <div class="clearfix"></div>
            </div>
            <form method="post" id="taxRegister" action="{{route('tax.register')}} "> 

                {{ csrf_field() }}
                <input type="hidden" name="tax_id" id="tax_id">
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
                    <div class="col-md-6 mb-3 padRig">
                        <label for="tax_cgst_rate">CGST Rate(in %)</label>
                        @if ($errors->has('tax_cgst_rate'))
                        <span class="help-block">
                            <strong>{{ $errors->first('tax_cgst_rate') }}</strong>
                        </span>
                        @endif
                        <input type="text" class="form-control onlyDec" autocomplete="off" id="tax_cgst_rate" name="tax_cgst_rate" placeholder="CGST Rate(in %)"   >
                    </div>
<!--                    <div class="col-md-3 mb-3 padRig">
                        <label for="tax_cgst_amt">CGST Amount</label>
                        @if ($errors->has('tax_cgst_amt'))
                        <span class="help-block">
                            <strong>{{ $errors->first('tax_cgst_amt') }}</strong>
                        </span>
                        @endif
                        <input type="text" class="form-control onlyDec" autocomplete="off" id="tax_cgst_amt" name="tax_cgst_amt" placeholder="CGST Amount"   >
                    </div>-->
                    <div class="col-md-6 mb-3 padRig">
                        <label for="tax_sgst_rate">SGST Rate(in %)</label>
                        @if ($errors->has('tax_sgst_rate'))
                        <span class="help-block">
                            <strong>{{ $errors->first('tax_sgst_rate') }}</strong>
                        </span>
                        @endif
                        <input type="text" class="form-control onlyDec" autocomplete="off" id="tax_sgst_rate" name="tax_sgst_rate" placeholder="SGST Rate(in %)"   >
                    </div>
<!--                    <div class="col-md-3 mb-3 padRig">
                        <label for="tax_sgst_amt">SGST Amount</label>
                        @if ($errors->has('tax_sgst_amt'))
                        <span class="help-block">
                            <strong>{{ $errors->first('tax_sgst_amt') }}</strong>
                        </span>
                        @endif
                        <input type="text" class="form-control onlyDec" autocomplete="off" id="tax_sgst_amt" name="tax_sgst_amt" placeholder="SGST Amount"   >
                    </div>-->
                    
                    
                </div>
                <div class="row mt-4">
                    <div class="col-md-4 mb-3 padRig">
                        <label for="status">Status</label>
                        @if ($errors->has('tax_status'))
                        <span class="help-block">
                            <strong>{{ $errors->first('tax_status') }}</strong>
                        </span>
                        @endif
                        <select  class="form-control select " id="tax_status" name="tax_status">
                            <option value="1"  >Enable</option>
                            <option value="0" >Disable</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-6  pb-3 clearfix">
                        <button type="submit" class="btn btn-secondary pl-4 pr-4 pt-2 pb-2   text-uppercase " id="submitCategory">Submit</button>
                        <button type="button" class="btn btn-outline-secondary pl-4 pr-4 pt-2 pb-2 ml-2 text-uppercase btn-reset reset-btn" id="submitCategory">Reset</button></div>
                   
                   
                </div>




            </form>
        </div>
    </div>
</div>
@endsection