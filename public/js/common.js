
$(document).ready(function () {
    var url = window.location.href;
    if (url.includes("get-booking-info") || url.includes("get-meetingroom-info/")) {
        scrollToCustomerForm();
    }
    var timeArr = [];
//    $('#n_centre_id').selectpicker();
    $('#customers').attr('disabled', true);
    $('.selcAttr').hide();
    $('#addcust').hide();
    $('.custDetail').hide();
    $('#cust_dob').datepicker({

        dateFormat: "yy-mm-dd",
//        yearRange: "-100:+0",
        yearRange: "1950:2010",
        defaultDate: new Date(1990, 00, 01),
        changeYear: true,

    });

    $('.date-picker').datepicker({
        minDate: 0,
        dateFormat: "yy-mm-dd",
        onSelect: function (dateText) {
            var timefromhtml = '';
            var postData = {'date': moment(this.value).format("MMMM D, YYYY")};
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                contentType: 'application/json',
                url: apiUrl + 'public/index.php/abc/populateToTime',
                dataType: "json",
                data: JSON.stringify(postData),
                success: function (data) {

                    if (data.curr_time_arr.length > 0) {
                        if (data.curr_time_arr.length > 0) {
                            timeArr = data.curr_time_arr;
                            timefromhtml += '<option> Select Time From</option>';
                            for (var i = 0; i < data.curr_time_arr.length; i++) {
                                timefromhtml += '<option val="' + data.curr_time_arr[i] + '">' + data.curr_time_arr[i] + ' </option>';
                            }
                            $('#time-from').html(timefromhtml);
                        }
                    }
                }, error: function (request, error) {

                    alert("Error while fetching  data");
                }
            });
        }
    });


    try {
        CKEDITOR.replace('ctext_inf');

    } catch (err) {
    }

    try {
        CKEDITOR.replace('offer_text');
    } catch (err) {
    }
    try {
        CKEDITOR.replace('intro_text');
    } catch (err) {
    }
    try {
        CKEDITOR.replace('vt_title');
    } catch (err) {
    }
    try {
        CKEDITOR.replace('vt_subtitle');
    } catch (err) {
    }
    if ("undefined" != typeof $.fn.daterangepicker) {
        console.log("init_daterangepicker2");
        var a = function (a, b, c) {
            console.log(a.toISOString(), b.toISOString(), c), $("#reportrange1 span").html(a.format("MMMM D, YYYY") + " - " + b.format("MMMM D, YYYY"))
        },
                b = {
                    startDate: moment().subtract(29, "days"),
                    endDate: moment(),
                    dateLimit: {
                        days: 180
                    },
                    showDropdowns: !0,
                    showWeekNumbers: !0,
                    timePicker: !1,
                    timePickerIncrement: 1,
                    timePicker12Hour: !0,
                    ranges: {
                        // Today: [moment(), moment()],
                        // Yesterday: [moment().subtract(1, "days"), moment().subtract(1, "days")],
                        // "Last 7 Days": [moment().subtract(6, "days"), moment()],
                        // "Last 30 Days": [moment().subtract(29, "days"), moment()],
                        // "This Month": [moment().startOf("month"), moment().endOf("month")],
                        // "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
                    },
                    opens: "left",
                    buttonClasses: ["btn btn-default"],
                    applyClass: "btn-small btn-primary",
                    cancelClass: "btn-small",
                    format: "MM/DD/YYYY",
                    separator: " to ",
                    locale: {
                        applyLabel: "Submit",
                        cancelLabel: "Clear",
                        fromLabel: "From",
                        toLabel: "To",
                        customRangeLabel: "Custom",
                        daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
                        monthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                        firstDay: 1
                    }
                };
        var range = '';
        var firstday = '';
        var lastday = '';
        //console.log($('#req_date_range').val());
        if ($('#req_date_range').val() != undefined && $('#req_date_range').val() != '') {
            range = $('#req_date_range').val().split('and');
            firstday = moment(range[0]).format("MMMM D, YYYY");
            lastday = moment(range[1]).format("MMMM D, YYYY");
        } else {
            firstday = moment().subtract(29, "days").format("MMMM D, YYYY");
            lastday = moment().format("MMMM D, YYYY");
        }
        /*if ($('#book_date_range').val() != undefined && $('#book_date_range').val() != '') {
         range = $('#req_date_range').val().split('and');
         firstday = moment(range[0]).format("MMMM D, YYYY");
         lastday = moment(range[1]).format("MMMM D, YYYY");
         } else {
         firstday = moment().subtract(29, "days").format("MMMM D, YYYY");
         lastday = moment().format("MMMM D, YYYY");
         }*/

        $("#reportrange1 span").html(
                firstday + " - " + lastday),
                $("#reportrange1").daterangepicker(b, a), $("#reportrange1").on("show.daterangepicker", function () {
            console.log("show event fired")
        }), $("#reportrange1").on("hide.daterangepicker", function () {
            console.log("hide event fired")
        }), $("#reportrange1").on("apply.daterangepicker", function (a, b) {

            $('#req_date_range').val(b.startDate.format("YYYY-MM-DD") + 'and' + b.endDate.format("YYYY-MM-DD"));

            $('#book_date_range').val(b.startDate.format("MMMM D, YYYY") + '-' + b.endDate.format("MMMM D, YYYY"));

            console.log("apply event fired, start/end dates are " + b.startDate.format("YYYY-MM-DD") + " to " + b.endDate.format("YYYY-MM-DD"));
            getTimeFromBooking(b.startDate.format("MMMM D, YYYY"), b.endDate.format("MMMM D, YYYY"));
        }), $("#reportrange").on("cancel.daterangepicker", function (a, b) {
            console.log("cancel event fired")
        }), $("#options1").click(function () {
            $("#reportrange").data("daterangepicker").setOptions(b, a)
        }), $("#options2").click(function () {
            $("#reportrange").data("daterangepicker").setOptions(optionSet2, a)
        }), $("#destroy").click(function () {
            $("#reportrange").data("daterangepicker").remove()
        })
    }


    // book range
    if ("undefined" != typeof $.fn.daterangepicker) {
        console.log("init_daterangepicker2");
        var date = new Date();
        date.setDate(date.getDate() - 1);
        var a = function (a, b, c) {
            console.log(a.toISOString(), b.toISOString(), c), $("#reportrange2 span").html(a.format("MMMM D, YYYY ") + " - " + b.format("MMMM D, YYYY "))
        },
                b = {
                    startDate: date,
                    endDate: moment(),
                    showDropdowns: !0,
                    showWeekNumbers: !0,
                    timePicker: !1,
                    timePickerIncrement: 1,
                    timePicker12Hour: !0,
                    ranges: {
                        // Today: [moment(), moment()],
                        // Yesterday: [moment().subtract(1, "days"), moment().subtract(1, "days")],
                        // "Last 7 Days": [moment().subtract(6, "days"), moment()],
                        // "Last 30 Days": [moment().subtract(29, "days"), moment()],
                        // "This Month": [moment().startOf("month"), moment().endOf("month")],
                        // "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
                    },
                    opens: "left",
                    buttonClasses: ["btn btn-default"],
                    applyClass: "btn-small btn-primary",
                    cancelClass: "btn-small",
                    format: "MM/DD/YYYY",
                    separator: " to ",
                    locale: {
                        applyLabel: "Submit",
                        cancelLabel: "Clear",
                        fromLabel: "From",
                        toLabel: "To",
                        customRangeLabel: "Custom",
                        daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
                        monthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                        firstDay: 1
                    }
                };
        var range = '';
        var firstday = '';
        var lastday = '';
        //console.log($('#req_date_range').val());
        if ($('#book_date_range').val() != undefined && $('#book_date_range').val() != '') {
            range = $('#book_date_range').val().split('and');
            firstday = moment(range[0]).format("MMMM D, YYYY ");
            lastday = moment(range[1]).format("MMMM D, YYYY ");
        } else {
            firstday = moment().format("MMMM D, YYYY ");
            lastday = moment().format("MMMM D, YYYY ");
        }
        $("#reportrange2 span").html(
                firstday + " - " + lastday),
                $("#reportrange2").daterangepicker(b, a), $("#reportrange2").on("show.daterangepicker", function () {
            console.log("show event fired")
        }), $("#reportrange2").on("hide.daterangepicker", function () {
            console.log("hide event fired")
        }), $("#reportrange2").on("apply.daterangepicker", function (a, b) {

            $('#ofr_date_range').val(b.startDate.format("YYYY-MM-DD") + 'and' + b.endDate.format("YYYY-MM-DD"));

            console.log("apply event fired, start/end dates are " + b.startDate.format("YYYY-MM-DD hh:mm A") + " to " + b.endDate.format("YYYY-MM-DD hh:mm A"));



        }), $("#reportrange2").on("cancel.daterangepicker", function (a, b) {
            console.log("cancel event fired")
        }), $("#options1").click(function () {
            $("#reportrange2").data("daterangepicker").setOptions(b, a)
        }), $("#options2").click(function () {
            $("#date_from").data("daterangepicker").setOptions(optionSet2, a)
        }), $("#destroy").click(function () {
            $("#reportrange2").data("daterangepicker").remove()
        })
    }


    // get time from booking api

    function getTimeFromBooking(from_date, to_date) {
        var postData = {'date': from_date + '-' + to_date};
        $('#time-to').html('<option>Select time to</option>');
        var timefromhtml = '';
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            contentType: 'application/json',
            url: apiUrl + 'public/index.php/abc/populateToTime',
            dataType: "json",
            data: JSON.stringify(postData),
            success: function (data) {

                if (data.curr_time_arr.length > 0) {
                    if (data.curr_time_arr.length > 0) {
                        timeArr = data.curr_time_arr;
                        timefromhtml += '<option> Select Time From</option>';
                        for (var i = 0; i < data.curr_time_arr.length; i++) {
                            timefromhtml += '<option val="' + data.curr_time_arr[i] + '">' + data.curr_time_arr[i] + ' </option>';
                        }
                        $('#time-from').html(timefromhtml);
                    }
                }
            }, error: function (request, error) {

                //alert("Error while fetching  data");
            }
        });
    }

    $(document).on('change', '#time-from', function () {
        var postData = {'from_time': $('#time-from').val()};
        $('#time-to').html('<option>Select time to</option>');
        var timefromhtml = '';
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            contentType: 'application/json',
            url: apiUrl + 'public/index.php/abc/populateToTimeFromTime',
            dataType: "json",
            data: JSON.stringify(postData),
            success: function (data) {

                if (data.curr_time_arr.length > 0) {
                    timeArr = data.curr_time_arr;
                    var time_from = $('#time-from').val();
                    for (var i = 0; i < timeArr.length; i++) {
                        if (timeArr[i] != time_from) {
                            timeArr.splice(i, 1);
                        }
                        if (timeArr[i] == time_from) {
                            timeArr.splice(i, 1);
                            break;
                        }
                    }
                    timefromhtml += '<option> Select Time to</option>';
                    for (var i = 0; i < timeArr.length; i++) {
                        timefromhtml += '<option val="' + timeArr[i] + '">' + timeArr[i] + ' </option>';
                    }
                    $('#time-to').html(timefromhtml);
                }
            }, error: function (request, error) {

                alert("Error while fetching  data");
            }
        });
    })


    $(document).on('change', '#ser_config', function () {

        if ($(this).val() !== '3') {
            $('.months-duration').hide();
        } else if ($(this).val() === '3') {
            //$('.months-duration').show();
        }
    });
    $(document).on('change', '#addons-select', function () {
        if ($(this).val() == 'Select Ad on') {
            $('.addoncheck').hide();
            $('#addonselect').val('0');
        } else {
            $('.addoncheck').show();
            $('#addonselect').val('1');

        }
    });
    $(document).on('change', '#addons-time-interval-select', function () {
        if ($(this).val() != 'by_hr') {
            $('.by-hr-val-select').hide();
            $('#addonhrselect').val('0');
        } else {
            $('.by-hr-val-select').show();
            $('#addonhrselect').val('1');
        }
    });





    $('input:radio[name="customRadio"]').change(
            function () {
                if ($(this).val() == 'allCust') {
                    $('#customers').attr('readonly', true);
                } else {
                    $('#customers').attr('readonly', false);
                }
            });
    setTimeout(function () {
        $('.alert').fadeOut('fast');
    }, 40000);
    if ($('#ajax_request_id').val() != '' && $('#ajax_request_id').val() != undefined) {
        getRequestData($('#ajax_request_id').val());
    } else {

    }
    try {
        CKEDITOR.replace('ser_details');
    } catch (err) {
    }
    try {
        CKEDITOR.replace('detail');
    } catch (err) {
    }
    $('#pageRegister').each(function () {
        if ($(this).data('validator'))
            $(this).data('validator').settings.ignore = ".note-editor *";
    });
    $('.confirmation').on('click', function () {
        return confirm('Are you sure?');
    });

    if (window.location.hash == "#AddForm") {
        $('html, body').animate({
            scrollTop: $(".newRequestForm").offset().top
        }, 2000);
    }


    $(document).on('click', '.editCategory', function () {
        $('#titleText').html('Edit Category');
        $('#submitCategory').html('Edit Category');
        $('#categoryRegister').attr('action', GlblURLs + "admin/edit-category");
        var cat_id = $(this).data('id');
        scrollToCustomerForm();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
		
        $.ajax({
            url: GlblURLs + 'admin/edit-category-ajax',
            type: 'POST',
            data: {
                'id': cat_id,
                '_token': $('meta[name="csrf_token"]').attr('content')
            },
            dataType: 'JSON',
            success: function (data) {
				$('.serviceCat').show();
                $('#acat_id').val(data['acat_id']);
                $('#acat_name').val(data['acat_name']);
                $('#acat_type').val(data['acat_type']);
                $('#acat_status').val(data['acat_status']);
                $("#acat_intro").val(data['acat_intro']);
                $("#acat_per_type").val(data['acat_per_type'].split(','));
                $("#acat_addons").val(data['acat_addons']);
                $("#acat_detail").val(data['acat_detail']);
                if (data['flag_hour'] == 1) {
                    $('#flag_hour').prop('checked', true);
                }
                if (data['flag_month'] == 1) {
                    $('#flag_month').prop('checked', true);
                }
                if (data['flag_year'] == 1) {
                    $('#flag_year').prop('checked', true);
                }
                if (data['flag_halfday'] == 1) {
                    $('#flag_halfday').prop('checked', true);
                }
                if (data['flag_fullday'] == 1) {
                    $('#flag_fullday').prop('checked', true);
                }
                if (data['flag_quart'] == 1) {
                    $('#flag_quart').prop('checked', true);
                }
                if (data['flag_halfyear'] == 1) {
                    $('#flag_halfyear').prop('checked', true);
                } 
				if (data['business_address'] == 1) {
                    $('#business_address').prop('checked', true);
                }if (data['high_internet'] == 1) {
                    $('#high_internet').prop('checked', true);
                }if (data['it_infra'] == 1) {
                    $('#it_infra').prop('checked', true);
                }if (data['parking_zone'] == 1) {
                    $('#parking_zone').prop('checked', true);
                }if (data['twentyfour_access'] == 1) {
                    $('#twentyfour_access').prop('checked', true);
                }if (data['event_activity'] == 1) {
                    $('#event_activity').prop('checked', true);
                }

            },
            error: function (request, error)
            {
                alert("An error occurred while editing the category");
            }
        });
    });



    $(document).on('click', '.editLocation', function () {
        $('#titleText').html('Edit Location');
        $('#submitLocation').html('Edit Location');
        $('#locationRegister').attr('action', GlblURLs + "admin/edit-location");
        var cat_id = $(this).data('id');
        scrollToCustomerForm();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: GlblURLs + 'admin/edit-location-ajax',
            type: 'POST',
            data: {
                'id': cat_id,
                '_token': $('meta[name="csrf_token"]').attr('content')
            },
            dataType: 'JSON',
            success: function (data) {
                $('#loc_id').val(data['id']);
                $('#locationId').val(data['location_id']);
                $('#locationName').val(data['name']);
                $("#cstatus").val(data['status']);
                $('#imagePage').html('<img style="height:100px;width:150px;" src="' + imgUrl + 'upload/location/' + data['loc_img'] + '">');
            },
            error: function (request, error)
            {
                alert("Error while editing the location");
            }
        });
    });









    $(document).on('click', '.editCentre', function () {
        $('#titleText').html('Edit Centre');
        $('#submitCentre').html('Edit Centre');
        $('#centreRegister').attr('action', GlblURLs + "admin/edit-centre");
        var c_id = $(this).data('id');
        scrollToCustomerForm();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: GlblURLs + 'admin/edit-centre-ajax',
            type: 'POST',
            data: {
                'id': c_id,
                '_token': $('meta[name="csrf_token"]').attr('content')
            },
            dataType: 'JSON',
            success: function (data) {
                $('#centre_id').val(data['centre_id']);
                $('#location').val(data['location']);
                $('#centre').val(data['centre']);
                $('#centre_address').val(data['centre_address']);
                $('#centre_email').val(data['centre_email']);
                $('#centre_mobile').val(data['centre_mobile']);
                $('#centre_phone').val(data['centre_phone']);
                $('#centre_url').val(data['centre_url']);
                $('#centre_content').val(data['centre_content']);
                if (data['flag_abc_lounge'] != '') {
                    $('#flag_abc_lounge').prop('checked', true);
                }
                if (data['flag_built_to_suit'] != '') {
                    $('#flag_built_to_suit').prop('checked', true);
                }
                if (data['flag_virtual_office'] != '') {
                    $('#flag_virtual_office').prop('checked', true);
                }
                if (data['flag_ser_office'] != '') {
                    $('#flag_ser_office').prop('checked', true);
                }
                if (data['flag_co_working'] != '') {
                    $('#flag_co_working').prop('checked', true);
                }
                if (data['flag_meeting_room'] != '') {
                    $('#flag_meeting_room').prop('checked', true);
                }
                if (data['flag_payment'] == 1) {
                    $('#flag_payment_online').prop('checked', true);
                } else if (data['flag_payment'] == 2) {
                    $('#flag_payment_offline').prop('checked', true);
                } else if (data['flag_payment'] == 3) {
                    $('#flag_payment_offline').prop('checked', true);
                    $('#flag_payment_online').prop('checked', true);
                }
                //$("#page_detail").summernote("code", data['detail']);

                $('#imagePage').html('<img style="height:100px;width:150px;" src="' + imgUrl + 'upload/centre/' + data['centre_image'] + '">');
                $('#menuimagePage').html('<a class="d-block mb-2" target="_blank" href="' + imgUrl + 'upload/centre/' + data['centre_menu'] + '" ><img style="height:100px;width:150px;" src="' + imgUrl + 'upload/centre/' + data['centre_menu'] + '"></a>');
                var galImg = data['centre_gallery'].split(',');
                var html = '<div class="row mt-4"><div class="col mb-3"> <label for="gal">Gallery Images</label>';

                if (galImg.length > 0) {
                    for (var i = 0; i < galImg.length; i++) {
                        html += '<a class="d-block mb-2" target="_blank" href="' + imgUrl + 'upload/centre/' + galImg[i] + '" ><img style="height:50px;" src="' + imgUrl + 'upload/centre/' + galImg[i] + '"></a>';
                    }
                    html += '</div></div>';
                }
                $('#imagePage').append(html);
                $('#centre_vtlink').val(data['centre_vtlink']);
                $('#centre_lat').val(data['centre_lat']);
                $('#centre_long').val(data['centre_long']);
                $('#status').val(data['status']);
            },
            error: function (request, error)
            {
                alert("An error occurred while editing the centre");
            }
        });
    });


    $(document).on('click', '.editMsinfo', function () {
        $('#titleText').html('Edit Master Info');
        $('#submitMsinfo').html('Edit Master Info');
        $('#msinfoRegister').attr('action', GlblURLs + "admin/edit-msinfo");
        var ms_id = $(this).data('id');
        scrollToCustomerForm();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: GlblURLs + 'admin/edit-msinfo-ajax',
            type: 'POST',
            data: {
                'id': ms_id,
                '_token': $('meta[name="csrf_token"]').attr('content')
            },
            dataType: 'JSON',
            success: function (data) {
                $('#ms_id').val(data['ms_id']);
                $('#ms_cat').val(data['ms_cat']);
                check_serflag(data['ms_cat']);
                $('#ms_name').val(data['ms_name']);
                $('#ms_type').val(data['ms_type']);
                $('#ms_hour').val(data['ms_hour']);
                $('#ms_half').val(data['ms_half']);
                $('#ms_full').val(data['ms_full']);
                $('#ms_month').val(data['ms_month']);
                $('#ms_year').val(data['ms_year']);
                $('#ms_hy').val(data['ms_hy']);
                $('#ms_quart').val(data['ms_quart']);
                $("#ms_status").val(data['ms_status']);
            },
            error: function (request, error)
            {
                alert("Error while editing the master info");
            }
        });
    });

    $(document).on('click', '.editVOPackage', function () {
        $('#titleText').html('Edit Virtual Office Package');
        $('#vopRegister').attr('action', GlblURLs + "admin/edit-vopackage");
        var ms_id = $(this).data('id');
        scrollToCustomerForm();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: GlblURLs + 'admin/edit-vopackage-ajax',
            type: 'POST',
            data: {
                'id': ms_id,
                '_token': $('meta[name="csrf_token"]').attr('content')
            },
            dataType: 'JSON',
            success: function (data) {
                $('#center_id').val(data['center_id']);
                $('#ms_cat').val(data['ms_cat']);
                $('#ms_pln_quart').val(data['ms_pln_quart']);
                $('#ms_pln_hy').val(data['ms_pln_hy']);
                $('#ms_pln_yr').val(data['ms_pln_yr']);
                $('#activation_fee').val(data['activation_fee']);
                $('#security_deposit').val(data['security_deposit']);
                $('#ms_status').val(data['ms_status']);
                $('#rr_id').val(data['rr_id']);
            },
            error: function (request, error)
            {
                alert("Error ");
            }
        });
    });

    $(document).on('click', '.editTax', function () {
        $('#titleText').html('Edit Tax');
        $('#taxRegister').attr('action', GlblURLs + "admin/edit-tax");
        var tax_id = $(this).data('id');
        scrollToCustomerForm();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: GlblURLs + 'admin/edit-tax-ajax',
            type: 'POST',
            data: {
                'id': tax_id,
                '_token': $('meta[name="csrf_token"]').attr('content')
            },
            dataType: 'JSON',
            success: function (data) {
                $('#tax_cgst_rate').val(data['tax_cgst_rate']);
                //$('#tax_cgst_amt').val(data['tax_cgst_amt']);
                $('#tax_sgst_rate').val(data['tax_sgst_rate']);
                //$('#tax_sgst_amt').val(data['tax_sgst_amt']);
                $('#tax_id').val(data['tax_id']);
                $('#tax_status').val(data['tax_status']);
            },
            error: function (request, error)
            {
                alert("Error ");
            }
        });
    });


    $(document).on('click', '.editCustomer', function () {
        $('#titleText').html('Edit Customer');
        $('#submitCust').html('Edit Customer');
        // $('.sendpwdradio').show();
        $('#requestMobile,#requestEmailID').attr('readonly', true);
        $('#custRegister').attr('action', GlblURLs + "admin/edit-customer");
        var customer_id = $(this).data('id');
        scrollToCustomerForm();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: GlblURLs + 'admin/edit-customer-ajax',
            type: 'POST',
            data: {
                'id': customer_id,
                '_token': $('meta[name="csrf_token"]').attr('content')
            },
            dataType: 'JSON',
            success: function (data) {
                $('#cust_id').val(data['id']);
                $('#requestName').val(data['name']);
                $('#requestEmailID').val(data['email']);
                $('#requestMobile').val(data['mobile']);
                $('#requestLocation').val(data['location']);
                $('#requestAddress').val(data['service_add1']);
                $('#requestAddress2').val(data['service_add2']);
                $('#cust_landmark').val(data['cust_landmark']);
                $("#cust_dob").datepicker('setDate', data['cust_dob']);
                $('#cust_comp').val(data['cust_comp']);
                $('#cust_desig').val(data['cust_desig']);
                $('#cust_pin').val(data['cust_pin']);
                $("#cstatus").val(data['status']);
            },
            error: function (request, error)
            {
                alert("Error while editing the customer");
            }
        });
    });

    $('.reset-btn').click(function () {
        $('form').trigger("reset");
    });

});



