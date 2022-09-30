<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use App\Modal\Discount;
use App\Modal\DiscountCustomer;
use App\Modal\Category;
use App\Modal\Customer;

class DiscountController extends Controller {
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
    public function AddNewDiscount(Request $request) {
//        dd($request->all());
        $validation = Validator::make($request->all(), [
                    'd_code' => 'required',
                    'd_cust' => 'required',
                    'd_amnt' => 'required',
                    'd_min_ordr_amnt' => 'required',
                    'd_max_consumed' => 'required',
                    'd_status' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('d_code', 'd_cust', 'd_min_ordr_amnt', 'd_amnt', 'd_status'));
        }
        $status = Discount::where('d_code', $request->d_code)->first();

        if ($status) {
            return redirect()->back()->withErrors(['message', 'Discount code already exists']);
        }
        $d_catid = 0;
        if (isset($request->d_catid) && !empty($request->d_catid)) {
            $d_catid = implode(',', $request->d_catid);
        }
        $d_cust_type = 1;
        if ($request->d_cust == 'all') {
            $d_cust_type = 1;
        } else if ($request->d_cust == 'select_cust') {
            $d_cust_type = 2;
        }else{
            $d_cust_type = 3;
        }
        

        $dataToSave = array('d_code' => $request->d_code,
            'd_cat' => $d_catid,
            'd_amnt' => $request->d_amnt,
            'd_min_ordr_amnt' => $request->d_min_ordr_amnt,
            'd_max_ofr_amnt' => $request->d_max_ofr_amnt,
            'd_max_consumed' => $request->d_max_consumed,
            'd_status' => $request->d_status,
            'd_cust_type' => $d_cust_type,
        );
        
//        dd($dataToSave);
        $id = Discount::create($dataToSave)->id;
//        $id = 1;
        if ($request->d_cust == 'all') {
            $custdata = Customer::select('cust_id', 'cust_nme', 'cust_mobile')->where('cust_status', 1)->get();
            foreach ($custdata as $key => $value) {
                $discountCustData = array('dc_custid' => $value['cust_id'], 'dc_custmob' => $value['cust_mobile'], 'dc_did' => $id, 'dc_max_consumed' => $request->d_max_consumed);
                DiscountCustomer::create($discountCustData);
                $URLtext = "Dear+" . str_replace(" ", "+", $value['cust_nme']) . ",+Your+coupon+code+has+been+created+.%0ACoupon+Code:+" . str_replace(" ", "+", $request->d_code) . ".%0ALog+on+to+ABC+app";
                if (strlen($value['cust_mobile']) == 10) {
                    $URLmob = "91" . $value['cust_mobile'];
                } else {
                    $URLmob = $value['cust_mobile'];
                }
                $URLsms = "";
                $URLsms = $URLsms . $URLmob . "&Text=" . $URLtext;
                if ($_SERVER['HTTP_HOST'] != 'localhost') {
                    get_sms($URLsms);
                }
            }
        } else if ($request->d_cust == 'select_cust') {

            $selectedCust = $request->d_custid;
            // $custMob = explode(',',$request->cust_mob);

            for ($i = 0; $i < count($selectedCust); $i++) {
                $custdata = Customer::select('cust_id', 'cust_nme', 'cust_mobile')->where('cust_status', 1)->where('cust_id', $selectedCust[$i])->first();
                $discountCustData = array('dc_custid' => $custdata['cust_id'], 'dc_custmob' => $custdata['cust_mobile'], 'dc_did' => $id, 'dc_max_consumed' => $request->d_max_consumed);
                DiscountCustomer::create($discountCustData);
                $URLtext = "Dear+" . str_replace(" ", "+", $custdata['cust_nme']) . ",+Your+coupon+code+has+been+created+.%0ACoupon+Code:+" . str_replace(" ", "+", $request->d_code) . ".%0ALog+on+to+ABC+app";
                if (strlen($custdata['cust_mobile']) == 10) {
                    $URLmob = "91" . $custdata['cust_mobile'];
                } else {
                    $URLmob = $custdata['cust_mobile'];
                }
                $URLsms = "";
                $URLsms = $URLsms . $URLmob . "&Text=" . $URLtext;
                if ($_SERVER['HTTP_HOST'] != 'localhost') {
                    get_sms($URLsms);
                }
            }
        } else {
            $custMob = explode(',', $request->cust_mob);
            for ($i = 0; $i < count($custMob); $i++) {
                $custdata = Customer::select('cust_id', 'cust_nme', 'cust_mobile')->where('cust_status', 1)->where('cust_mobile', $custMob[$i])->first();

                if (is_null($custdata)) {
                    $discountCustData = array('dc_custid' => 0, 'dc_custmob' => $custMob[$i], 'dc_did' => $id, 'dc_max_consumed' => $request->d_max_consumed);
                    $URLtext = "Dear+,+Your+coupon+code+has+been+created+.%0ACoupon+Code:+" . str_replace(" ", "+", $request->d_code) . ".%0ALog+on+to+ABC+app";
                } else {
                    $discountCustData = array('dc_custid' => $custdata['cust_id'], 'dc_custmob' => $custMob[$i], 'dc_did' => $id, 'dc_max_consumed' => $request->d_max_consumed);
                    $URLtext = "Dear+,+Your+coupon+code+has+been+created+.%0ACoupon+Code:+" . str_replace(" ", "+", $request->d_code) . ".%0ALog+on+to+ABC+app";
                }

                DiscountCustomer::create($discountCustData);

                if (strlen($custMob[$i]) == 10) {
                    $URLmob = "91" . $custMob[$i];
                } else {
                    $URLmob = $custMob[$i];
                }
                $URLsms = "";
                $URLsms = $URLsms . $URLmob . "&Text=" . $URLtext;
                if ($_SERVER['HTTP_HOST'] != 'localhost') {
                    get_sms($URLsms);
                }
            }
        }
        $logData = array('subject_id' => $id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_discount',
            'description' => 'insert', 'data_prev' => '', 'data_now' => urldecode(http_build_query($dataToSave))
        );
        saveQueryLog($logData);
        if ($id) {
            return redirect()->back()->with('message', 'Discount successfully created');
        } else {
            return redirect()->back()->with('message', 'Error while creating a discount');
        }
    }

