<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use Mail;
use App\Modal\SupportService;

class SupportServiceController extends Controller {
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
    public function AddNewSupportService(Request $request) {
        //dd($request->all());
        $validation = Validator::make($request->all(), [
                    'ss_text' => 'required',
                    'ss_status' => 'required',
                    'ss_img' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('ss_text', 'ss_status', 'ss_img'));
        }
        $status = SupportService::Where('ss_text', $request->ss_text)
                ->first();

        if ($status) {
            return redirect()->back()->withErrors(['message', 'This SupportService already exists']);
        } else {
            $name = '';
            if ($request->hasFile('ss_img')) {
                $image = $request->file('ss_img');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/upload/supportservice/');
                $valImage = validateImage($image->getClientOriginalExtension());
                if ($valImage) {
                    $image->move($destinationPath, $name);
                } else {
                    if ($image->getClientOriginalExtension() == 'svg') {
                        $image->move($destinationPath, $name);
                    } else {
                        return redirect()->back()->withErrors(['message', 'Uploaded file is not a valid image. Only JPG, PNG ,SVG and GIF files are allowed.']);
                    }
                }
            }




            $dataToSave = array('ss_text' => $request->ss_text,
                'ss_img' => $name == '' ? '' : $name,
                'ss_status' => $request->ss_status
            );
            $id = SupportService::create($dataToSave)->id;


            $logData = array('subject_id' => $id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_sprtserv',
                'description' => 'insert', 'data_prev' => '', 'data_now' => urldecode(http_build_query($dataToSave))
            );
            saveQueryLog($logData);
            if ($id) {
                return redirect()->back()->with('message', 'SupportService successfully created');
            } else {
                return redirect()->back()->with('message', 'An error occurred while creating the SupportService');
            }
        }
    }

    /*

     * View customers 
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function SupportService(Request $request) {

        if (Auth::guard('admin')->user()->admin_role == 1 || Auth::guard('admin')->user()->admin_role == 2 ) {

            $pageData = SupportService::orderby('created_at', 'DESC')->get();
            return view('admin.support_serv.manage-supportservice', array('data' => $pageData));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function EditSupportService(Request $request) {
//        dd($request->all());
        $validation = Validator::make($request->all(), [
                    'ss_text' => 'required',
                    'ss_status' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('ss_text', 'ss_status', 'ss_img'));
        }
        $status = SupportService::Where('ss_text', $request->ss_text)
                ->where('ss_id', '!=', $request->id)
                ->first();

        if ($status) {
            return redirect()->back()->withErrors(['message', 'This SupportService already exists']);
        } else {
            $data = SupportService::Where('ss_id', $request->id)->first();
            $name = '';
            if ($request->hasFile('ss_img')) {
                $image = $request->file('ss_img');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/upload/supportservice/');
                $valImage = validateImage($image->getClientOriginalExtension());
                if ($valImage) {
                    $image->move($destinationPath, $name);
                } else {
                    if ($image->getClientOriginalExtension() == 'svg') {
                        $image->move($destinationPath, $name);
                    } else {
                        return redirect()->back()->withErrors(['message', 'Uploaded file is not a valid image. Only JPG, PNG ,SVG and GIF files are allowed.']);
                    }
                }
            }



            $changed_data = array('ss_text' => $request->ss_text,
                'ss_img' => $name == '' ? $data['ss_img'] : $name,
                'ss_status' => $request->ss_status
            );


            $diff_in_data_to_save = array();
            $diff_in_data = array_diff_assoc($data->getOriginal(), $changed_data);

            $keys_to_be_updated = array_keys($diff_in_data);

            for ($i = 0; $i < count($keys_to_be_updated); $i++) {
                if (isset($changed_data[$keys_to_be_updated[$i]])) {
                    $data_to_update[$keys_to_be_updated[$i]] = $changed_data[$keys_to_be_updated[$i]];
                    $diff_in_data_to_save[$keys_to_be_updated[$i]] = $diff_in_data[$keys_to_be_updated[$i]];
                }
            }
            $logData = array('subject_id' => $request->id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_sprtserv',
                'description' => 'update', 'data_prev' => urldecode(http_build_query($diff_in_data_to_save)), 'data_now' => urldecode(http_build_query($changed_data))
            );
            saveQueryLog($logData);
            $status = SupportService::Where('ss_id', $request->id)->update($changed_data);
            if ($status) {
                return redirect()->back()->with('message', 'SupportService successfully updated');
            } else {
                return redirect()->back()->with('message', 'An error occurred while updating the SupportService');
            }
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function DeleteSupportService($id) {
        $users = SupportService::where('ss_id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_ms_sprtserv',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($users->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);

        $status = DB::table('abc_ms_sprtserv')->where('ss_id', base64_decode($id))->delete();
        if ($status) {
            return redirect()->back()->with('message', 'SupportService successfully deleted');
        } else {
            return redirect()->back()->with('message', 'Error while deleting SupportService');
        }
    }

    public function GetSupportServiceData(Request $request) {
        $data = SupportService::Where('ss_id', base64_decode($request->id))->first();
        echo json_encode($data);
    }

}