function scrollToCustomerForm() {

    //llocation.reload();

    $('html, body').animate({
        scrollTop: $(".newRequestForm").offset().top
    }, 500);
}
// function reloadloc(){
//     location.reload();
// }

// function scrollToCustomerFormadd(){
//  $("#custRegister")[0].reset();
////$('#custRegister').trigger("reset");
//$(".hiddenclass").val('');
//    $('html, body').animate({
//        scrollTop: $(".newRequestForm").offset().top
//    }, 2000);
//}







function commaSeparateNumber(val) {
    while (/(\d+)(\d{3})/.test(val)) {
        val = val.toString().replace(/(\d+)(\d{3})/, '$1' + ',' + '$2');
    }
    return val;
}


function formatDate(date, flag) {
    var todayDate = new Date(date);
    var hour = todayDate.getHours();
    var min = todayDate.getMinutes();
    var word = '';
    const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "June",
        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
    ];
    if (hour > 12) {
        hour = hour - 12;
    }
    if (hour == 0) {
        hour = 12;
    }
    if (min < 10) {
        min = "0" + min;
    }
    if (todayDate.getDate() == 1) {
        word = 'st';
    } else if (todayDate.getDate() == 2) {
        word = 'nd';
    } else if (todayDate.getDate() == 3) {
        word = 'rd';
    } else {
        word = 'th';
    }
    if (flag) {
        return todayDate.getDate() + word + ' ' + monthNames[todayDate.getMonth() ] + " " + todayDate.getFullYear() + ' | ' + hour + ":" + min + " ";
    } else {
        return todayDate.getDate() + word + ' ' + monthNames[todayDate.getMonth() ] + " " + todayDate.getFullYear() + " ";
    }


}

