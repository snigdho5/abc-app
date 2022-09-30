<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use App\Exports\RClientExport;
use Intervention\Image\ImageManagerStatic as Image;
use App\Modal\ReferClient;

class ReferClientController extends Controller {
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

    /*

     * View customers 
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function ReferClients() {
        if (Auth::guard('admin')->user()->admin_role == 1) {
            $data = ReferClient::orderBy('created_at', 'DESC')->get();
            return view('admin.form_data.manage-referclients', array('data' => $data));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

  
    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function DeleteReferClient($id) {
        $data = ReferClient::where('rc_id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_ms_refer_client',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($data->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);
        $status = DB::table('abc_ms_refer_client')->where('rc_id', base64_decode($id))->delete();
        if ($status) {
            return redirect()->back()->with('message', 'Data successfully deleted');
        } else {
            return redirect()->back()->with('message', 'An error occurred while deleting the data');
        }
    }
    
      public function exportToExcelReferClient() {
        $exporter = app()->makeWith(RClientExport::class);
        return $exporter->download(date('Y-m-d-H-i-s') . '-Referred_clients.xlsx');
    }

}
