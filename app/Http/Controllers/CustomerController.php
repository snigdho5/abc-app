<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use Mail;
use App\Modal\Admin;
use App\Modal\Customer;
use App\Modal\Location;
use App\Modal\CompanyClient;
use App\Modal\CompanyOffer;

class CustomerController extends Controller {
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
    public function AddNewCustomer(Request $request) {

        //dd($request->all());
        $prefix = "CUABC";
        $validation = Validator::make($request->all(), [
                    'cust_nme' => 'required',
                    'cust_email' => 'required|email',
                    'cust_mobile' => 'required|numeric',
                    'custloc' => 'required',
                    'cust_service_add1' => 'required',
                    'cust_status' => 'required'
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('cust_nme', 'cust_email', 'cust_mobile', 'cust_loc', 'cust_service_add1', 'cust_service_add2', 'cust_status', 'cust_pin', 'cust_landmark'));
        }

        $users = Customer::Where('cust_mobile', $request->cust_mobile)->Orwhere('cust_email', $request->cust_email)->first();
        $admin = Admin::Where('mobile', $request->cust_mobile)->Orwhere('email', $request->cust_email)->first();

        if ($users || $admin) {
            return redirect()->back()->withErrors(['message', 'Mobile Number/Email already exists. Please use a different Mobile Number/Email']);
        } else {
            $dataToSave = array('cust_nme' => $request->cust_nme,
                'cust_email' => $request->cust_email,
                'cust_mobile' => $request->cust_mobile,
                'custloc' => $request->custloc,
                'cust_service_add1' => $request->cust_service_add1,
                'cust_service_add2' => $request->cust_service_add2,
                'cust_dob' => date('Y-m-d', strtotime($request->cust_dob)),
                'cust_comp' => $request->cust_comp,
                'cust_desig' => $request->cust_desig,
                'cust_landmark' => $request->cust_landmark,
                'cust_pin' => $request->cust_pin,
                'cust_status' => $request->cust_status,
            );


            $compdataToSave = array('comp_contact_person' => $request->cust_nme,
                'comp_email' => $request->cust_email,
                'comp_phone' => $request->cust_mobile,
                'comp_add' => $request->cust_service_add1 . ' ' . $request->cust_service_add2 . ' ' . $request->cust_pin,
                'comp_name' => $request->cust_comp,
                'comp_status' => 2,
            );

            // Company::create($compdataToSave);

            $id = Customer::create($dataToSave)->id;
            $custPwd = rand_pass(6);

            $data = array('custName' => $request->cust_nme, 'mobileNumber' => $request->cust_mobile, 'password' => $custPwd, 'siteurl' => 'https://www.apeejaybusinesscentre.com/');
            $URLtext = "Dear+" . str_replace(" ", "+", $request->cust_nme) . ",+Your+account+has+been+created+.%0AUser+ID:+" . str_replace(" ", "+", $request->cust_mobile) . ".%0ALog+on+to+ABC+app";
            if (strlen($request->cust_mobile) == 10)
                $URLmob = "91" . $request->cust_mobile;
            else
                $URLmob = $request->cust_mobile;
            $URLsms = "";
            $URLsms = $URLsms . $URLmob . "&Text=" . $URLtext;
            if ($_SERVER['HTTP_HOST'] != 'localhost') {
                get_sms($URLsms);
                $retval = Mail::send('admin.emails.user_new_registration', $data, function ($message) use ($request) {

                            $message->from('noreply@apeejaybusinesscentre.com', 'Apeejay Business Centre');
                            $message->to($request->cust_email);
                            $message->subject('ABC : New User Registration');
                        });
            }

            $idTosave = 1000 + $id;
            $dataTosave = array('cust_code' => $prefix . date("y") . $idTosave, 'cust_pwd' => md5($custPwd));

            $logData = array('subject_id' => $id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_cust',
                'description' => 'insert', 'data_prev' => '', 'data_now' => urldecode(http_build_query($request->all()))
            );

            saveQueryLog($logData);
            if ($id) {
                Customer::where('cust_id', $id)->update($dataTosave);
                return redirect()->back()->with('message', 'Customer successfully created');
            } else {
                return redirect()->back()->with('message', 'Error while creating customer');
            }
        }
        $data = Customer::orderBy('created_at', 'DESC')->get();
        return view('admin.customer.view-customers', array('data' => $data));
    }