$(function () {
    $("#datepicker").datepicker({
        minDate: 0,
        dateFormat: "yy-mm-dd",
        onSelect: function () {
            var selected = $(this).val();
            $('#selectedDate').val(selected);
        }
    });
});



function isEmpty(str) {
    return (!str || 0 === str.length);
}


function redirectToService() {
    window.location.href = GlblURLs + 'admin/view-services/1';
}



function check_space(str)
{
    str = str.replace(/^\s+|\s+$/g, '');
    return str;
}
$(document).on('click', '.editMeetingroom', function () {
    $('#titleText').html('Edit Meeting room');
    $('#submitCentre').html('Edit Meeting room');
    $('#centreRegister').attr('action', GlblURLs + "admin/edit-meetingroom");
    var c_id = $(this).data('id');
    scrollToCustomerForm();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: GlblURLs + 'admin/edit-meetingroom-ajax',
        type: 'POST',
        data: {
            'id': c_id,
            '_token': $('meta[name="csrf_token"]').attr('content')
        },
        dataType: 'JSON',
        success: function (data) {
            $('.hideConfig').hide();
            $('.configdiv').show();
            $('.config-val').attr('readonly', true);
            $('#centre_id').val(data['centre_id']);
            $('#location').val(data['location']);
            $('#centre').val(data['centre']);
            $('#centre_address').val(data['centre_address']);
            $('#centre_email').val(data['centre_email']);
            $('#centre_mobile').val(data['centre_mobile']);
            $('#centre_phone').val(data['centre_phone']);
            $('#status').val(data['status']);
        },
        error: function (request, error)
        {
            alert("An error occurred while editing the centre");
        }
    });
});


