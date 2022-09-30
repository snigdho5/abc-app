<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use Intervention\Image\ImageManagerStatic as Image;
use App\Modal\Tax;

class TaxController extends Controller {
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
    public function AddNewTax(Request $request) {   

        $validation = Validator::make($request->all(), [
                    'tax_cgst_rate' => 'required',
//                    'tax_cgst_amt' =>'required',
                    'tax_sgst_rate' => 'required',
//                    'tax_sgst_amt' =>'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('tax_cgst_rate','tax_cgst_amt','tax_sgst_rate','tax_sgst_amt'));
        }

        $status = Tax::where('tax_cgst_rate',$request->tax_cgst_rate)
                ->where('tax_sgst_rate',$request->tax_sgst_rate)
                ->first();

        if ($status) {
            return redirect()->back()->withErrors(['message', 'Tax already exists']);
        } else {

            $dataToSave = array('tax_cgst_rate' => $request->tax_cgst_rate,
                'tax_sgst_rate' => $request->tax_sgst_rate,
                'tax_status' => $request->tax_status,
            );
//            dd($dataToSave);die;
            $id = Tax::create($dataToSave)->id;
            $logData = array('subject_id' => $id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_tax',
                'description' => 'insert', 'data_prev' => '', 'data_now' => urldecode(http_build_query($dataToSave))
            );
            saveQueryLog($logData);
            if ($id) {
                return redirect()->back()->with('message', 'Tax successfully created');
            } else {
                return redirect()->back()->with('message', 'An error occurred while creating the tax');
            }
        }

    }

    /*

     * View customers 
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function Taxes() {
        if (Auth::guard('admin')->user()->admin_role == 1) {
            $data = [];
            $data = Tax::orderBy('created_at', 'DESC')->get();
            return view('admin.services.manage-tax', array('data' => $data));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function EditTax(Request $request) {

 $validation = Validator::make($request->all(), [
                    'tax_cgst_rate' => 'required',
                    'tax_sgst_rate' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('tax_cgst_rate','tax_cgst_amt','tax_sgst_rate','tax_sgst_amt'));
        }

        $status = Tax::where('tax_cgst_rate',$request->tax_cgst_rate)
                ->where('tax_sgst_rate',$request->tax_sgst_rate)
                ->where('tax_id', '!=', $request->tax_id)
                ->first();

        if ($status) {
            return redirect()->back()->withErrors(['message', 'Tax  already exists']);
        } else {
            $data = Tax::Where('tax_id', $request->tax_id)->first();
            //dd($data);
            
            $changed_data = array('tax_cgst_rate' => $request->tax_cgst_rate,
                'tax_sgst_rate' => $request->tax_sgst_rate,
                'tax_status' => $request->tax_status,
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
            $logData = array('subject_id' => $request->tax_id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_tax',
                'description' => 'update', 'data_prev' => urldecode(http_build_query($diff_in_data_to_save)), 'data_now' => urldecode(http_build_query($changed_data))
            );
            //dd($logData);
            saveQueryLog($logData);
            $updateCat = Tax::Where('tax_id', $request->tax_id)->update($changed_data);
            if ($updateCat) {
                return redirect()->back()->with('message', 'Tax successfully updated');
            } else {
                return redirect()->back()->with('message', 'Error while updating the tax');
            }
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function DeleteTax($id) {
        $data = Tax::where('tax_id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_ms_tax',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($data->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);
        $status = DB::table('abc_ms_tax')->where('tax_id', base64_decode($id))->delete();
        if ($status) {
            return redirect()->back()->with('message', 'Tax successfully deleted');
        } else {
            return redirect()->back()->with('message', 'An error occurred while deleting the tax');
        }
    }

    public function GetTaxData(Request $request) {
        $data = Tax::Where('tax_id', base64_decode($request->id))->first();
        echo json_encode($data);
    }

}