    /*

     * View customers 
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function Customers(Request $request) {

        if (Auth::guard('admin')->user()->admin_role == 1) {
            $locationData = Location::orderBy('loc_name', 'ASC')->get();
            $query = Customer::query();
            if (!empty($request->search)) {
                $search = $request->search;

                $query = $query->where(function($query) use ($search) {
                    $query->orWhere('cust_nme', 'LIKE', '%' . $search . '%');
                    $query->orWhere('cust_email', 'LIKE', '%' . $search . '%');
                    $query->orWhere('cust_mobile', 'LIKE', '%' . $search . '%');
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
            $query = $query->where('comp_flag', '=', 0);
            $pageData = $query->orderby('created_at', 'DESC')
                    ->paginate(10)
                    ->withPath('?search=' . $request->search . '&req_date_range=' . $request->req_date_range);
            return view('admin.customer.view-customers', array('data' => $pageData, 'locationData' => $locationData));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function EditCustomer(Request $request) {
        //dd($request->all());
        DB::connection()->enableQueryLog();
        $validation = Validator::make($request->all(), [
                    'cust_nme' => 'required',
                    'cust_email' => 'required|email',
                    'custloc' => 'required',
                    'cust_service_add1' => 'required',
                    //'cust_service_add2' => 'required',  
                    'cust_status' => 'required'
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('cust_nme', 'cust_email', 'cust_mobile', 'cust_loc', 'cust_service_add1', 'cust_service_add2', 'cust_status', 'cust_pin', 'cust_landmark'));
        }
        $cust_mobile = $request->cust_mobile;
        $cust_email = $request->cust_email;

        $users = Customer::where(function($query) use($cust_mobile, $cust_email) {
                    $query->where('cust_mobile', $cust_mobile)
                    ->orWhere('cust_email', $cust_email);
                })
                ->where('cust_id', '!=', $request->cust_id)
                ->first();

        $admin = Admin::Where('mobile', $request->cust_mobile)->Orwhere('email', $request->cust_email)->first();

        if ($users || $admin) {
            return redirect()->back()->withErrors(['message', 'Mobile Number/Email already exists. Please use a different Mobile Number/Email']);
        } else {
            $users = Customer::where('cust_id', $request->cust_id)->first();
//            if (isset($request->sendpwd) && $request->sendpwd == 1) {
//                $userPwd = rand_pass(6);
//               
//            }
            $changed_data = $request->all();
            $diff_in_data = array_diff_assoc($users->getOriginal(), $request->all());

            $keys_to_be_updated = array_keys($diff_in_data);

            $data_to_update = [];
            $diff_in_data_to_save = [];
            for ($i = 0; $i < count($keys_to_be_updated); $i++) {
                if (isset($changed_data[$keys_to_be_updated[$i]])) {
                    $data_to_update[$keys_to_be_updated[$i]] = $changed_data[$keys_to_be_updated[$i]];
                    $diff_in_data_to_save[$keys_to_be_updated[$i]] = $diff_in_data[$keys_to_be_updated[$i]];
                }
            }

            $logData = array('subject_id' => $request->cust_id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_cust',
                'description' => 'update', 'data_prev' => urldecode(http_build_query($diff_in_data_to_save)), 'data_now' => urldecode(http_build_query($data_to_update))
            );

            saveQueryLog($logData);
            $data_to_update = array('cust_nme' => $request->cust_nme,
                'cust_email' => $request->cust_email,
                'cust_mobile' => $request->cust_mobile,
                'custloc' => $request->custloc,
                'cust_service_add1' => $request->cust_service_add1,
                'cust_service_add2' => $request->cust_service_add2,
                'cust_dob' => date('Y-m-d', strtotime($request->cust_dob)),
                'cust_comp' => $request->cust_comp,
                'cust_desig' => $request->cust_desig,
                'cust_landmark' => $request->cust_landmark,
                'cust_pin' => $request->cust_pin,
                'cust_status' => $request->cust_status,
            );
//            if (isset($userPwd) && !empty($userPwd)) {
//                $data_to_update['cust_pwd'] = md5($userPwd);
//            }
            $updateUser = Customer::where('cust_id', $request->cust_id)->update($data_to_update);

            if ($updateUser) {
                return redirect('admin/view-customer')->with('message', 'Customer successfully updated');
            } else {
                return redirect()->back()->with('message', 'Error while updating customer');
            }
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function DeleteCustomer($id) {
        $users = Customer::where('cust_id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_ms_cust',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($users->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);

        $status = DB::table('abc_ms_cust')->where('cust_id', base64_decode($id))->delete();
        if ($status) {
            return redirect('admin/view-customer')->with('message', 'Customer successfully deleted');
        } else {
            return redirect()->back()->with('message', 'Error while deleting customer');
        }
    }

    public function GetCustomerData(Request $request) {

        $users = Customer::Where('cust_id', base64_decode($request->id))->first();

        $data = array(
            'id' => $users['cust_id'],
            'name' => $users['cust_nme'],
            'email' => $users['cust_email'],
            'user_id' => $users['cust_code'],
            'mobile' => $users['cust_mobile'],
            'location' => $users['custloc'],
            'service_add1' => $users['cust_service_add1'],
            'service_add2' => $users['cust_service_add2'],
            'cust_landmark' => $users['cust_landmark'],
            'cust_pin' => $users['cust_pin'],
            'cust_comp' => $users['cust_comp'],
            'cust_desig' => $users['cust_desig'],
            'cust_dob' => $users['cust_dob'],
            'status' => $users['cust_status'],
        );

        echo json_encode($data);
    }

    function SearchCustomer(Request $request) {

        if ($request->get('query')) {
            $query = DB::table('abc_ms_cust')
                    ->select('cust_id', 'cust_email');
            $ser = $request->get('query');
            $query = $query->where(function($query) use ($ser) {
                // $query->where('cust_nme', 'LIKE', '%' . $ser . '%');
                // $query->orWhere('cust_mobile', 'LIKE', '%' . $ser . '%');
                $query->orWhere('cust_email', 'LIKE', '%' . $ser . '%');
            });
            $data = $query->limit(10)->get();
            if (count($data) > 0) {
                $output = '<ul class="dropdown-menu usrlist" style="display:block; position:relative">';
                foreach ($data as $row) {
                    $output .= '
       <li class="userlist" id="' . base64_encode($row->cust_id) . '"><a href="#" >' . $row->cust_email . '</a></li>
       ';
                }
                $output .= '</ul>';
                echo $output;
            } else {
                echo "No Customers Found";
            }
        }
    }

    public function SCustomers(Request $request) {

        if (Auth::guard('admin')->user()->admin_role == 1) {
            $locationData = Location::orderBy('loc_name', 'ASC')->get();
            $query = Customer::query();
            if (!empty($request->search)) {
                $search = $request->search;

                $query = $query->where(function($query) use ($search) {
                    $query->orWhere('cust_nme', 'LIKE', '%' . $search . '%');
                    $query->orWhere('cust_email', 'LIKE', '%' . $search . '%');
                    $query->orWhere('cust_mobile', 'LIKE', '%' . $search . '%');
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
            $pageData = $query->orderby('created_at', 'DESC')
                    ->paginate(10)
                    ->withPath('?search=' . $request->search . '&req_date_range=' . $request->req_date_range);
            return view('admin.business_connect.view-customers', array('data' => $pageData, 'locationData' => $locationData));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    public function SCustomerChangeStatus($id) {

        $users = Customer::where('cust_id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_ms_cust',
            'description' => 'change social connect status', 'data_prev' => urldecode(http_build_query($users->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);
        $dataToUpdate = array();
        if ($users['sc_status'] == 1) {
            $dataToUpdate = array('sc_status' => 2, 'sc_appr_date' => date("Y-m-d H:i:s"));
        } else {
            $dataToUpdate = array('sc_status' => 1);
        }

        $status = DB::table('abc_ms_cust')->where('cust_id', base64_decode($id))->update($dataToUpdate);
        if ($status) {
            return redirect()->back()->with('message', 'Customer social connect status changed successfully');
        } else {
            return redirect()->back()->with('message', 'Error while changing customer social connect status');
        }
    }

    public function UpdateCompanyId() {
        $compCnt = 0;
        $compOfrCnt = 0;
        $custData = Customer::where('comp_flag', '=', 1)->get();

        foreach ($custData as $key => $value) {
            if ($value->cust_comp != '') {
                $compData = CompanyClient::where('cc_name', '=', $value->cust_comp)->first();
                if (isset($compData)) {
                    DB::table('abc_ms_cust')->where('cust_comp', $value->cust_comp)->where('comp_flag', 1)->update(array('cust_comp' => $compData['cc_id']));
                    $compCnt++;
                }
            }
        }
        $offerData = CompanyOffer::get();
        foreach ($offerData as $key1 => $value1) {
            $custDetail = Customer::where('cust_id', '=', $value1->co_compid)->first();
            if (isset($custDetail)) {
                DB::table('abc_client_offer')->where('co_compid', $custDetail['cust_id'])->update(array('co_compid' => $custDetail['cust_comp']));
                $compOfrCnt++;
            }
        }

        echo $compCnt . ' users company updated' . '</br>';
        echo $compOfrCnt .' users company offer  updated' .'</br>';
    }

}