$(document).on('click', '.addConfig', function (e) {
    e.preventDefault();

    var cid = $(this).data('id');
    var cname = $(this).data('name');
    var centreid = $(this).data('centerid');
    var configids = $('#configIds').val();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: GlblURLs + 'admin/getconfig-data-ajax',
        type: 'POST',
        data: {
            'id': cid,
            'centreid': centreid,
            'configids': configids,
            '_token': $('meta[name="csrf_token"]').attr('content')
        },
        beforeSend: function () {
            // setting a timeout
            $("#loader").show();
        },
        dataType: 'JSON',
        success: function (data) {
            if (data.success == 1) {
                $('.config-title').text(cname);
                $('.populateConfiguration').html(data.html);
                $('#myModal').modal('show');
            } else {
                alert("No Other Config data.");
            }
            $("#loader").hide();
        },
        error: function (request, error)
        {
            alert("Error while fetching the config.");
        }
    });

});


$(document).on('click', '.add-config-modal', function (e) {
    e.preventDefault();
    var readonlyVals = $('.config-checkbox:checkbox:checked').map(function () {
        return this.value;
    }).get();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: GlblURLs + 'admin/saveconfig-data-ajax',
        type: 'POST',
        data: {
            'configids': readonlyVals,
            'centreid': $('#centre_id').val(),
            '_token': $('meta[name="csrf_token"]').attr('content')
        },
        beforeSend: function () {
            // setting a timeout
            $("#loader").show();
        },
        dataType: 'JSON',
        success: function (data) {
            if (data.status == 1) {
                location.reload();
            }
            $("#loader").hide();
        },
        error: function (request, error)
        {
            alert("Error while fetching the config.");
        }
    });

});

$(document).on('click', '.editSubAdmin', function () {
    $('#titleText').html('Edit Manager');
    $('.resetbtn').hide();
    $('#submitSubAdmin').html('Edit Manager');
    $('#subAdminRegister').attr('action', GlblURLs + "admin/edit-subAdmin");
    var subadmin_id = $(this).data('id');
    scrollToCustomerForm();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: GlblURLs + 'admin/edit-subAdmin-ajax',
        type: 'POST',
        data: {
            'id': subadmin_id,
            '_token': $('meta[name="csrf_token"]').attr('content')
        },
        dataType: 'JSON',
        success: function (data) {

            $('#subadmin_id').val(data['id']);
            $('#subadminName').val(data['name']);
            $('#requestEmailID').val(data['email']);
            $('#requestMobile').val(data['mobile']);
            $('#center_id').val(data['center_id']);

            $('#commentsubadmin').text(data['comment']);
            //$('#requestEmailID,#requestMobile').prop('readonly', true);
            $('#status').val(data['status']);
//	    	changelocation(data['center_id']);
        },
        error: function (request, error)
        {
            alert("Error while editing the subadmin.");
        }
    });
});


function changelocation(centerid) {
    var loc_id = $("#selectedLocation option:selected").val();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: GlblURLs + 'admin/get-centrebyloc-ajax',
        type: 'POST',
        data: {
            'loc_id': loc_id,
            '_token': $('meta[name="csrf_token"]').attr('content')
        },
        dataType: 'JSON',
        success: function (data) {
            if (data.status == 1) {
                $('.populate-centre').html(data.html);
                if (centerid != 0) {
                    $('.populate-centre').val(centerid);
                }

            } else {
                $('.populate-centre').html('<option value="">No centre available</option>');
            }
        },
        error: function (request, error)
        {
            alert("Error while getting centre data.");
        }
    });
}



$('.user-booking-email').keyup(function () {
    var query = $(this).val();
    if (query != '')
    {
        $('#custList').fadeIn();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: GlblURLs + 'admin/search-customer',
            method: "POST",
            data: {
                'query': query,
                '_token': $('meta[name="csrf_token"]').attr('content')
            },
            success: function (data) {
                if (data != 'No Customers Found') {
                    $('#custList').fadeIn();
                    $('#custList').html(data);
                    $('#addcust').hide();
                } else {
                    $('#custList').fadeOut();
                    $('#addcust').show();
                }
            }
        });
    } else {
        $('#custList').fadeOut();
        $('.custDetail').hide();
    }
});

$(document).on('click', '.userlist', function () {
    var cust_id = this.id;
    $('.custDetail').show();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: GlblURLs + 'admin/edit-customer-ajax',
        type: 'POST',
        data: {
            'id': cust_id,
            '_token': $('meta[name="csrf_token"]').attr('content')
        },
        dataType: 'JSON',
        success: function (data) {
            $('#comp_name').val(data['cust_comp']);
            $('#user_name').val(data['name']);
            $('#user_desig').val(data['cust_desig']);
            $('#phone_no').val(data['mobile']);

        },
        error: function (request, error)
        {
            alert("Error while edit  data");
        }
    });
    $('.user-booking-email').val($(this).text());
    $('#custList').fadeOut();

});


function check_avail() {

    if ($("#location").val() == '')
    {
        alert('Please select City');
        $("#location").focus();
        return false;
    }
    if ($("#center").val() == '')
    {
        alert('Please select Center');
        $("#center").focus();
        return false;
    }
    if ($("#book_date_range").val() == '')
    {
        alert('Please select Date From');
        $("#date_from").focus();
        return false;
    }



    ///$("#avl_msg").css("display", "none");
    $.ajax({
        url: GlblURLs + 'admin/check-cobooking-available-ajax', // Url to which the request is send
        type: "POST", // Type of request to be send, called as method
        data: $('#bookingRegister').serialize(), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
        success: function (data) {
            if (data == 'Error') {
                $("#avl_msg").html("Not available, Check again");
                $("#booking_amount").val('');
                //$(".searchBut3").prop('disabled', true);
                $(".searchBut3").css("display", "none");
            } else {
                $("#avl_msg").html("");
                $("#booking_amount").val(data);
                //$(".searchBut3").prop('disabled', false);

                $("#amt_opt").show();
                $("#check_avail_div").css("display", "none");
                $(".searchBut3").css("display", "block");
            }
        }
    });
    return false;
}



function check_serflag(cat_id) {
    if (cat_id == 0) {
        cat_id = $('#ms_cat').val();
    }
    if (cat_id != '') {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: GlblURLs + 'admin/check-flagcategory-ajax',
            type: 'POST',
            data: {
                'id': cat_id,
                '_token': $('meta[name="csrf_token"]').attr('content')
            },
            dataType: 'JSON',
            success: function (data) {
                $('.padRig').show();
                if (data['flag_hour'] == 0) {
                    $('.ms_hour').hide();
                }
                if (data['flag_month'] == 0) {
                    $('.ms_month').hide();
                }
                if (data['flag_year'] == 0) {
                    $('.ms_year').hide();
                }
                if (data['flag_halfday'] == 0) {
                    $('.ms_half').hide();
                }
                if (data['flag_fullday'] == 0) {
                    $('.ms_full').hide();
                }
                if (data['flag_quart'] == 0) {
                    $('.ms_quart').hide();
                }
                if (data['flag_halfyear'] == 0) {
                    $('.ms_hy').hide();
                }

            },
            error: function (request, error)
            {
                alert("Error while edit  data");
            }
        });
    }
}


