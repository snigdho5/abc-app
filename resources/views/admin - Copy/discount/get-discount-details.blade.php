@extends('admin.layouts.app')

@section('content')


<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel row m-0">
            <div class="x_title">
                <h2>Manage Discount</h2>
                <div class="clearfix"></div>
            </div>

            <div class="x_content pt-4 mb-3 col-12 clearfix">
                <div class="table-responsive-md collRquest pb-4">
                    <table id="datatable" class="table table-striped ">
                        <thead>
                            <tr class="headings">
                                <th class="column-title">Promo Code</th>
                                <th class="column-title">Customer</th>
                                <th class="column-title">Category</th>
                                <th class="column-title">Discount(%)</th>
                                <th class="column-title">Max Offer Amount</th>
                                <th class="column-title">Min Order Amount</th>
                                <th class="column-title">Max Consumed Amount</th>
                                <th class="column-title">Action</th>
                            </tr>
                        </thead>
                        <tbody>


                            @foreach($data as $key=>$value)
                            <tr class="pointer">
                                <td class="whiteSpace">{{$value->d_code}}</td>
                                <td class="whiteSpace"><a href="{{route('discount.detail',base64_encode($value->d_id))}}">@if($value->d_cust_type ==1) All Customer @elseif($value->d_cust_type ==2) Selected Customer @else New Customers @endif</a></td>
                                <td class="whiteSpace">
                                    <?php
                                    $catStr = '';
                                    $catArr = explode(',', $value->d_cat);
                                    foreach ($catArr as $k => $v) {
                                        if ($v) {
                                            $catStr .= '<strong>' . getCatName($v) . "</strong>, ";
                                        }else if($v==0){
                                            $catStr .= "<strong> All Categories</strong>, ";
                                        }
                                    }
                                    echo rtrim($catStr, ' ,');
                                    ?>
                                </td>
                                <td class="whiteSpace">{{$value->d_amnt}}</td>
                                <td class="whiteSpace">{{$value->d_max_ofr_amnt}}</td>
                                <td class="whiteSpace">{{$value->d_min_ordr_amnt}}</td>
                                <td class="whiteSpace">{{$value->d_max_consumed}}</td>

                                <td class="whiteSpace">
                                    @if($value->d_status ==1) Available @else Not Available @endif
                                </td>
                                <td class="last">
                                    <a href="{{route('discount.delete',base64_encode($value->d_id))}}"  title="Delete" class="d-inline-block delete confirmation"><img src="{{ URL::asset('images/delete.svg') }}" alt=""></a>
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
                <h2 id="titleText" >Discount Details</h2>
                <div class="clearfix"></div>
            </div>
            <div class="row mt-4" id="amt_opt" >
                <div class="col">
                    <table id="datatable1" class="table table-striped ">
                        <thead>
                            <tr class="headings">
                                <th class="column-title">Promo Code</th>
                                <th class="column-title">Mobile</th>
                                <th class="column-title">Discount(%)</th>
                                <th class="column-title">Max Offer Amount</th>
                                <th class="column-title">Min Order Amount</th>
                                <th class="column-title">Max Consumed Amount</th>
                                <!--<th class="column-title">Action</th>-->
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($discountDetails as $key=>$value)
                            <tr class="pointer">
                                <td class=" ">{{$discountData['d_code']}}</td>
                                <td class=" ">{{$value->dc_custmob}}</td>
                                <td class=" ">{{$discountData['d_amnt']}}</td>
                                <td class=" ">{{$discountData['d_max_ofr_amnt']}}</td>
                                <td class=" ">{{$discountData['d_min_ordr_amnt']}}</td>
                                <td class=" ">{{$value->dc_max_consumed}}</td>
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