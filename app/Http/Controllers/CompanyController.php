<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Auth;
use DB;
use Intervention\Image\ImageManagerStatic as Image;
use App\Modal\Customer;
use App\Modal\CompanyClient;
use App\Modal\CompanyOffer;
use App\Modal\Location;
use App\Modal\Centre;
use File;

class CompanyController extends Controller {
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
    public function AddNewCompany(Request $request) {

        $validation = Validator::make($request->all(), [
                    'cust_comp' => 'required',
                    'cust_nme' => 'required',
                    'cust_email' => 'required',
                    'cust_mobile' => 'required',
                    'cust_service_add1' => 'required',
                    'custloc' => 'required',
                    'cust_desig' => 'required',
                    'comp_status' => 'required',
                    'cust_centre' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('cust_comp', 'cust_nme', 'cust_email', 'cust_mobile', 'cust_service_add1', 'custloc', 'cust_desig', 'comp_status', 'cust_centre'));
        }

        $status = Customer::where('cust_email', $request->cust_email)
                ->where('cust_mobile', $request->cust_mobile)
                ->first();

        if ($status) {
            return redirect()->back()->withErrors(['message', 'Client already exists']);
        } else {

            $dataToSave = array('cust_comp' => $request->cust_comp,
                'cust_nme' => $request->cust_nme,
                'cust_email' => $request->cust_email,
                'cust_mobile' => $request->cust_mobile,
                'comp_status' => $request->comp_status,
                'cust_service_add1' => $request->cust_service_add1,
                'custloc' => $request->custloc,
                'cust_desig' => $request->cust_desig,
                'cust_centre' => $request->cust_centre,
                'comp_flag' => 1,
				'blc' => $request->blc,
            );
            $id = Customer::create($dataToSave)->id;
            $logData = array('subject_id' => $id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_cust',
                'description' => 'insert', 'data_prev' => '', 'data_now' => urldecode(http_build_query($dataToSave))
            );
            saveQueryLog($logData);
            if ($id) {
                return redirect()->back()->with('message', 'Client successfully created');
            } else {
                return redirect()->back()->with('message', 'An error occurred while creating the client');
            }
        }
    }