$(document).on('click', '.editBooking', function () {
    var booking_id = $(this).data('id');
    scrollToCustomerForm();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: GlblURLs + 'admin/edit-booking-ajax',
        type: 'POST',
        data: {
            'id': booking_id,
            '_token': $('meta[name="csrf_token"]').attr('content')
        },
        dataType: 'JSON',
        success: function (data) {
            console.log(data);
        },
        error: function (request, error)
        {
            alert("Error while editing the subadmin.");
        }
    });
});

$('.all_ofr_flag').on('change', function () {
    if ($(this).attr('id') === 'flag_internal_url' && $(this).is(':checked')) {
        $('.internal_url_check').attr('readonly', false);
    } else {
        $('.internal_url_check').attr('readonly', true);
    }
    $('.all_ofr_flag').not(this).prop('checked', false);
});



$('#book_add_on').on('change', function () {
    var add_on_val = $(this).val();
    if (add_on_val != '') {
        $('#add_on_div').css('display', 'contents');
    } else {
        $('#add_on_div').css('display', 'none');
    }
});

$('.all_flag_book').on('change', function () {
    $('.all_flag_book').not(this).prop('checked', false);
});



$(document).on('click', '.editNews', function () {
    var n_id = $(this).data('id');
    $('#newsRegister').attr('action', GlblURLs + "admin/edit-news");
    scrollToCustomerForm();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: GlblURLs + 'admin/edit-news-ajax',
        type: 'POST',
        data: {
            'id': n_id,
            '_token': $('meta[name="csrf_token"]').attr('content')
        },
        dataType: 'JSON',
        success: function (data) {
            $('#n_title').val(data['n_title']);
            $('#n_heading').val(data['n_heading']);
            $('#n_id').val(data['n_id']);
            $('#n_url').val(data['n_url']);
            var n_centre_id = data['n_centre_id'].split(",");
            $('#n_centre_id').val(n_centre_id);
            $('#n_ordering').val(data['n_ordering']);
            $('#n_status').val(data['n_status']);
            if (data['n_featured'] == 1) {
                $('#n_featured').prop('checked', 'true');
            }

        },
        error: function (request, error)
        {
            alert("Error while editing .");
        }
    });
});
$(document).on('click', '.editEvent', function () {
    var n_id = $(this).data('id');
    $('#eventRegister').attr('action', GlblURLs + "admin/edit-event");
    scrollToCustomerForm();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: GlblURLs + 'admin/edit-event-ajax',
        type: 'POST',
        data: {
            'id': n_id,
            '_token': $('meta[name="csrf_token"]').attr('content')
        },
        dataType: 'JSON',
        success: function (data) {
            $('#e_name').val(data['e_name']);
            var dateTimeFrom = new Date(data['e_from']);
            var dateTimeTo = new Date(data['e_to']);
            dateTimeFrom = moment(dateTimeFrom).format("MMMM DD , YYYY");
            dateTimeTo = moment(dateTimeTo).format("MMMM DD , YYYY");
            $('#event_daterange').html(dateTimeFrom + ' - ' + dateTimeTo);
            $('#e_id').val(data['e_id']);
            $('#e_detail').val(data['e_detail']);
            var e_centre_id = data['e_centre_id'].split(",");
            $('#e_centre_id').val(e_centre_id);
            $('#e_status').val(data['e_status']);
            $('#imagePage').html('<img style="height:100px;width:150px;" src="' + imgUrl + 'upload/event/' + data['e_img'] + '">');
        },
        error: function (request, error)
        {
            alert("Error while editing .");
        }
    });
});

$(document).on('click', '.editPost', function () {
    var post_id = $(this).data('id');
    scrollToCustomerForm();
    $('.postDetail').show();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: GlblURLs + 'admin/edit-post-ajax',
        type: 'POST',
        data: {
            'id': post_id,
            '_token': $('meta[name="csrf_token"]').attr('content')
        },
        dataType: 'JSON',
        success: function (data) {
            $('#post_id').val(data['post_id']);
            $('#post_content').val(data['post_content']);
            $('#user_name').val(data['user_name']);
            $('#user_mobile').val(data['user_mobile']);
            $('#user_email').val(data['user_email']);
            $('#published_date').val(data['created_at']);
            $('#post_status').val(data['post_status']);

        },
        error: function (request, error)
        {
            alert("Error while editing .");
        }
    });
});

$(document).on('click', '.editCtext', function () {
    var ctext_id = $(this).data('id');
    $('#ctextRegister').attr('action', GlblURLs + "admin/edit-ctext");
    scrollToCustomerForm();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: GlblURLs + 'admin/edit-ctext-ajax',
        type: 'POST',
        data: {
            'id': ctext_id,
            '_token': $('meta[name="csrf_token"]').attr('content')
        },
        dataType: 'JSON',
        success: function (data) {
            $('#ctext_id').val(data['ctext_id']);
            $('#ctext_status').val(data['ctext_status']);
            CKEDITOR.instances.ctext_inf.setData(data['ctext_inf']);

        },
        error: function (request, error)
        {
            alert("Error while editing.");
        }
    });
});


$(document).on('click', '.editOffer', function () {
    var n_id = $(this).data('id');
    $('#offerRegister').attr('action', GlblURLs + "admin/edit-offer");
    scrollToCustomerForm();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: GlblURLs + 'admin/edit-offer-ajax',
        type: 'POST',
        data: {
            'id': n_id,
            '_token': $('meta[name="csrf_token"]').attr('content')
        },
        dataType: 'JSON',
        success: function (data) {
            CKEDITOR.instances.offer_text.setData(data['offer_text']);
            $('#offer_id').val(data['offer_id']);
            $('#offer_url').val(data['offer_url']);
            $('#offer_centre_id').val(data['offer_centre_id']);
            $('#offer_category_id').val(data['offer_category_id']);
            $('#imagePage').html('<img style="height:100px;width:150px;" src="' + imgUrl + 'upload/offer/' + data['offer_banner'] + '">');
            if (data['offer_url_flg'] == 0) {
                $('#flag_internal_url').prop('checked', 'true');
            } else {
                $('#flag_external_url').prop('checked', 'true');
            }

        },
        error: function (request, error)
        {
            alert("Error while editing .");
        }
    });
});
$(document).on('click', '.editIntro', function () {
    var n_id = $(this).data('id');
    $('#introRegister').attr('action', GlblURLs + "admin/edit-introdata");
    scrollToCustomerForm();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: GlblURLs + 'admin/edit-introdata-ajax',
        type: 'POST',
        data: {
            'id': n_id,
            '_token': $('meta[name="csrf_token"]').attr('content')
        },
        dataType: 'JSON',
        success: function (data) {
            CKEDITOR.instances.intro_text.setData(data['intro_text']);
            $('#intro_id').val(data['intro_id']);
            $('#intro_url').val(data['intro_url']);
            $('#intro_status').val(data['intro_status']);
            $('#imagePage').html('<img style="height:100px;width:150px;" src="' + imgUrl + 'upload/intro/' + data['intro_image'] + '">');


        },
        error: function (request, error)
        {
            alert("Error while editing .");
        }
    });
});


$(document).on('click', '.editVtour', function () {
    var n_id = $(this).data('id');
    $('#vtourRegister').attr('action', GlblURLs + "admin/edit-virtualtour");
    scrollToCustomerForm();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: GlblURLs + 'admin/edit-virtualtour-ajax',
        type: 'POST',
        data: {
            'id': n_id,
            '_token': $('meta[name="csrf_token"]').attr('content')
        },
        dataType: 'JSON',
        success: function (data) {
            CKEDITOR.instances.vt_title.setData(data['vt_title']);
            CKEDITOR.instances.vt_subtitle.setData(data['vt_subtitle']);
            $('#vt_id').val(data['vt_id']);
            $('#vt_centre_id').val(data['vt_centre_id']);
            $('#vt_embed_map').val(data['vt_embed_map']);
            $('#vt_status').val(data['vt_status']);
            $('#imagePage').html('<img style="height:100px;width:150px;" src="' + imgUrl + 'upload/vtour/' + data['vt_th_img'] + '">');

        },
        error: function (request, error)
        {
            alert("Error while editing .");
        }
    });
});

