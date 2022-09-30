<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use Intervention\Image\ImageManagerStatic as Image;
use App\Modal\Msinfo;
use App\Modal\Category;

class MsinfoController extends Controller {
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
    public function AddNewMsinfo(Request $request) {

        $validation = Validator::make($request->all(), [
                    'ms_cat' => 'required',
                    'ms_name' => 'required',
                    'ms_type' => 'required',
//                    'ms_hour' => 'required',
//                    'ms_half' => 'required',
//                    'ms_full' => 'required',
//                    'ms_month' => 'required',
//                    'ms_year' => 'required',
//                    'ms_quart' => 'required',
//                    'ms_hy' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('ms_cat', 'ms_name', 'ms_type'));
        }

        $status = Msinfo::where('ms_cat', $request->ms_cat)
                ->where('ms_name', $request->ms_name)
                ->where('ms_type', $request->ms_type)
                ->first();

        if ($status) {
            return redirect()->back()->withErrors(['message', 'Master Info already exists']);
        } else {

            $dataToSave = array('ms_cat' => $request->ms_cat,
                'ms_name' => $request->ms_name,
                'ms_type' => $request->ms_type,
                'ms_hour' => $request->ms_hour ?$request->ms_hour:'0.00',
                'ms_half' => $request->ms_half?$request->ms_half:'0.00',
                'ms_full' => $request->ms_full?$request->ms_full:'0.00',
                'ms_month' => $request->ms_month?$request->ms_month:'0.00',
                'ms_year' => $request->ms_year?$request->ms_year:'0.00',
                'ms_quart' => $request->ms_quart?$request->ms_quart:'0.00',
                'ms_hy' => $request->ms_hy?$request->ms_hy:'0.00',
            );
            $id = Msinfo::create($dataToSave)->id;
            $logData = array('subject_id' => $id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_info',
                'description' => 'insert', 'data_prev' => '', 'data_now' => urldecode(http_build_query($dataToSave))
            );
            saveQueryLog($logData);
            if ($id) {
                return redirect()->back()->with('message', 'Master Info successfully created');
            } else {
                return redirect()->back()->with('message', 'An error occurred while creating the master Info');
            }
        }
    }

    /*

     * View customers 
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function Msinfos() {
        if (Auth::guard('admin')->user()->admin_role == 1) {
            $data = Msinfo::orderBy('ms_name', 'ASC')->get();
            $cdata = Category::orderBy('acat_name', 'ASC')->get();
            return view('admin.services.manage-msinfo', array('data' => $data, 'cdata' => $cdata));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function EditMsinfo(Request $request) {

        $validation = Validator::make($request->all(), [
                    'ms_cat' => 'required',
                    'ms_name' => 'required',
                    'ms_type' => 'required',
//                    'ms_hour' => 'required',
//                    'ms_half' => 'required',
//                    'ms_full' => 'required',
//                    'ms_month' => 'required',
//                    'ms_year' => 'required',
//                    'ms_quart' => 'required',
//                    'ms_hy' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('ms_cat', 'ms_name', 'ms_type'));
        }

        $status = Msinfo::where('ms_cat', $request->ms_cat)
                ->where('ms_name', $request->ms_name)
                ->where('ms_type', $request->ms_type)
                ->where('ms_id', '!=', $request->ms_id)
                ->first();

        if ($status) {
            return redirect()->back()->withErrors(['message', 'Master info  already exists']);
        } else {
            $data = Msinfo::Where('ms_id', $request->ms_id)->first();
            //dd($data);

            $changed_data = array('ms_cat' => $request->ms_cat,
                'ms_name' => $request->ms_name,
                'ms_type' => $request->ms_type,
                'ms_hour' => $request->ms_hour,
                'ms_half' => $request->ms_half,
                'ms_full' => $request->ms_full,
                'ms_month' => $request->ms_month,
                'ms_year' => $request->ms_year,
                'ms_quart' => $request->ms_quart,
                'ms_hy' => $request->ms_hy,
                'ms_status' => $request->ms_status,
            );

            $diff_in_data = array_diff_assoc($data->getOriginal(), $changed_data);
            $diff_in_data_to_save = array();
            $keys_to_be_updated = array_keys($diff_in_data);

            for ($i = 0; $i < count($keys_to_be_updated); $i++) {
                if (isset($changed_data[$keys_to_be_updated[$i]])) {
                    $data_to_update[$keys_to_be_updated[$i]] = $changed_data[$keys_to_be_updated[$i]];
                    $diff_in_data_to_save[$keys_to_be_updated[$i]] = $diff_in_data[$keys_to_be_updated[$i]];
                }
            }
            $logData = array('subject_id' => $request->ms_id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_info',
                'description' => 'update', 'data_prev' => urldecode(http_build_query($diff_in_data_to_save)), 'data_now' => urldecode(http_build_query($changed_data))
            );
            //dd($logData);
            saveQueryLog($logData);
            $updateCat = Msinfo::Where('ms_id', $request->ms_id)->update($changed_data);
            if ($updateCat) {
                return redirect()->back()->with('message', 'Master info successfully updated');
            } else {
                return redirect()->back()->with('message', 'Error while updating the master info');
            }
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function DeleteMsinfo($id) {
        $data = Msinfo::where('ms_id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_ms_info',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($data->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);
        $status = DB::table('abc_ms_info')->where('ms_id', base64_decode($id))->delete();
        if ($status) {
            return redirect()->back()->with('message', 'Master info successfully deleted');
        } else {
            return redirect()->back()->with('message', 'An error occurred while deleting the master info');
        }
    }

    public function GetMsinfoData(Request $request) {
        $data = Msinfo::Where('ms_id', base64_decode($request->id))->first();
        echo json_encode($data);
    }
    
    public function GetMsinfoDataByCat(Request $request) {
        $data = Msinfo::select('ms_id','ms_name','ms_type')->Where('ms_cat', $request->ms_cat)->Where('ms_status', 1)->get();
        echo json_encode($data);
    }
    


}