    /*

     * View customers 
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function Company(Request $request) {

        if (Auth::guard('admin')->user()->admin_role == 1 || Auth::guard('admin')->user()->admin_role == 2) {
            $locationData = Location::orderby('loc_name', 'asc')->get();
            $compData = CompanyClient::orderby('cc_name', 'asc')->get();
            $centreData = Centre::select('centre_id', 'centre', 'location')->orderby('centre', 'asc')->get();
            $query = Customer::query();
            if (!empty($request->search)) {
                $search = $request->search;
                $query = $query->where(function($query) use ($search) {
                   // $query->orWhere('cust_comp', 'LIKE', '%' . $search . '%');
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

            $query = $query->where('comp_flag', 1);

            $pageData = $query->orderby('created_at', 'DESC')
                    ->paginate(10)
                    ->withPath('?search=' . $request->search . '&req_date_range=' . $request->req_date_range);
            return view('admin.company.manage-company', array('data' => $pageData, 'locationData' => $locationData, 'centreData' => $centreData,'compData'=>$compData));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function EditCompany(Request $request) {

        //dd($request->all());

        $validation = Validator::make($request->all(), [
                    'cust_comp' => 'required',
                    'cust_nme' => 'required',
                    'cust_email' => 'required',
                    'cust_mobile' => 'required',
                    'cust_service_add1' => 'required',
                    'custloc' => 'required',
                    'cust_desig' => 'required',
                    'comp_status' => 'required',
                    'cust_centre' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('cust_comp', 'cust_nme', 'cust_email', 'cust_mobile', 'cust_service_add1', 'custloc', 'cust_desig', 'comp_status', 'cust_centre'));
        }

        $status = Customer::where('cust_email', $request->cust_email)
                ->where('cust_mobile', $request->cust_mobile)
                ->where('comp_flag', 1)
                ->where('cust_id', '!=', $request->id)
                ->first();


        if ($status) {
            return redirect()->back()->withErrors(['message', 'Client  already exists']);
        } else {
            $data = Customer::Where('cust_id', $request->id)->first();

            $changed_data = array('cust_comp' => $request->cust_comp,
                'cust_nme' => $request->cust_nme,
                'cust_email' => $request->cust_email,
                'cust_mobile' => $request->cust_mobile,
                'comp_status' => $request->comp_status,
                'cust_service_add1' => $request->cust_service_add1,
                'custloc' => $request->custloc,
                'cust_desig' => $request->cust_desig,
                'comp_gst' => $request->comp_gst,
                'cust_centre' => $request->cust_centre,
                'comp_flag' => 1,
				'blc' => $request->blc,
            );

            // dd($changed_data);
            $diff_in_data = array_diff_assoc($data->getOriginal(), $changed_data);
            $diff_in_data_to_save = array();
            $keys_to_be_updated = array_keys($diff_in_data);

            for ($i = 0; $i < count($keys_to_be_updated); $i++) {
                if (isset($changed_data[$keys_to_be_updated[$i]])) {
                    $data_to_update[$keys_to_be_updated[$i]] = $changed_data[$keys_to_be_updated[$i]];
                    $diff_in_data_to_save[$keys_to_be_updated[$i]] = $diff_in_data[$keys_to_be_updated[$i]];
                }
            }
            $logData = array('subject_id' => $request->id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_cust',
                'description' => 'update', 'data_prev' => urldecode(http_build_query($diff_in_data_to_save)), 'data_now' => urldecode(http_build_query($changed_data))
            );
            //dd($logData);
            saveQueryLog($logData);
            $updateCat = Customer::Where('cust_id', $request->id)->update($changed_data);
            if ($updateCat) {
                return redirect()->back()->with('message', 'Client successfully updated');
            } else {
                return redirect()->back()->with('message', 'Error while updating the client');
            }
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function DeleteCompany($id) {
        $data = Customer::where('cust_id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_ms_customer',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($data->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);
        $status = DB::table('abc_ms_cust')->where('cust_id', base64_decode($id))->delete();
        if ($status) {
            return redirect()->back()->with('message', 'Client successfully deleted');
        } else {
            return redirect()->back()->with('message', 'An error occurred while deleting the client');
        }
    }

    public function GetCompanyData(Request $request) {
        $data = Customer::Where('cust_id', base64_decode($request->id))->first();
        echo json_encode($data);
    }

    public function UploadCorporateCust() {
        if (Auth::guard('admin')->user()->admin_role == 1) {

            if (Input::hasFile('corporate_file')) {
                $file = Input::file('corporate_file');
                if ($file->getClientOriginalExtension() == 'csv') {
                    $name = $file->getClientOriginalName();

                    $name = preg_replace("/[^\.\-\_a-zA-Z0-9]/", "", $name);
                    //Not really uniqe - but for all practical reasons, it is
                    $uniqer = substr(md5(uniqid(rand(), 1)), 0, 6);
                    $name = $uniqer . '_' . $name; //Get Unique Name             
//                $path = public_path('/upload/csv');
                    $path = public_path('/upload/corporate-client');

                    // Moves file to folder on server
                    $file->move($path, $name);

                    $file1 = public_path('/upload/corporate-client/' . $name);

                    $productArr = csvToArray($file1);
                    for ($i = 0; $i < count($productArr); $i++) {
                        $prdata = [];
                        $CompanyName = '';
                        $Address = '';
                        $Location = '';
                        $Centre = '';
                        $GSTNumber = '';
                        $ContactName = '';
                        $Emailid = '';
                        $MobileNumber = '';
                        $Designation = '';
                        if (isset($productArr[$i]['CompanyName']))
                            $CompanyName = trim($productArr[$i]['CompanyName']);
                        if (isset($productArr[$i]['Address']))
                            $Address = trim($productArr[$i]['Address']);
                        if (isset($productArr[$i]['Location']))
                            $Location = trim($productArr[$i]['Location']);
                        if (($productArr[$i]['Centre']) !='')
                            $Centre = trim($productArr[$i]['Centre']);
                        if (isset($productArr[$i]['GSTNumber']))
                            $GSTNumber = trim($productArr[$i]['GSTNumber']);
                        if (isset($productArr[$i]['ContactName']))
                            $ContactName = trim($productArr[$i]['ContactName']);
                        if (isset($productArr[$i]['Emailid']))
                            $Emailid = trim($productArr[$i]['Emailid']);
                        if (isset($productArr[$i]['MobileNumber']))
                            $MobileNumber = trim($productArr[$i]['MobileNumber']);
                        if (isset($productArr[$i]['Designation']))
                            $Designation = trim($productArr[$i]['Designation']);


                        if ($CompanyName != '' && $Address != '' && $Location != '' && $ContactName != '' && $Emailid != '' && $MobileNumber != '' && $Centre != '') {
                            $prstatus = Customer::select('cust_id')->where('cust_email', $Emailid)
                                    ->where('cust_mobile', $MobileNumber)
                                    ->where('cust_comp', $CompanyName)
                                    ->first();
                            $dataToSave = array('cust_comp' => $CompanyName,
                                'cust_nme' => $ContactName,
                                'cust_email' => $Emailid,
                                'cust_mobile' => $MobileNumber,
                                'comp_status' => 2,
                                'cust_service_add1' => $Address,
                                'custloc' => $Location,
                                'cust_desig' => $Designation,
                                'cust_centre' => $Centre,
                                'comp_flag' => 1,
                            );
                            if ($prstatus) {
                                Customer::where('cust_id',$prstatus['cust_id'])->update($dataToSave);
                            } else {
//                    var_dump($data);
                                $prid = Customer::create($dataToSave)->id;
                            }
                        }
                    }
//exit;                
                    return redirect()->back()->with('message',
                                    'Clients
successfully uploaded.');
                } else {
                    return redirect()->back()->withErrors(['message', 'file format is not csv']);
                }
            } else {
                return view('admin.company.upload-corporateclients');
            }
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }


}