$(document).on('click', '.editEmailMatrix', function () {
    var n_id = $(this).data('id');
    $('#emailMatrixRegister').attr('action', GlblURLs + "admin/edit-emailmatrix");
    scrollToCustomerForm();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

//    $('.tworow').hide();
//    $('.threerow').hide();
    $.ajax({
        url: GlblURLs + 'admin/edit-emailmatrix-ajax',
        type: 'POST',
        data: {
            'id': n_id,
            '_token': $('meta[name="csrf_token"]').attr('content')
        },
        dataType: 'JSON',
        success: function (data) {

            $.each(data, function (k, v) {
                $('#centre_id_emailmatrix').val(v.centre_id);
                $('#em_per' + (k + 1)).val(v.em_per);
                $('#em_email' + (k + 1)).val(v.em_email);
                $('#em_phone' + (k + 1)).val(v.em_phone);
                $('#em_status').val(v.em_status);
                $('#em_id' + (k + 1)).val(v.em_id);

            });
        },
        error: function (request, error)
        {
            alert("Error while editing .");
        }
    });
});



$(document).on('click', '.editCompany', function () {
    var n_id = $(this).data('id');
    $('#compRegister').attr('action', GlblURLs + "admin/edit-company");
    scrollToCustomerForm();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: GlblURLs + 'admin/edit-company-ajax',
        type: 'POST',
        data: {
            'id': n_id,
            '_token': $('meta[name="csrf_token"]').attr('content')
        },
        dataType: 'JSON',
        success: function (data) {
            $('#cust_id').val(data['cust_id']);
            $('#cust_comp').val(data['cust_comp']);
            $('#cust_nme').val(data['cust_nme']);
            $('#cust_email').val(data['cust_email']);
            $('#cust_mobile').val(data['cust_mobile']);
            $('#comp_status').val(data['comp_status']);
            $('#cust_service_add1').val(data['cust_service_add1']);
            $('#custloc').val(data['custloc']);
            $('#cust_desig').val(data['cust_desig']);
            $('#comp_gst').val(data['comp_gst']);
            $('#cust_centre').val(data['cust_centre']);
            $('#blc').val(data['blc']);

        },
        error: function (request, error)
        {
            alert("Error while editing .");
        }
    });
});


$(document).on('click', '.editSupportService', function () {
    var n_id = $(this).data('id');
    $('#supportservRegister').attr('action', GlblURLs + "admin/edit-sprtserv");
    scrollToCustomerForm();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: GlblURLs + 'admin/edit-sprtserv-ajax',
        type: 'POST',
        data: {
            'id': n_id,
            '_token': $('meta[name="csrf_token"]').attr('content')
        },
        dataType: 'JSON',
        success: function (data) {
            $('#ss_id').val(data['ss_id']);
            $('#ss_text').val(data['ss_text']);
            $('#ss_status').val(data['ss_status']);
            $('#imagePage').html('<img style="height:100px;width:150px;" src="' + imgUrl + 'upload/supportservice/' + data['ss_img'] + '">');

        },
        error: function (request, error)
        {
            alert("Error while editing .");
        }
    });
});
$(document).on('click', '.editTagSupportService', function () {
    var n_id = $(this).data('id');
    $('#tagsupportservRegister').attr('action', GlblURLs + "admin/edit-sprtservtocomp");
    scrollToCustomerForm();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: GlblURLs + 'admin/edit-sprtservtocomp-ajax',
        type: 'POST',
        data: {
            'id': n_id,
            '_token': $('meta[name="csrf_token"]').attr('content')
        },
        dataType: 'JSON',
        success: function (data) {
            $.each(data, function (k, v) {
                $('#centreid').val(k);
                $('#ssid').val(v);
            });

        },
        error: function (request, error)
        {
            alert("Error while editing .");
        }
    });
});


$(document).on('click', '.editCompanyOffer', function () {
    var co_id = $(this).data('id');
    $('#compofferRegister').attr('action', GlblURLs + "admin/edit-companyoffer");
    scrollToCustomerForm();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: GlblURLs + 'admin/edit-companyoffer-ajax',
        type: 'POST',
        data: {
            'id': co_id,
            '_token': $('meta[name="csrf_token"]').attr('content')
        },
        dataType: 'JSON',
        success: function (data) {

            $('#dis_date_range').val(data['co_cntrctstrtdte'] + 'and' + data['co_cntrctenddte']);
            var firstday = moment(data['co_cntrctstrtdte']).format("MMMM D, YYYY");
            var lastday = moment(data['co_cntrctenddte']).format("MMMM D, YYYY");
            $("#reportrange2 span").html(firstday + " - " + lastday);
            $("#req_date_range").val(firstday + " and " + lastday);
            $('#co_compid').val(data['co_compid']);
            $('#co_id').val(data['co_id']);
            $('#co_catid').val(data['co_catid']);
            //populateServiceConfig(data['co_catid'], data['co_configid']);
            $('#co_allctedhrs').val(data['co_allctedhrs']);
            $('#co_offerdays').val(data['co_offerdays'].split(','));
            $('#co_ofrtimefrom').val(data['co_ofrtimefrom']);
            $('#co_ofrtimeto').val(data['co_ofrtimeto']);
            $('#co_allctedmnthhrs').val(data['co_allctedmnthhrs']);
            $('#co_status').val(data['co_status']);

        },
        error: function (request, error)
        {
            alert("Error while editing .");
        }
    });
});


$('#d_cust').change(function () {
    if ($(this).val() === 'enter_mob') {
        $('#cust_mob').attr('disabled', false);
        $('.sel-cust-multiple').hide();
    } else if ($(this).val() === 'select_cust') {
        $('.sel-cust-multiple').show();
        $('#cust_mob').attr('disabled', true);
    } else {
        $('.sel-cust-multiple').hide();
        $('#cust_mob').attr('disabled', true);
    }
});




function populateServiceConfig(data1, selconfig) {
    var catid = '';
    if (data1 === 0) {
        catid = $('#co_catid').val();
    } else {
        catid = data1;
    }

    $('#co_configid').html('');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: GlblURLs + 'admin/populate-configuration',
        type: 'POST',
        data: {
            'ms_cat': catid,
            '_token': $('meta[name="csrf_token"]').attr('content')
        },
        dataType: 'JSON',
        success: function (data) {
            var $dropdown2 = $("#co_configid");
//            data.length=0;
            if (data.length > 0) {
                $dropdown2.append($("<option />").val('').text('Select Config'));
                $.each(data, function () {
                    if (this.ms_type !== 0) {
                        $dropdown2.append($("<option />").val(this.ms_id).text(this.ms_name + '(' + this.ms_type + ' seater)'));
                    } else {
                        $dropdown2.append($("<option />").val(this.ms_id).text(this.ms_name));
                    }
                });
                if (selconfig !== 0) {
                    $('#co_configid').val(selconfig);
                }
            } else {
                $dropdown2.append($("<option />").val('').text('No config Available'));
            }

        },
        error: function (request, error)
        {
            alert("NO services present");
            return false;
        }
    });
}

$('#centre_id_emailmatrix').change(function () {
    var c_id = $(this).val();
    $('#c_person1').val('');
    $('#c_email1').val('');
    $('#c_phone1').val('');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: GlblURLs + 'admin/get-emailmatrixdata-ajax',
        type: 'POST',
        data: {
            'id': c_id,
            '_token': $('meta[name="csrf_token"]').attr('content')
        },
        dataType: 'JSON',
        success: function (data) {
            $('#em_per1').val(data['centre']);
            $('#em_email1').val(data['centre_email']);
            $('#em_phone1').val(data['centre_mobile']);

        },
        error: function (request, error)
        {
            alert("Error while editing .");
        }
    });
});


$('#customerser').keyup(function () {
    var query = $(this).val();
    if (query != '')
    {
        $('#custList').fadeIn();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: GlblURLs + 'admin/search-customer',
            method: "POST",
            data: {
                'query': query,
                '_token': $('meta[name="csrf_token"]').attr('content')
            },
            success: function (data) {
                if (data != 'No Customers Found') {
                    $('#custList').fadeIn();
                    $('#custList').html(data);
                    $('#addcust').hide();
                } else {
                    $('#custList').fadeOut();
                    $('#addcust').show();
                }
            }
        });
    } else {
        $('#custList').fadeOut();
        $('.custDetail').hide();
    }
});

$(document).on('click', '.userlist', function () {
    var cust_id = this.id;
    $('.custDetail').show();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: GlblURLs + 'admin/edit-customer-ajax',
        type: 'POST',
        data: {
            'id': cust_id,
            '_token': $('meta[name="csrf_token"]').attr('content')
        },
        dataType: 'JSON',
        success: function (data) {
            $('#cust_id').val(data['id']);
            $('#requestName').val(data['name']);
            $('#requestEmailID').val(data['email']);
            $('#requestMobile').val(data['mobile']);
            $('.regcustbutton').hide();
        },
        error: function (request, error)
        {
            alert("Error while edit  data");
        }
    });
    $('#customerser').val($(this).text());
    $('#custList').fadeOut();

});

