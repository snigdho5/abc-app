<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use Intervention\Image\ImageManagerStatic as Image;
use App\Modal\CText;

class CTextController extends Controller {
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
    public function AddNewCText(Request $request) {

        $validation = Validator::make($request->all(), [
                    'ctext_inf' => 'required',
                    'ctext_status' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('ctext_inf', 'ctext_status'));
        }

        $status = CText::where('ctext_inf', $request->ctext_inf)
                ->first();

        if ($status) {
            return redirect()->back()->withErrors(['message', 'Cancellation text already exists']);
        } else {

            $dataToSave = array('ctext_inf' => $request->ctext_inf,
                'ctext_status' => $request->ctext_status,
            );
            $id = CText::create($dataToSave)->id;
            $logData = array('subject_id' => $id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_ctext',
                'description' => 'insert', 'data_prev' => '', 'data_now' => urldecode(http_build_query($dataToSave))
            );
            saveQueryLog($logData);
            if ($id) {
                return redirect()->back()->with('message', 'Cancellation text successfully created');
            } else {
                return redirect()->back()->with('message', 'An error occurred while creating the cancellation text');
            }
        }
    }

    /*

     * View customers 
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function CTexts() {
        if (Auth::guard('admin')->user()->admin_role == 1) {
            $data = CText::orderBy('created_at', 'DESC')->get();
            return view('admin.order_cancellation_rules.manage-ctext', array('data' => $data));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function EditCText(Request $request) {

        $validation = Validator::make($request->all(), [
                    'ctext_inf' => 'required',
                    'ctext_status' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('ctext_inf', 'ctext_status'));
        }

        $status = CText::where('ctext_inf', $request->ctext_inf)
                ->where('ctext_id', '!=', $request->id)
                ->first();


        if ($status) {
            return redirect()->back()->withErrors(['message', 'Cancellation text  already exists']);
        } else {
            $data = CText::Where('ctext_id', $request->id)->first();
            //dd($data);

            $changed_data = array('ctext_inf' => $request->ctext_inf,
                'ctext_status' => $request->ctext_status,
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
            $logData = array('subject_id' => $request->id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_ctext',
                'description' => 'update', 'data_prev' => urldecode(http_build_query($diff_in_data_to_save)), 'data_now' => urldecode(http_build_query($changed_data))
            );
            //dd($logData);
            saveQueryLog($logData);
            $updateCat = CText::Where('ctext_id', $request->id)->update($changed_data);
            if ($updateCat) {
                return redirect()->back()->with('message', 'Cancellation text successfully updated');
            } else {
                return redirect()->back()->with('message', 'Error while updating the cancellation text');
            }
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function DeleteCText($id) {
        $data = CText::where('ctext_id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_ms_ctext',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($data->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);
        $status = DB::table('abc_ms_ctext')->where('ctext_id', base64_decode($id))->delete();
        if ($status) {
            return redirect()->back()->with('message', 'Cancellation text successfully deleted');
        } else {
            return redirect()->back()->with('message', 'An error occurred while deleting the cancellation text');
        }
    }

    public function GetCTextData(Request $request) {
        $data = CText::Where('ctext_id', base64_decode($request->id))->first();
        echo json_encode($data);
    }

}