    /*

     * View list 
     * Sanjit Bhardwaj
     * 11-01-2018
     */

    public function Discounts() {
        if (Auth::guard('admin')->user()->admin_role == 1) {
            $pageData = Discount::orderBy('created_at', 'DESC')->get();
            $catData = Category::where('acat_type', 'Service')->orderBy('acat_name', 'ASC')->get();
            $custData = Customer::where('cust_status', 1)->orderBy('cust_nme', 'ASC')->get();
            $discountCode = promo_code(10);
            return view('admin.discount.manage-discount', array('data' => $pageData, 'discountCode' => $discountCode, 'catData' => $catData, 'custData' => $custData));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    /*

     * Edit data
     * Sanjit Bhardwaj
     * 11-01-2018
     */

    public function EditDiscount(Request $request) {
        // dd($request->all());

        $validation = Validator::make($request->all(), [
                    'd_code' => 'required',
                    'req_date_range' => 'required',
                    'd_loc' => 'required',
                    'd_amnt' => 'required',
                    'd_min_ordr_amnt' => 'required',
                    'd_status' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('d_code', 'req_date_range', 'd_loc', 'd_min_ordr_amnt', '', 'd_status'));
        }
        $from = '';
        $to = '';
        $dates = explode('and', $request->req_date_range);
        $from = date("Y-m-d H:i:s", strtotime($dates[0]));
        $to = date("Y-m-d H:i:s", strtotime($dates[1]));


        $status = Discount::Where('d_code', '=', $request->d_code)
                ->Where('d_id', '!=', $request->d_id)
                ->first();


        $data = DB::table('abc_ms_discount')->where('d_id', '=', $request->d_id)->get()->toArray();

        $name = '';
        $d_catid = 0;
        if (isset($request->d_catid) && !empty($request->d_catid)) {
            $d_catid = implode(',', $request->d_catid);
        }
        $changed_data = array('d_code' => $request->d_code,
            'd_date_start' => $from,
            'd_date_end' => $to,
            'd_loc' => implode(',', $request->d_loc),
            'd_catid' => $d_catid,
            'd_amnt' => $request->d_amnt,
            'd_min_ordr_amnt' => $request->d_min_ordr_amnt,
            'd_max_consumed' => $request->d_max_consumed,
            'd_status' => $request->d_status
        );
        ;

        $diff_in_data_to_save = array();
        $diff_in_data = array_diff_assoc($data, $changed_data);

        $keys_to_be_updated = array_keys($diff_in_data);

        for ($i = 0; $i < count($keys_to_be_updated); $i++) {
            if (isset($changed_data[$keys_to_be_updated[$i]])) {
                $data_to_update[$keys_to_be_updated[$i]] = $changed_data[$keys_to_be_updated[$i]];
                $diff_in_data_to_save[$keys_to_be_updated[$i]] = $diff_in_data[$keys_to_be_updated[$i]];
            }
        }
        $logData = array('subject_id' => $request->d_id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_discount',
            'description' => 'update', 'data_prev' => urldecode(http_build_query($diff_in_data_to_save)), 'data_now' => urldecode(http_build_query($changed_data))
        );
        saveQueryLog($logData);

        $status = Discount::Where('d_id', $request->d_id)->update($changed_data);
        //$status = Ad::find($request->id)->update($changed_data);
        if ($status) {
            return redirect('admin/view-discount')->with('message', 'Discount successfully updated');
        } else {
            return redirect()->back()->with('message', 'Error while updating the discount');
        }
    }

    /*

     * Edit data
     * Sanjit Bhardwaj
     * 11-01-2018
     */

    public function DeleteDiscount($id) {
        $data = Discount::where('d_id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_ms_discount',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($data->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);
        $status = DB::table('abc_ms_discount')->where('d_id', base64_decode($id))->delete();
        if ($status) {
            return redirect('admin/view-discount')->with('message', 'Discount successfully deleted');
        } else {
            return redirect()->back()->with('message', 'Error while deleting a discount');
        }
    }

    public function GetDiscountData(Request $request) {

        $cat = Discount::Where('d_id', base64_decode($request->id))->first();
        $data = array(
            'd_id' => $cat['d_id'],
            'd_code' => $cat['d_code'],
            'd_date_start' => $cat['d_date_start'],
            'd_date_end' => $cat['d_date_end'],
            'd_loc' => $cat['d_loc'],
            'd_catid' => $cat['d_catid'],
            'd_amnt' => $cat['d_amnt'],
            'd_min_ordr_amnt' => $cat['d_min_ordr_amnt'],
            'd_max_consumed' => $cat['d_max_consumed'],
            'd_status' => $cat['d_status'],
        );
        echo json_encode($data);
    }
    
    
    public function GetDiscountDetails(Request $request){
        $pageData = Discount::orderBy('created_at', 'DESC')->get();
        $data = Discount::Where('d_id', base64_decode($request->id))->first();
        $discountData = DiscountCustomer::Where('dc_did', base64_decode($request->id))->get();
        return view('admin.discount.get-discount-details', array('data' => $pageData,'discountData' => $data, 'discountDetails' => $discountData));
    }

}