$(document).on('click', '#addcust', function () {
    $('#cust_id').val('');
    $('#requestName').val('');
    $('#requestEmailID').val('');
    $('#requestMobile').val('');
    $('#requestLocation').val('');
    $('#requestAddress').val('');
    $('#requestAddress2').val('');
    $('#cust_landmark').val('');
    $('.regcustbutton').show();
    $('.custDetail').toggle();
});
$(document).on('click', '#registerCust', function () {
    var cust_name = $('#requestName').val();
    var cust_email = $('#requestEmailID').val();
    var cust_mob = $('#requestMobile').val();
    var cust_seradd1 = $('#requestAddress').val();
    var cust_seradd2 = $('#requestAddress2').val();
    var cust_landmark = $('#cust_landmark').val();
    var cust_loc = $('#selectLocation').val();
    var cust_pref_date = $('#req_date').val();
    var cust_timeslot = $('#ser_timeslot').val();


    if (check_space(document.getElementById('requestName').value) == '' || check_space(document.getElementById('requestName').value) == 0)
    {
        alert('Please enter  Name');
        document.getElementById('requestName').value = '';
        document.getElementById('requestName').focus();
        return false;
    } else if (!(/^[a-zA-Z\s]+$/.test(document.getElementById('requestName').value)))
    {
        alert('Please enter valid  Name');
        document.getElementById('requestName').value = '';
        document.getElementById('requestName').focus();
        return false;
    }
    if (check_space(document.getElementById('requestEmailID').value) == '' || document.getElementById('requestEmailID').value == 'Email')
    {
        alert("Please enter Email");
        document.getElementById('requestEmailID').value = '';
        document.getElementById('requestEmailID').focus();
        return false;
    } else if (!(/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/.test(document.getElementById('requestEmailID').value)))
    {
        alert("Please enter valid Email");
        document.getElementById('requestEmailID').value = '';
        document.getElementById('requestEmailID').focus();
    }
    if (check_space(document.getElementById('requestMobile').value) == '')
    {
        alert('Please enter Mobile no');
        document.getElementById('requestMobile').value = '';
        document.getElementById('requestMobile').focus();
        return false;
    } else if (!(/^[0-9\-]+$/.test(document.getElementById('requestMobile').value)))
    {
        alert('Please enter valid Mobile no');
        document.getElementById('requestMobile').value = '';
        document.getElementById('requestMobile').focus();
        return false;
    }
    if (check_space(document.getElementById('requestAddress').value) == '' || check_space(document.getElementById('requestAddress').value) == 0)
    {
        alert('Please enter  Service Address 1');
        document.getElementById('requestAddress').value = '';
        document.getElementById('requestAddress').focus();
        return false;
    }
    if (check_space(document.getElementById('requestAddress2').value) == '' || check_space(document.getElementById('requestAddress2').value) == 0)
    {
        alert('Please enter Service Address 2');
        document.getElementById('requestAddress2').value = '';
        document.getElementById('requestAddress2').focus();
        return false;
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: GlblURLs + 'admin/add-customer-ajax',
        type: 'POST',
        data: {
            'cust_name': cust_name,
            'cust_email': cust_email,
            'cust_mob': cust_mob,
            'cust_seradd1': cust_seradd1,
            'cust_seradd2': cust_seradd2,
            'cust_landmark': cust_landmark,
            'cust_loc': cust_loc,
            'cust_pref_date': cust_pref_date,
            'cust_timeslot': cust_timeslot,
            '_token': $('meta[name="csrf_token"]').attr('content')
        },
        dataType: 'JSON',
        success: function (data) {
            alert(data.msg);
            $('#cust_id').val(data.data.id);
            $('#requestName').val(cust_name);
            $('#requestEmailID').val(cust_email);
            $('#requestMobile').val(cust_mob);
            $('#requestLocation').val(cust_loc);
            $('#requestAddress').val(cust_seradd1);
            $('#requestAddress2').val(cust_seradd2);
            $('#cust_landmark').val(cust_landmark);
            $('.regcustbutton').hide();
        },
        error: function (request, error)
        {
            alert("Error while edit  data");
        }
    });
});

var dataToSend = '';
var userdata = '';

function validateBookingAvailability() {
    var centre_id = $('#centre_id').val();
    $('#customerser').prop('readonly', true);
    var ser_config = $('#ser_config').val();
    $('#ser_config').prop('disabled', true);
    var date_range = $('#book_date_range').val();
    $('#book_date_range').prop('readonly', true);
    var time_from = $('#time-from').val();
//    $('#time-from').prop('disabled',true);
    var time_to = $('#time-to').val();
//    $('#time-to').prop('disabled',true);
    var selectedCat = $('#ms_cat').val();
    var add_on = '';
    var subs_duration = '';
    if (selectedCat === '2' || selectedCat === '10' || selectedCat === '5' || selectedCat === '3' || selectedCat === '6') {
        if (typeof $('#months-duration').val() !== 'undefined' || $('#months-duration').val() !== 'Select month') {
            subs_duration = $('#months-duration').val();
            $('#months-duration').prop('readonly', true);
        }

        if (typeof ser_config === 'undefined' || ser_config === 'Select Plan') {
            alert("Please select plan");
            document.getElementById('ser_config').focus();
            $('#months-duration').prop('readonly', false);
            return false;
        } else {
            $('#months-duration').prop('readonly', true);
        }
    }

    if ($('#customerser').val() == '') {
        alert('Please enter customer details');
        document.getElementById('customerser').value = '';
        document.getElementById('customerser').focus();
        $('#customerser').prop('readonly', false);
        return false;
    }
    if ($('#ms_cat').val() == 4) {
        if ($('#ser_config').val() == '') {
            alert('Please select configuration');
            document.getElementById('ser_config').focus();
            $('#ser_config').prop('disabled', false);
            return false;
        } else {
            $('#ser_config').prop('disabled', true);
        }
        if ($('#time-from').val() == 'Select Time From' || $('#time-from').val() == 'Select time from') {
            alert('Please select time from');
            document.getElementById('time-from').focus();
            $('#time-from').prop('disabled', false);
            return false;
        } else {
            $('#time-from').prop('disabled', true);
        }
        if ($('#time-to').val() == 'Select Time to' || $('#time-to').val() == 'Select time to') {
            alert('Please select time to');
            document.getElementById('time-to').focus();
            $('#time-to').prop('disabled', false);
            return false;
        } else {
            $('#time-to').prop('disabled', true);
        }
        if ($('#addonselect').val() == 1) {
            if ($('#addons-time-interval-select').val() == 'by_hr' && ($('#by-hr-val-select').val() == '' || $('#by-hr-val-select').val() == 'By hour')) {
                alert('Please select time interval');
                document.getElementById('by-hr-val-select').focus();
                $('#by-hr-val-select').prop('disabled', false);
                return false;
            } else {
                $('#by-hr-val-select').prop('disabled', true);
            }
        }
    } else {
        if ($('#time-from').val() == 'Select Time From' || $('#time-from').val() == 'Select time from') {
            alert('Please select time from');
            document.getElementById('time-from').focus();
            $('#time-from').prop('disabled', false);
            return false;
        } else {
            $('#time-from').prop('disabled', true);
        }
    }

    if ($('#addons-select').val() == 'Video Conference') {
        add_on = 'video-conference';
        $('#addons-select').prop('disabled', true);
    }
    if ($('#addons-select').val() == 'Projector') {
        add_on = 'projector';
        $('#addons-select').prop('disabled', true);
    }

    var hr_selected = $('#by-hr-val-select').val();
    var time_interval = $('#addons-time-interval-select').val();

    $('#by-hr-val-select').prop('disabled', true);
    $('#addons-time-interval-select').prop('disabled', true);
	if (ser_config == '3') {
		subs_duration = 1;
	}
    dataToSend = {'ms_id':$('#ms_id').val(),'centre_id': centre_id, 'ser_config': ser_config, 'date_range': date_range, 'time_from': time_from, 'time_to': time_to, 'add_on': add_on, 'time_interval': $('#addons-time-interval-select').val(), 'hr_selected': $('#by-hr-val-select').val(), 'loc_id': $('#loc_id').val(), 'selectedCat': $('#ms_cat').val(), 'subs_duration': subs_duration, 'check_app': 0};

    userdata = {'cust_id': $('#cust_id').val(), 'cust_nme': $('#requestName').val(), 'cust_email': $('#requestEmailID').val(), 'cust_mobile': $('#requestMobile').val()};

    checkBookingAvailability(dataToSend, userdata);
}


function checkBookingAvailability(dataToSend, userData) {

    var data = {'dataToSend': dataToSend, 'userData': userData};
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'POST',
        contentType: 'application/json',
        url: apiUrl + 'public/index.php/abc/checkBookingAvailability',
        dataType: "json",
        data: JSON.stringify(data),
        success: function (data) {
            if (data.status === 1) {
                $('.availablemsg').html(data.html);

                $('#submitBooking').attr('onclick', 'bookUserService()');
                $('#submitBooking').text('Book Service');
            }
        }, error: function (request, error) {

            alert("Error while fetching  data");
        }
    });
}


