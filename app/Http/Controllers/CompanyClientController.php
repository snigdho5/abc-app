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
use App\Modal\CompanyClient;
use App\Modal\Location;
use App\Modal\Centre;
use File;

class CompanyClientController extends Controller {
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
    public function AddNewCompanyClient(Request $request) {

        $validation = Validator::make($request->all(), [
                    'cc_name' => 'required',
                    'cc_status' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('cc_name', 'cc_status'));
        }

        $status = CompanyClient::where('cc_name', $request->cc_name)
                ->first();

        if ($status) {
            return redirect()->back()->withErrors(['message', 'Company already exists']);
        } else {

            $dataToSave = array('cc_name' => $request->cc_name,
                'cc_status' => $request->cc_status,
            );
            $id = CompanyClient::create($dataToSave)->id;
            $logData = array('subject_id' => $id, 'user_id' => Auth::id(), 'table_used' => 'abc_client_comp',
                'description' => 'insert', 'data_prev' => '', 'data_now' => urldecode(http_build_query($dataToSave))
            );
            saveQueryLog($logData);
            if ($id) {
                return redirect()->back()->with('message', 'Company successfully created');
            } else {
                return redirect()->back()->with('message', 'An error occurred while creating the company');
            }
        }
    }

    /*

     * View customers 
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function CompanyClient(Request $request) {

        if (Auth::guard('admin')->user()->admin_role == 1 || Auth::guard('admin')->user()->admin_role == 2) {
            $query = CompanyClient::query();
            if (!empty($request->search)) {
                $search = $request->search;
                $query = $query->where(function($query) use ($search) {
                    $query->orWhere('cc_name', 'LIKE', '%' . $search . '%');
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
            return view('admin.company.manage-companyclient', array('data' => $pageData));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function EditCompanyClient(Request $request) {

        //dd($request->all());

        $validation = Validator::make($request->all(), [
                    'cc_name' => 'required',
                    'cc_status' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('cc_name', 'cc_status'));
        }

        $status = CompanyClient::where('cc_name', $request->cc_name)
                ->where('cc_id', '!=', $request->id)
                ->first();


        if ($status) {
            return redirect()->back()->withErrors(['message', 'Company  already exists']);
        } else {
            $data = CompanyClient::Where('cc_id', $request->id)->first();

            $changed_data = array('cc_name' => $request->cc_name,
                'cc_status' => $request->cc_status,
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
            $logData = array('subject_id' => $request->id, 'user_id' => Auth::id(), 'table_used' => 'abc_client_comp',
                'description' => 'update', 'data_prev' => urldecode(http_build_query($diff_in_data_to_save)), 'data_now' => urldecode(http_build_query($changed_data))
            );
            //dd($logData);
            saveQueryLog($logData);
            $updateCat = CompanyClient::Where('cc_id', $request->id)->update($changed_data);
            if ($updateCat) {
                return redirect()->back()->with('message', 'Company successfully updated');
            } else {
                return redirect()->back()->with('message', 'Error while updating the company');
            }
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function DeleteCompanyClient($id) {
        $data = CompanyClient::where('cc_id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_client_compomer',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($data->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);
        $status = DB::table('abc_client_comp')->where('cc_id', base64_decode($id))->delete();
        if ($status) {
            return redirect()->back()->with('message', 'Company successfully deleted');
        } else {
            return redirect()->back()->with('message', 'An error occurred while deleting the company');
        }
    }

    public function GetCompanyClientData(Request $request) {
        $data = CompanyClient::Where('cc_id', base64_decode($request->id))->first();
        echo json_encode($data);
    }

   

}
