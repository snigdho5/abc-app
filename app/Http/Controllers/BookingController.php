<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Auth;
use DB;
use Mail;
use GuzzleHttp\Client;
use App\Modal\Category;
use App\Modal\Centre;
use App\Modal\Tax;
use App\Modal\Meetingroom;
use App\Modal\Booking;
use App\Modal\BookingDetail;
use App\Modal\BookingComment;
use App\Modal\EmailMatrix;
use App\Modal\Location;
use File;
use Response;

class BookingController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles authenticating users for the application and
      | redirecting them to your home screen. The controller uses a trait
      | to conveniently provide its functionality to your applications.
      |
     */

use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
//     protected $redirectTo = '/dashboard';



    public function __construct() {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function AddNewBooking(Request $request) {

        if (Auth::guard('admin')->user()->admin_role == 1 || Auth::guard('admin')->user()->admin_role == 2) {

            $session_data = $request->session()->all();

            $centreData = DB::table('abc_manager_centre_tag')
                    ->select('cid')
                    ->where('mid', Auth::guard('admin')->user()->id)
                    ->get();
            $centre_data = [];
            foreach ($centreData as $key => $value) {
                array_push($centre_data, $value->cid);
            }
            if (count($centre_data) == 1) {
                $cat_selc = 0;
                if (isset($session_data['cat_changed'])) {
                    $cat_selc = $session_data['cat_changed'];
                }
                $request->session()->put('centreselected', $centre_data[0]);
                $emailData = EmailMatrix::where('centre_id', $centre_data[0])->orderBy('created_at', 'DESC')->get();

                $cid = $centre_data[0];

                $minfoData1 = Category::with(['MsInfoDetail', 'MeetingroomDetail' => function($q) {
                                        // Query the name field in status table
                                        $q->where('rr_no', '>', 0);
                                        $q->where('ms_status', '>', 0);  // '=' is optional
                                    }])
                                ->where('acat_type', '=', 'Service')
                                ->where('acat_status', '1')
                                ->where('acat_name', '!=', 'Other')
                                ->where('acat_type', '!=', 'Add. Service')
                                ->orderBy('acat_name', 'ASC')->get();


                $minfoData = collect();
                $minfoData = $minfoData->merge($minfoData1);
                $cdata = Centre::Where('centre_id', $centre_data[0])->first();


                $locationData = Location::Where('loc_id', $cdata->location)->orderBy('loc_name', 'ASC')->first();
                $query = Centre::query();
                if (!empty($request->search)) {
                    $search = $request->search;
                    $query = $query->where(function($query) use ($search) {
                        $query->orWhere('centre', 'LIKE', '%' . $search . '%');
                        $query->orWhere('centre_address', 'LIKE', '%' . $search . '%');
                    });
                }

                $query = $query->whereIn('centre_id', $centre_data);


                $pageData = $query->orderby('created_at', 'DESC')
                        ->paginate(10)
                        ->withPath('?search=' . $request->search);

                return view('admin.booking.config-booking', array('data' => $pageData, 'locationData' => $locationData, 'minfoData' => $minfoData, 'cinfoData' => $cdata, 'cid' => $cid, 'cat_changed' => $cat_selc, 'emaildata' => $emailData));
            } else {
                $locationData = Location::orderBy('loc_name', 'ASC')->get();

                $minfoData = Category::whereHas('MsInfoDetail', function($q) {
                            $q->where('ms_cat', '!=', 0);
                        })->whereHas('MeetingroomDetail', function($q) {
                            $q->where('ms_cat', '!=', 0);
                        })->orderBy('acat_name', 'ASC')->get();


                $query = Centre::query();
                if (!empty($request->search)) {
                    $search = $request->search;
                    $query = $query->where(function($query) use ($search) {
                        $query->orWhere('centre', 'LIKE', '%' . $search . '%');
                        $query->orWhere('centre_address', 'LIKE', '%' . $search . '%');
                    });
                }

                if (Auth::guard('admin')->user()->admin_role == 2) {
                    $centreData = DB::table('abc_manager_centre_tag')
                            ->select('cid')
                            ->where('mid', Auth::guard('admin')->user()->id)
                            ->get();
                    $centre_data = [];
                    foreach ($centreData as $key => $value) {
                        array_push($centre_data, $value->cid);
                    }
                    //		       $query =  $query->where('location', Auth::guard('admin')->user()->loc);
                    $query = $query->whereIn('centre_id', $centre_data);
                }


                $pageData = $query->orderby('created_at', 'DESC')
                        ->paginate(10)
                        ->withPath('?search=' . $request->search);
                return view('admin.services.manage-multibooking', array('data' => $pageData, 'locationData' => $locationData, 'minfoData' => $minfoData));
            }
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    public function AddNewBookingMult(Request $request, $id) {
        $cat_selc = 0;
        $cid = base64_decode($id);
        if (isset($session_data['cat_changed'])) {
            $cat_selc = $session_data['cat_changed'];
        }

        $emailData = EmailMatrix::where('centre_id', $cid)->orderBy('created_at', 'DESC')->get();



        $minfoData1 = Category::with(['MsInfoDetail', 'MeetingroomDetail' => function($q) {
                                // Query the name field in status table
                                $q->where('rr_no', '>', 0);
                                $q->where('ms_status', '>', 0);  // '=' is optional
                            }])
                        ->where('acat_type', '=', 'Service')
                        ->where('acat_status', '1')
                        ->where('acat_name', '!=', 'Other')
                        ->where('acat_type', '!=', 'Add. Service')
                        ->orderBy('acat_name', 'ASC')->get();


        $minfoData = collect();
        $minfoData = $minfoData->merge($minfoData1);
        $cdata = Centre::Where('centre_id', $cid)->first();


        $request->session()->put('centreselected', $cid);

        $locationData = Location::Where('loc_id', $cdata->location)->orderBy('loc_name', 'ASC')->first();
        $query = Centre::query();

        $query = $query->where('centre_id', $cid);


        $pageData = $query->orderby('created_at', 'DESC')
                ->paginate(10);

        return view('admin.booking.config-booking', array('data' => $pageData, 'locationData' => $locationData, 'minfoData' => $minfoData, 'cinfoData' => $cdata, 'cid' => $cid, 'cat_changed' => $cat_selc, 'emaildata' => $emailData));
    }

    public function SelectService(Request $request, $id) {

        //dd($request->session()->get('centreselected'));

        $centreData = DB::table('abc_manager_centre_tag')
                ->select('cid')
                ->where('mid', Auth::guard('admin')->user()->id)
                ->get();
        $centre_data = [];
        foreach ($centreData as $key => $value) {
            array_push($centre_data, $value->cid);
        }

        $centreid = $request->session()->get('centreselected');

        if (isset($centreid) && $centreid != '') {
            $cid = $centreid;
        } else {
            $cid = $centre_data[0];
        }

        $configData = Meetingroom::select('ms_id', 'rr_no', 'ms_name', 'ms_cat', 'ms_hour', 'ms_half', 'ms_full', 'ms_month', 'ms_pln_quart', 'ms_pln_hy', 'ms_pln_yr')
                ->where('rr_id', base64_decode($id))
                ->where('center_id', $cid)
                ->first();

        $client = new Client();
        $options = [
            'json' => [
                "cid" => $cid,
                "sname" => $configData['ms_cat']
            ]
        ];
        $response = $client->post(env('LIVE_API_PATH') . "getCenterConfigDetailsWeb", $options);


        $result = $response->getBody()->getContents();


        $result = json_decode($result, true);

        //dd($result);

        if ($configData['rr_no'] <= 0) {
            return redirect()->back()->with('message', 'No inventory available');
        }

        $configHtml = '';
        $durhtml = '';
        $addonhtml = '';

        if ($configData['ms_cat'] == 4) {

            foreach ($result['data'] as $key => $value) {
                $configHtml .= '<option value="' . $value['ms_type'] . '">' . $value['ms_type'] . '</option>';
            }
        }
        if ($configData['ms_cat'] == 2 || $configData['ms_cat'] == 3 || $configData['ms_cat'] == 5 || $configData['ms_cat'] == 6 || $configData['ms_cat'] == 10) {
            $configHtml .= '<option> Select Plan</option>';
            if ($result['data'][0]['ms_full'] !== '' && $result['data'][0]['ms_full'] !== '0.00') {
                $configHtml .= '<option  value="2"> Full Day</option>';
            }
            if ($result['data'][0]['ms_hour'] !== '' && $result['data'][0]['ms_hour'] !== '0.00') {
                $configHtml .= '<option value="5"> Hourly</option>';
            }
            if ($result['data'][0]['ms_month'] !== '' && $result['data'][0]['ms_month'] !== '0.00') {
                $configHtml .= '<option value="3"> Monthly</option>';
            }
            if ($result['data'][0]['ms_pln_quart'] !== '' && $result['data'][0]['ms_pln_quart'] !== '0.00') {
                $configHtml .= '<option value="8"> Quarterly</option>';
            }
            if ($result['data'][0]['ms_pln_hy'] !== '' && $result['data'][0]['ms_pln_hy'] !== '0.00') {
                $configHtml .= '<option value="9"> Half yearly</option>';
            }
            if ($result['data'][0]['ms_pln_yr'] !== '' && $result['data'][0]['ms_pln_yr'] !== '0.00') {
                $configHtml .= '<option value="4"> Yearly</option>';
            }
        }



        for ($i = 1; $i <= 11; $i++) {
            $durhtml .= '<option value="' . $i . '"> ' . $i . '</option>';
        }



        if ($configData['ms_cat'] !== 2) {
            if (isset($result['add_data']) && $result['add_data'] != '') {
                foreach ($result['add_data'] as $k1 => $v1) {
                    $addonhtml .= '<option value="' . $v1['ms_name'] . '"> ' . $v1['ms_name'] . '</option>';
                }
            }
        }


        $query = Centre::query();

        $query = $query->where('centre_id', $cid);

        $centreDetail = $query->get();

        return view('admin.booking.create-booking', array('centreDetail' => $centreDetail, 'configHtml' => $configHtml, 'durhtml' => $durhtml, 'addonhtml' => $addonhtml, 'ms_cat' => $configData['ms_cat'], 'ms_name' => $configData['ms_name'], 'ms_id' => $configData['ms_id']));
    }

    /*

     * View customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function Bookings(Request $request) {


        if (Auth::guard('admin')->user()->admin_role == 1 || Auth::guard('admin')->user()->admin_role == 2) {
            $statusArr = array(1 => 'All', 2 => 'Paid', 3 => 'Pay at centre', 7 => 'Bill to Company', 8 => 'Bill to Company - Paid', 5 => 'Cancelled');
            $serviceArr = array('ABC Lounge' => 'ABC Lounge', 'Built To Suit' => 'Built To Suit', 'Co-Working' => 'Co-Working',
                'Meeting Room' => 'Meeting Room', 'Serviced office' => 'Serviced office', 'Virtual Office' => 'Virtual Office');
            $query = Booking::query();
            if (!empty($request->search)) {

                $search = $request->search;
                $query = $query->where(function($query) use ($search) {
                    $query->orWhere('booking_code', 'LIKE', '%' . $search . '%');
                    $query->orWhere('loc_name', 'LIKE', '%' . $search . '%');
                    $query->orWhere('centre_name', 'LIKE', '%' . $search . '%');
                    $query->orWhere('user_name', 'LIKE', '%' . $search . '%');
                    $query->orWhere('user_email', 'LIKE', '%' . $search . '%');
                    $query->orWhere('user_phone', 'LIKE', '%' . $search . '%');
                });
            }
            if (!empty($request->book_status) && $request->book_status != 1) {
                $query = $query->where('book_status', ($request->book_status));
            }
            if (!empty($request->service_status) && $request->service_status != 1) {
                $query = $query->where('service_type', ($request->service_status));
            }


            if (!empty($request->req_date_range)) {
                $dates = explode('and', $request->req_date_range);
                $from = date("Y-m-d H:i:s", strtotime($dates[0]));
                $to = date("Y-m-d H:i:s", strtotime($dates[1]));
            }
            if (!empty($from) && !empty($to)) {

                $query = $query->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
            }

            $centreData = DB::table('abc_manager_centre_tag')
                    ->select('cid')
                    ->where('mid', Auth::guard('admin')->user()->id)
                    ->get();
            $centre_data = [];
            foreach ($centreData as $key => $value) {
                array_push($centre_data, $value->cid);
            }
            if (Auth::guard('admin')->user()->admin_role != 1) {
                $query = $query->whereIn('centre_id', $centre_data);
            }

            $pageData = $query->orderby('created_at', 'DESC')
                    ->paginate(10)
                    ->withPath('?search=' . $request->search . '&book_status=' . $request->book_status . '&req_date_range=' . $request->req_date_range);
            return view('admin.booking.manage-booking', array('data' => $pageData, 'statusArr' => $statusArr, 'serviceArr' => $serviceArr));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    public function CBookings(Request $request) {

        if (Auth::guard('admin')->user()->admin_role == 1) {
            $statusArr = array(1 => 'All', 2 => 'Pending', 3 => 'Confirm', 4 => 'Pay at centre', 5 => 'Cancelled', 6 => 'Closed');

            $query = Booking::query();
            if (!empty($request->search)) {
                $search = $request->search;
                $query = $query->where(function($query) use ($search) {
                    $query->orWhere('booking_code', 'LIKE', '%' . $search . '%');
                    $query->orWhere('loc_name', 'LIKE', '%' . $search . '%');
                    $query->orWhere('centre_name', 'LIKE', '%' . $search . '%');
                    $query->orWhere('user_name', 'LIKE', '%' . $search . '%');
                    $query->orWhere('user_email', 'LIKE', '%' . $search . '%');
                    $query->orWhere('user_phone', 'LIKE', '%' . $search . '%');
                });
            }
            $query = $query->where('book_status', 4);
            if (!empty($request->req_date_range)) {
                $dates = explode('and', $request->req_date_range);
                $from = date("Y-m-d H:i:s", strtotime($dates[0]));
                $to = date("Y-m-d H:i:s", strtotime($dates[1]));
            }
            if (!empty($from) && !empty($to)) {

                $query = $query->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
            }
            $pageData = $query->orderby('created_at', 'DESC')
                    ->paginate(10)
                    ->withPath('?search=' . $request->search . '&book_status=' . $request->book_status . '&req_date_range=' . $request->req_date_range);
            return view('admin.order_cancellation_rules.cancelled-orders', array('data' => $pageData, 'statusArr' => $statusArr));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function EditBooking(Request $request) {


        $admin_comment = '';
        $package_option = '';
        $book_user_amnt = '';
        $book_user_opt = '';
        $book_hrs = '';
        $book_price = '';
        $book_tot_amnt = '';
        $only_txt_amnt = '';
        $cgst_tax = 0;
        $sgst_tax = 0;
        $igst_tax = 0;
        $hours = 0;

        $taxData = Tax::where('tax_status', 1)->where('tax_cgst_rate', '>', 0)->where('tax_sgst_rate', '>', 0)->first();
        if ($taxData != NULL) {
            $cgst_tax = $taxData['tax_cgst_rate'];
            $sgst_tax = $taxData['tax_sgst_rate'];
            $igst_tax = $cgst_tax + $sgst_tax;
        }

        /* Calculate add on price */
        if (isset($request->book_add_on)) {
            if ($request->flag_hour == 'on') {
                $hours = $request->book_hour;
            }
            if ($request->flag_half == 'on') {
                $hours = 4;
            }
            if ($request->flag_full == 'on') {
                $hours = 8;
            }


            $rateData = Meetingroom::select('ms_hour', 'rr_id', 'ms_name')->where('center_id', $request->centre_id)->where('ms_cat', $request->book_add_on)->first();


            $bookData = Booking:: select('book_date_from', 'book_date_to')->where('booking_id', $request->booking_id)->first();

            if (strtotime(date('Y-m-d')) >= strtotime($bookData['book_date_from']) && strtotime(date('Y-m-d')) <= strtotime($bookData['book_date_to']) && ($request->book_status == 2 || $request->book_status == 3)) {

                $book_price = $hours * $rateData['ms_hour'];
                if ($igst_tax > 0) {
                    $tax_amnt = (float) ($igst_tax / 100);
                    $tax_val = round($book_price * $tax_amnt, 2);
                    $only_txt_amnt = $book_price - $tax_val;
                }


                $timestamp = strtotime(date('H:i')) + (60 * 60 * $hours);

                $book_tot_amnt = $book_price + $tax_val;

                if ($book_tot_amnt > 0) {
                    $rateDataToSave = array('booking_id' => $request->booking_id,
                        'service_id' => $request->book_add_on,
                        'service_name' => $rateData['ms_name'],
                        'is_add_service' => 1,
                        'service_amnt' => $rateData['ms_hour'],
                        'service_tax_amnt' => $only_txt_amnt,
                        'service_tot_amnt' => $book_tot_amnt,
                        'book_date_from' => date('Y-m-d'),
                        'book_date_to' => date('Y-m-d'),
                        'book_time_from' => date("H:i"),
                        'book_time_to' => date("H:i", $timestamp),
                        'book_hrs' => $hours,
                    );
//            dd($rateDataToSave);
                    BookingDetail::create($rateDataToSave)->id;
                }
            } else {
                return redirect()->back()->with('message', 'You can not book add on services after date');
            }
        }
        if (isset($request->admin_cmnt) && $request->admin_cmnt != '') {
            $admin_comment = $request->admin_cmnt;
        }
        if (isset($request->book_user_amnt) && $request->book_user_amnt != '') {
            $book_user_amnt = $request->book_user_amnt;
        }
        if (isset($request->payment_option) && $request->payment_option != '') {
            $book_user_opt = $request->payment_option;
        }
        $data = Booking::Where('booking_id', $request->booking_id)->first();
        if ($request->book_status == 4) {
            $changed_data = array(
                'book_cancel_by' => Auth::id(),
                'book_cancel_status' => 2,
                'book_status' => $request->book_status,
                'status_remarks' => $request->status_remarks,
                'book_cancel_before_status' => $data['book_status'],
                'book_cancl_date' => date("Y-m-d H:i:s"),
                'package_type' => $request->package_option,
            );
        } else {
            $changed_data = array(
                'book_status' => $request->book_status,
                'status_remarks' => $request->status_remarks,
                'updated_at' => date("Y-m-d H:i:s"),
                'package_type' => $request->package_option,
            );
        }

        if ($admin_comment != '') {
            $changed_data_cmnt = array('admin_cmnt' => $admin_comment,
                'payment_amnt' => $book_user_amnt,
                'payment_type' => $book_user_opt,
                'book_id' => $request->booking_id,
            );
            BookingComment::insert($changed_data_cmnt);
        }



        $diff_in_data_to_save = array();
        $diff_in_data = array_diff_assoc($data->getOriginal(), $changed_data);

        $keys_to_be_updated = array_keys($diff_in_data);

        for ($i = 0; $i < count($keys_to_be_updated); $i++) {
            if (isset($changed_data[$keys_to_be_updated[$i]])) {
                $data_to_update[$keys_to_be_updated[$i]] = $changed_data[$keys_to_be_updated[$i]];
                $diff_in_data_to_save[$keys_to_be_updated[$i]] = $diff_in_data[$keys_to_be_updated[$i]];
            }
        }
        $logData = array('subject_id' => $request->booking_id, 'user_id' => Auth::id(), 'table_used' => 'abc_booking',
            'description' => 'update', 'data_prev' => urldecode(http_build_query($diff_in_data_to_save)), 'data_now' => urldecode(http_build_query($changed_data))
        );
        saveQueryLog($logData);
        $status = Booking::Where('booking_id', $request->booking_id)->update($changed_data);
        if ($status) {
            return redirect()->back()->with('message', 'Booking successfully updated');
        } else {
            return redirect()->back()->with('message', 'An error occurred while updating the booking');
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function DeleteBooking($id) {
        $users = Booking::where('booking_id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_ms_booking',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($users->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);

        $status = DB::table('abc_booking')->where('booking_id', base64_decode($id))->delete();
        DB::table('abc_booking_details')->where('booking_id', base64_decode($id))->delete();
        if ($status) {
            return redirect()->back()->with('message', 'Booking successfully deleted');
        } else {
            return redirect()->back()->with('message', 'Error while deleting booking');
        }
    }

    public function GetBookingData(Request $request, $id) {
        if (Auth::guard('admin')->user()->admin_role == 1 || Auth::guard('admin')->user()->admin_role == 2) {
            $flag = 0;
            $cgst_tax = 0;
            $sgst_tax = 0;
            $igst_tax = 0;
            $serNameArr = array();
            $catIdArr = array();
            $statusArr = array(0 => 'All', 2 => 'Paid', 3 => 'Pay at centre', 7 => 'Bill to Company', 8 => 'Bill to Company - Paid', 4 => 'Cancelled');
            $serviceArr = array('ABC Lounge' => 'ABC Lounge', 'Built To Suit' => 'Built To Suit', 'Co-Working' => 'Co-Working',
                'Meeting Room' => 'Meeting Room', 'Serviced office' => 'Serviced office', 'Virtual Office' => 'Virtual Office');
            $bookingData = Booking::where('booking_id', base64_decode($id))->first();
            if ($bookingData) {

                $catData = Category::where('acat_type', 'Add. Service')->get();
                $commentData = BookingComment::where('book_id', base64_decode($id))->get();

                $bDetailsData = BookingDetail::select('service_id', 'service_name', 'booking_details_id', 'book_date_from', 'book_date_to', 'book_time_from', 'book_time_to', 'book_hrs', 'service_tot_amnt')->where('booking_id', base64_decode($id))->get();

                foreach ($bDetailsData as $key => $value) {
                    array_push($catIdArr, getCatIdByService($value->service_id));
                }

                $query = Booking::query();
                if (!empty($request->search)) {
                    $flag = 1;
                    $search = $request->search;
                    $query = $query->where(function($query) use ($search) {
                        $query->orWhere('booking_code', 'LIKE', '%' . $search . '%');
                        $query->orWhere('loc_name', 'LIKE', '%' . $search . '%');
                        $query->orWhere('centre_name', 'LIKE', '%' . $search . '%');
                        $query->orWhere('user_name', 'LIKE', '%' . $search . '%');
                        $query->orWhere('user_email', 'LIKE', '%' . $search . '%');
                        $query->orWhere('user_phone', 'LIKE', '%' . $search . '%');
                    });
                }
                if (!empty($request->book_status) && $request->book_status != 1) {
                    $flag = 1;
                    $query = $query->where('book_status', ($request->book_status));
                }

                if (!empty($request->service_status) && $request->service_status != 1) {
                    $query = $query->where('service_type', ($request->service_status));
                }
                if (!empty($request->req_date_range)) {
                    $flag = 1;
                    $dates = explode('and', $request->req_date_range);
                    $from = date("Y-m-d H:i:s", strtotime($dates[0]));
                    $to = date("Y-m-d H:i:s", strtotime($dates[1]));
                }
                if (!empty($from) && !empty($to)) {

                    $query = $query->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
                }
                if (Auth::guard('admin')->user()->admin_role != 1) {

                    $centreData = DB::table('abc_manager_centre_tag')
                            ->select('cid')
                            ->where('mid', Auth::guard('admin')->user()->id)
                            ->get();
                    $centre_data = [];
                    foreach ($centreData as $key => $value) {
                        array_push($centre_data, $value->cid);
                    }
                    $query = $query->where('centre_id', $centre_data);
                }

                $taxData = Tax::where('tax_status', 1)->where('tax_cgst_rate', '>', 0)->where('tax_sgst_rate', '>', 0)->first();
                if ($taxData != NULL) {
                    $cgst_tax = $taxData['tax_cgst_rate'];
                    $sgst_tax = $taxData['tax_sgst_rate'];
                    $igst_tax = $cgst_tax + $sgst_tax;
                }

                $pageData = $query->orderby('created_at', 'DESC')
                        ->paginate(10)
                        ->withPath('?search=' . $request->search . '&book_status=' . $request->book_status . '&req_date_range=' . $request->req_date_range);
                if ($flag) {
                    return view('admin.booking.manage-booking', array('data' => $pageData, 'statusArr' => $statusArr, 'serviceArr' => $serviceArr));
                } else {

                    return view('admin.booking.edit-booking', array('data' => $pageData, 'bookingData' => $bookingData, 'commentData' => $commentData, 'catData' => $catData, 'catIdArr' => $catIdArr, 'statusArr' => $statusArr, 'bdetails' => $bDetailsData, 'tax' => $igst_tax, 'serviceArr' => $serviceArr));
                }
            } else {
                if (Auth::guard('admin')->user()->admin_role == 1 || Auth::guard('admin')->user()->admin_role == 2) {
                    $statusArr = array(0 => 'All', 2 => 'Paid', 3 => 'Pay at centre', 7 => 'Bill to Company', 8 => 'Bill to Company - Paid', 4 => 'Cancelled');
                    $serviceArr = array('ABC Lounge' => 'ABC Lounge', 'Built To Suit' => 'Built To Suit', 'Co-Working' => 'Co-Working',
                        'Meeting Room' => 'Meeting Room', 'Serviced office' => 'Serviced office', 'Virtual Office' => 'Virtual Office');
                    $query = Booking::query();
                    if (!empty($request->search)) {

                        $search = $request->search;
                        $query = $query->where(function($query) use ($search) {
                            $query->orWhere('booking_code', 'LIKE', '%' . $search . '%');
                            $query->orWhere('loc_name', 'LIKE', '%' . $search . '%');
                            $query->orWhere('centre_name', 'LIKE', '%' . $search . '%');
                            $query->orWhere('user_name', 'LIKE', '%' . $search . '%');
                            $query->orWhere('user_email', 'LIKE', '%' . $search . '%');
                            $query->orWhere('user_phone', 'LIKE', '%' . $search . '%');
                        });
                    }
                    if (!empty($request->book_status) && $request->book_status != 1) {
                        $query = $query->where('book_status', ($request->book_status - 1));
                    }
                    if (!empty($id) && $id != 1) {
                        $query = $query->whereIn('book_status', [2, 3]);
                    }
                    if (!empty($request->service_status) && $request->service_status != 1) {
                        $query = $query->where('service_type', ($request->service_status));
                    }
                    if (!empty($request->req_date_range)) {
                        $dates = explode('and', $request->req_date_range);
                        $from = date("Y-m-d H:i:s", strtotime($dates[0]));
                        $to = date("Y-m-d H:i:s", strtotime($dates[1]));
                    }
                    if (!empty($from) && !empty($to)) {

                        $query = $query->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
                    }
                    if (Auth::guard('admin')->user()->admin_role != 1) {

                        $centreData = DB::table('abc_manager_centre_tag')
                                ->select('cid')
                                ->where('mid', Auth::guard('admin')->user()->id)
                                ->get();
                        $centre_data = [];
                        foreach ($centreData as $key => $value) {
                            array_push($centre_data, $value->cid);
                        }
                        $query = $query->whereIn('centre_id', $centre_data);
                    }

                    $pageData = $query->orderby('created_at', 'DESC')
                            ->paginate(10)
                            ->withPath('?search=' . $request->search . '&book_status=' . $request->book_status . '&req_date_range=' . $request->req_date_range);
                    return view('admin.booking.manage-booking', array('data' => $pageData, 'statusArr' => $statusArr, 'serviceArr' => $serviceArr));
                } else {
                    return redirect('admin/dashboard')->with('message', 'Not Allowed');
                }
            }
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    public function GetBookingDetailsData(Request $request, $id) {
        if (Auth::guard('admin')->user()->admin_role == 1) {

            //dd($bookingData);
            $query = BookingDetail::query();
            if (!empty($request->search)) {
                $search = $request->search;
                $query = $query->where(function($query) use ($search) {
                    $query->orWhere('service_name', 'LIKE', '%' . $search . '%');
                });
            }
            if (!empty($request->req_date_range)) {
                $dates = explode('and', $request->req_date_range);
                $from = date("Y-m-d H:i:s", strtotime($dates[0]));
                $to = date("Y-m-d H:i:s", strtotime($dates[1]));
            }
            if (!empty($from) && !empty($to)) {

                $query = $query->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
            }
            $query = $query->where('booking_id', base64_decode($id));
            $pageData = $query->orderby('created_at', 'DESC')
                    ->paginate(10)
                    ->withPath('?search=' . $request->search . '&book_status=' . $request->book_status . '&req_date_range=' . $request->req_date_range);
            return view('admin.booking.view-booking-details', array('data' => $pageData));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    public function GetCentreData(Request $request) {
        $data = Centre::Where('location', $request->loc_id)->get();
        $html = '<option value="" >Select Centre</option>';

        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                $locnm = getLocationName($value->location);
                $html .= '<option value="' . $value->centre_id . '">' . $value->centre . '(' . $locnm->loc_name . ')' . '</option>';
            }
            echo json_encode(array('status' => 1, 'html' => $html));
        } else {
            echo json_encode(array('status' => 0));
        }
    }

    public function CheckCoBookingAvailable(Request $request) {
        //dd($request->all());
        $ravl = 0;

        $Security_enable = 0;
        $security_deposite = 0;
        $tot_room_cost = 0;
        $total_with_tax = 0;
        $error = '';
        $cgst_tax = 0;
        $sgst_tax = 0;
        $igst_tax = 0;

        $vat_taxlbl = 'GST';
        $taxData = Tax::where('tax_status', 1)->where('tax_cgst_rate', '>', 0)->where('tax_sgst_rate', '>', 0)->first();
        if ($taxData != NULL) {
            $cgst_tax = $taxData['tax_cgst_rate'];
            $sgst_tax = $taxData['tax_sgst_rate'];
            $igst_tax = $cgst_tax + $sgst_tax;
        }
        $tax_val = '0';

        $center = '';
        if (isset($request->centre_id) && $request->centre_id != '')
            $center = $request->centre_id;
        $service_configuration = '';
        if (isset($request->location) && $request->location != '')
            $location = $request->location;
        $date_from = '';
        $date_to = '';
        $date_range = '';
        $dateTimeArr = '';
        $dateFromArr = '';
        $dateToArr = '';

        if (isset($request->book_date_range) && $request->book_date_range != '') {
            $date_range = $request->book_date_range;
            $dateTimeArr = explode('and', $date_range);
            $date_from = $dateTimeArr[0];
            $date_to = $dateTimeArr[1];
            $dateFromArr = explode(' ', $date_from);
            $dateToArr = explode(' ', $date_to);
            $time_from = $dateFromArr[1] . ' ' . $dateFromArr[2];
            $time_to = $dateToArr[1] . ' ' . $dateToArr[2];
        }
        $co_type = '';
        if (isset($request->co_type) && $request->co_type != '')
            $co_type = $request->co_type;
        $booking_type = '';
        if (isset($request->booking_type) && $request->booking_type != '')
            $booking_type = $request->booking_type;

        if ($location == '' || $center == '' || $date_range == '') {
            echo 'Error';
            exit;
        }
        $timezone = 5.5; //(GMT -5:00) EST (U.S. & Canada)
        $getsysdate = gmdate("Y-m-d", time() + 3600 * ($timezone + date("I")));
        $daydiff = abs(strtotime($date_from) - strtotime($date_to));
        $Days = (int) ($daydiff / 86400);
        $Days = $Days + 1;


        $mindiff = abs(strtotime($getsysdate . ' ' . $time_from) - strtotime($getsysdate . ' ' . $time_to));
        $Hours = (($mindiff % 86400 ) / 3600);
        $Mins = ($mindiff / 60 );


        if ($Days == 1) {
            if ($Hours < 3)
                $chrg_val = 'ms_hour';
            else if ($Hours <= 4)
                $chrg_val = 'ms_half';
            if ($Hours > 4)
                $chrg_val = 'ms_full';
        } else if ($Days > 1 && $Days < 335) {
            if ($co_type == 6)
                $chrg_val = 'nw_month';
            else if ($co_type == 7)
                $chrg_val = 'fs_month';
            else
                $chrg_val = 'ms_month';
//            $Security_enable = 1;
        } else {
            $chrg_val = 'ms_year';
//            $Security_enable = 1;
        }



        $month = ceil($Days / 30);
        if ($month == 12)
            $month = 11;
        $cat_id = 0;
        $cat_id = getCategoryIdByName($booking_type);


        $rate_data = Meetingroom::select('rr_no', 'ms_id', 'ms_hour', 'ms_half', 'ms_full', 'ms_month', 'ms_year', 'nw_month', 'fs_month')->where('center_id', $center)->where('ms_cat', $cat_id)->first();

        $room_book_check = DB::select("select rb_id from abc_room_booking where center_id = '" . $center . "' and cat_id = 5 and booking_date between '" . $date_from . "' and '" . $date_to . "' and (st_time between '" . $time_from . "' and '" . $time_to . "' or end_time between '" . $time_from . "' and '" . $time_to . "') ");


        if (count($room_book_check) >= $rate_data['rr_no']) {
            echo 'Error';
            exit;
        } else {
            if ($chrg_val == 'ms_hour') {
                $tot_room_cost = ($rate_data['ms_hour'] * $Hours);
            } else if ($chrg_val == 'nw_month') {
                $tot_room_cost = ($rate_data['nw_month'] * $month);
            } else if ($chrg_val == 'fs_month') {
                $tot_room_cost = ($rate_data['fs_month'] * $month);
            } else if ($chrg_val == 'ms_month') {
                $tot_room_cost = ($rate_data['ms_month'] * $month);
            } else {
                $tot_room_cost = ($rate_data[$chrg_val]);
            }
        }

        if ($Security_enable == 1) {
            $security_deposite = ($tot_room_cost * 20 / 100);
        }
        $tot_booking_val = $tot_room_cost;
        if ($igst_tax > 0) {
            $tax_amnt = (float) ($igst_tax / 100);

            $tax_val = round($tot_booking_val * $tax_amnt, 2);
        }

        $total_with_tax = $tot_booking_val + $tax_val + $security_deposite;

        echo $total_with_tax;
        exit;
    }

    public function ExportBooking(Request $request) {
        //dd($request->all());
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=" . date('Y-m-d-H-i-s') . "-booking.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $query = DB::table('abc_booking')
                ->select('booking_code', 'loc_name', 'centre_name', 'add_date', 'service_type', 'ser_config', 'book_date_from',
                'book_date_to', 'book_time_from', 'book_time_to', 'user_name', 'user_email', 'user_phone', 'user_address',
                'book_charge_amnt', 'book_tax_amnt', 'tot_book_amnt', 'book_status', 'book_cancel_by',
                'book_cancl_date'
        ); //'mscode.MsCode', 'mscode.V8Key'

        if (!empty($request->search)) {

            $search = $request->search;
            $query = $query->where(function($query) use ($search) {
                $query->orWhere('booking_code', 'LIKE', '%' . $search . '%');
                $query->orWhere('loc_name', 'LIKE', '%' . $search . '%');
                $query->orWhere('centre_name', 'LIKE', '%' . $search . '%');
                $query->orWhere('user_name', 'LIKE', '%' . $search . '%');
                $query->orWhere('user_email', 'LIKE', '%' . $search . '%');
                $query->orWhere('user_phone', 'LIKE', '%' . $search . '%');
            });
        }
        if (!empty($request->book_status) && $request->book_status != 1) {
            $query = $query->where('book_status', ($request->book_status));
        }
        if (!empty($id) && $id != 1) {
            $query = $query->whereIn('book_status', $request->book_status);
        }

        if (!empty($request->req_date_range)) {
            $dates = explode('and', $request->req_date_range);
            $from = date("Y-m-d H:i:s", strtotime($dates[0]));
            $to = date("Y-m-d H:i:s", strtotime($dates[1]));
        }
        if (!empty($from) && !empty($to)) {

            $query = $query->whereDate('add_date', '>=', $from)->whereDate('add_date', '<=', $to);
        }
        $reqData = $query->orderBy('created_at', 'ASC')
                ->get();

        //dd($reqData);

        $columns = array("Booking Code", "Location", "Center Name", "Date ", "Service Type", "Service Config", "Booking Date From",
            "Booking Date To", "Booking Time From", "Booking Time To", "User Name", "User Email", "User Phone", "User Address", "Booking charge",
            "Booking Tax Amount", "Total Booking Amount", "Booking Status", "Cancel By", "Cancel Date");


        $callback = function() use ($reqData, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            //dd($reqData);

            foreach ($reqData as $review) {
                $book_status = '';

                if ($review->book_status == 2) {
                    $book_status = 'Paid';
                } else if ($review->book_status == 3) {
                    $book_status = 'Pay at centre';
                } else if ($review->book_status == 7) {
                    $book_status = 'Bill to Company';
                } else if ($review->book_status == 8) {
                    $book_status = 'Bill to Company - Paid';
                } else if ($review->book_status == 4) {
                    $book_status = 'Cancelled';
                }
                fputcsv($file, array($review->booking_code, $review->loc_name, $review->centre_name, $review->add_date, $review->service_type, $review->ser_config, $review->book_date_from,
                    $review->book_date_to, $review->book_time_from, $review->book_time_to, $review->user_name, $review->user_email, $review->user_phone, $review->user_address,
                    $review->book_charge_amnt, $review->book_tax_amnt, $review->tot_book_amnt, $book_status, $review->book_cancel_by,
                    $review->book_cancl_date));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function getPackagePrice(Request $request) {

        $type = 0;
        $query = DB::table('abc_room_rate');
        if ($request->ptype == 'Daily') {
            $query = $query->select('ms_full');
        }
        if ($request->ptype == 'Monthly') {
            $query = $query->select('ms_month');
        }
        if ($request->ptype == 'Quaterly') {
            $query = $query->select('ms_pln_quart');
        }
        if ($request->ptype == 'Yearly') {
            $query = $query->select('ms_pln_yr');
        }
        $data = $query->where('center_id', $request->cid)->where('ms_name', $request->ms_name)->get();
        //$query =

        if (count($data) > 0) {
            echo json_encode(array('status' => 1, 'data' => $data));
        } else {
            echo json_encode(array('status' => 0));
        }
    }

}