function bookUserService() {
    var centre_id = $('#centre_id').val();
    var ser_config = $('#ser_config').val();
    var date_range = $('#book_date_range').val();

    var time_from = $('#time-from').val();
    var time_to = $('#time-to').val();
    var selectedCat = $('#ms_cat').val();
    var add_on = '';
    var subs_duration = '';
    if (selectedCat === '2' || selectedCat === '10' || selectedCat === '5' || selectedCat === '3' || selectedCat === '6') {
        if (typeof $('#months-duration').val() !== 'undefined' || $('#months-duration').val() !== 'Select month') {
            subs_duration = $('#months-duration').val();
        }

        if (typeof ser_config === 'undefined' || ser_config === 'Select Plan') {
            alert("Please select plan");
            document.getElementById('ser_config').focus();
            return false;
        }
    }

    if ($('#customerser').val() == '') {
        alert('Please enter customer details');
        document.getElementById('customerser').value = '';
        document.getElementById('customerser').focus();
        return false;
    }
    if ($('#ms_cat').val() == 4) {
        if ($('#ser_config').val() == '') {
            alert('Please select configuration');
            document.getElementById('ser_config').focus();
            return false;
        }
        if ($('#time-from').val() == 'Select Time From' || $('#time-from').val() == 'Select time from') {
            alert('Please select time from');
            document.getElementById('time-from').focus();
            return false;
        }
        if ($('#time-to').val() == 'Select Time to' || $('#time-to').val() == 'Select time to') {
            alert('Please select time to');
            document.getElementById('time-to').focus();
            return false;
        }
        if ($('#addonselect').val() == 1) {
            if ($('#addons-time-interval-select').val() == 'by_hr' && ($('#by-hr-val-select').val() == '' || $('#by-hr-val-select').val() == 'By hour')) {
                alert('Please select time interval');
                document.getElementById('by-hr-val-select').focus();
                return false;
            }
        }
    }

    if ($('#addons-select').val() == 'Video Conference') {
        add_on = 'video-conference';
        $('#addons-select').prop('disabled', true);
    }
    if ($('#addons-select').val() == 'Projector') {
        add_on = 'projector';
        $('#addons-select').prop('disabled', true);
    }
	if (ser_config == '3') {
		subs_duration = 1;
	}
    dataToSend = {'centre_id': centre_id, 'ser_config': ser_config, 'date_range': date_range, 'time_from': time_from, 'time_to': time_to, 'add_on': add_on, 'time_interval': $('#addons-time-interval-select').val(), 'hr_selected': $('#by-hr-val-select').val(), 'loc_id': $('#loc_id').val(), 'selectedCat': $('#ms_cat').val(), 'order_total_amnt': $('#order_total_amnt').val(), 'order_tax_amnt': $('#order_tax_amnt').val(), 'order_amnt': $('#order_amnt').val(), 'addon_amnt': $('#addon_amnt').val(), 'subs_duration': subs_duration, 'service_amnt': $('#service_amnt').val()};


    userdata = {'cust_id': $('#cust_id').val(), 'cust_nme': $('#requestName').val(), 'cust_email': $('#requestEmailID').val(), 'cust_mobile': $('#requestMobile').val()};


$('#submitBooking').text('Please wait..');

    var data = {'dataToSend': dataToSend, 'userData': userdata};
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'POST',
        contentType: 'application/json',
        url: apiUrl + 'public/index.php/abc/bookServiceRoom',
        dataType: "json",
        data: JSON.stringify(data),
        success: function (data) {
            if (data.status === 'true') {

                alert('Your service has been booked successfully');

                $('#submitBooking').hide();

                var html = '<div class="block no-margin display-flex h-100 thankYou">';
                html += '<p class="margin font-14"> Thanks for booking.</p></div>';
                $('.availablemsg').html(html);

                location.reload();
            }
        }, error: function (request, error) {

            alert("Error while fetching  data");
        }
    });
}


$('#center_id_link').change(function () {
    var c_id = $(this).val();
    $('#link').val('');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: GlblURLs + 'admin/get-centreLocationLink-ajax',
        type: 'POST',
        data: {
            'id': c_id,
            '_token': $('meta[name="csrf_token"]').attr('content')
        },
        dataType: 'JSON',
        success: function (data) {
            $('#link').val(data['centre_url']);

        },
        error: function (request, error)
        {
            alert("Error while editing .");
        }
    });
});

$('#book_status').change(function () {
    $('#status_remarks_div').show();
});

$('#package_option').change(function () {
	

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: GlblURLs + 'admin/get-packageprice-ajax',
        type: 'POST',
        data: {
            'cid': $('#centre_id').val(),
            'ptype': $(this).val(),
            'ms_name': $('#ser_name').val(),
            '_token': $('meta[name="csrf_token"]').attr('content')
        },
        dataType: 'JSON',
        success: function (data) {
			if(data.status == 1){
				$('#book_user_amnt').val(data.data[0].ms_full);
			}
            //$('#link').val(data['centre_url']);

        },
        error: function (request, error)
        {
            alert("Error while editing .");
        }
    });
});

$(document).on('click', '.editClientBenefits', function () {
    $('#titleText').html('Edit Client Benefits');
    $('#submitNews').html('Edit');
    $('#newsRegister').attr('action', GlblURLs + "admin/edit-clientbenefits");
    var subadmin_id = $(this).data('id');
    scrollToCustomerForm();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: GlblURLs + 'admin/edit-clientbenefits-ajax',
        type: 'POST',
        data: {
            'id': subadmin_id,
            '_token': $('meta[name="csrf_token"]').attr('content')
        },
        dataType: 'JSON',
        success: function (data) {

            $('#cb_id').val(data['cb_id']);
            $('#cb_name').val(data['cb_name']);
            $('#cb_detail').val(data['cb_detail']);
            $('#imagePage').html('<img style="height:100px;width:150px;" src="' + imgUrl + 'upload/clientbenefits/' + data['cb_image'] + '">');
//	    	changelocation(data['center_id']);
        },
        error: function (request, error)
        {
            alert("Error while editing the client benefits.");
        }
    });
});



//
//$(document).on('click', '.editDiscount', function () {
//    $('#titleText').html('Edit Discount');
//    $('#submitDiscount').html('Edit Discount');
//    $('#discountRegister').attr('action', GlblURLs + "admin/edit-discount");
//    $('#d_loc').val('');
//    $('#d_catid').val('');
//    var d_id = $(this).data('id');
//    scrollToCustomerForm();
//    $.ajaxSetup({
//        headers: {
//            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//        }
//    });
//    $.ajax({
//        url: GlblURLs + 'admin/edit-discount-ajax',
//        type: 'POST',
//        data: {
//            'id': d_id,
//            '_token': $('meta[name="csrf_token"]').attr('content')
//        },
//        dataType: 'JSON',
//        success: function (data) {
//            $('#d_id').val(data['d_id']);
//            $('#d_code').val(data['d_code']);
////            $('#dis_date_range').val(data['d_date_start'] + 'and' + data['d_date_end']);
////            firstday = moment(data['d_date_start']).format("MMMM D, YYYY");
////            lastday = moment(data['d_date_end']).format("MMMM D, YYYY");
////            $("#reportrange2 span").html(firstday + " - " + lastday);
////            $('#d_loc').val(data['d_loc'].split(','));
//            $('#d_catid').val(data['d_catid'].split(','));
//            $('#d_amnt').val(data['d_amnt']);
//            $('#d_min_ordr_amnt').val(data['d_min_ordr_amnt']);
//            $('#d_max_consumed').val(data['d_max_consumed']);
//            $('#d_status').val(data['d_status']);
//        },
//        error: function (request, error)
//        {
//            alert("Error while editing the discount");
//        }
//    });
//});


$(document).on('click', '.editCompanyClient', function () {
    var n_id = $(this).data('id');
    $('#compRegister').attr('action', GlblURLs + "admin/edit-companyclient");
    scrollToCustomerForm();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: GlblURLs + 'admin/edit-companyclient-ajax',
        type: 'POST',
        data: {
            'id': n_id,
            '_token': $('meta[name="csrf_token"]').attr('content')
        },
        dataType: 'JSON',
        success: function (data) {
            $('#cc_id').val(data['cc_id']);
            $('#cc_name').val(data['cc_name']);
            $('#cc_status').val(data['cc_status']);

        },
        error: function (request, error)
        {
            alert("Error while editing .");
        }
    });
});
$('input:radio[name="customRadio"]').change(function () {
    console.log(this.id);
    if (this.id == 'customRadio1') {
        $('#customers').attr('disabled', true);
        // append goes here
    } else if (this.id == 'customRadio2') {
        $('#customers').attr('disabled', false);
        // append goes here
    }
});
