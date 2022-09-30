<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use Mail;
use App\Modal\Centre;
use App\Modal\Location;
use App\Modal\Category;
use App\Modal\Msinfo;
use App\Modal\Meetingroom;

class VOPackagesController extends Controller {
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
    public function AddNewVOPackage(Request $request) {

//        dd($request->all());
        $validation = Validator::make($request->all(), [
                    'center_id' => 'required',
                    'ms_cat' => 'required',
                    'ms_pln_quart' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('center_id', 'ms_cat', 'ms_pln_quart'));
        }
        $status = Meetingroom::Where('center_id', $request->center_id)
                ->where('ms_cat', $request->ms_cat)
                ->first();

        if ($status) {
            return redirect()->back()->withErrors(['message', 'This package already exists']);
        } else {

            $dataToSave = array('center_id' => $request->center_id,
                'ms_cat' => $request->ms_cat,
                'ms_pln_quart' => $request->ms_pln_quart,
                'ms_pln_hy' => $request->ms_pln_hy,
                'ms_pln_yr' => $request->ms_pln_yr,
                'activation_fee' => $request->activation_fee,
                'security_deposit' => $request->security_deposit,
                'ms_status' => $request->ms_status,
                'ms_name' => getCatName($request->ms_cat),
            );
            $id = Meetingroom::create($dataToSave)->id;
            $logData = array('subject_id' => $id, 'user_id' => Auth::id(), 'table_used' => 'abc_room_rate',
                'description' => 'insert', 'data_prev' => '', 'data_now' => urldecode(http_build_query($dataToSave))
            );
            saveQueryLog($logData);
            if ($id) {
                return redirect()->back()->with('message', 'VO Package successfully created');
            } else {
                return redirect()->back()->with('message', 'An error occurred while creating the VO Package');
            }
        }
    }

    /*

     * View customers 
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function VOPackages(Request $request) {

        if (Auth::guard('admin')->user()->admin_role == 1) {

            $centreData = Centre::orderBy('centre', 'ASC')->get();

            $minfoData = Category::where('acat_type', '=', 'Package')->orderBy('acat_name', 'ASC')->get();

            $query = Centre::query();

            $query = $query->whereHas('RateDetail', function ($query) {
                $query->where('center_id', '!=', 0);
                $query->where('ms_id', '=', 0);
            });

            if (!empty($request->search)) {
                $search = $request->search;
                $query = $query->where(function($query) use ($search) {
                    $query->orWhere('centre', 'LIKE', '%' . $search . '%');
                    $query->orWhere('centre_address', 'LIKE', '%' . $search . '%');
                });
            }
//            /dd($query->get());
            $pageData = $query->orderby('created_at', 'DESC')
                    ->paginate(10)
                    ->withPath('?search=' . $request->search);

            return view('admin.services.manage-vopackage', array('data' => $pageData, 'centreData' => $centreData, 'minfoData' => $minfoData));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function EditVOPackage(Request $request) {
        //dd($request->all());
        $validation = Validator::make($request->all(), [
                    'center_id' => 'required',
                    'ms_cat' => 'required',
                    'ms_pln_quart' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('center_id', 'ms_cat', 'ms_pln_quart'));
        }
        $status = Meetingroom::Where('center_id', $request->center_id)
                ->where('ms_cat', $request->ms_cat)
                ->where('rr_id', '!=', $request->id)
                ->first();

        if ($status) {
            return redirect()->back()->withErrors(['message', 'VO Package  already exists']);
        } else {
            $data = Meetingroom::Where('rr_id', $request->id)->first();
            //dd($data);

            $changed_data = array('center_id' => $request->center_id,
                'ms_cat' => $request->ms_cat,
                'ms_pln_quart' => $request->ms_pln_quart,
                'ms_pln_hy' => $request->ms_pln_hy,
                'ms_pln_yr' => $request->ms_pln_yr,
                'activation_fee' => $request->activation_fee,
                'security_deposit' => $request->security_deposit,
                'ms_status' => $request->ms_status,
                'ms_name' => getCatName($request->ms_cat),
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
            $logData = array('subject_id' => $request->id, 'user_id' => Auth::id(), 'table_used' => 'abc_room_rate',
                'description' => 'update', 'data_prev' => urldecode(http_build_query($diff_in_data_to_save)), 'data_now' => urldecode(http_build_query($changed_data))
            );
            //dd($logData);
            saveQueryLog($logData);
            $updateCat = Meetingroom::Where('rr_id', $request->id)->update($changed_data);
            if ($updateCat) {
                return redirect()->back()->with('message', 'VO Package successfully updated');
            } else {
                return redirect()->back()->with('message', 'Error while updating the vo package');
            }
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function DeleteVOPackage($id) {
        $users = Meetingroom::where('rr_id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_room_rate',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($users->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);

        $status = DB::table('abc_room_rate')->where('rr_id', base64_decode($id))->delete();
        if ($status) {
            return redirect()->back()->with('message', 'VO Package successfully deleted');
        } else {
            return redirect()->back()->with('message', 'Error while deleting vo package');
        }
    }

    public function GetVOPackageData(Request $request) {
        $data = Meetingroom::Where('rr_id', base64_decode($request->id))->first();
        echo json_encode($data);
    }

   

}
