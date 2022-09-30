<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use App\Modal\SendLink;
use App\Modal\CentreLink;
use App\Modal\Centre;

class SendLinkController extends Controller {
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
    public function SendLink(Request $request) {
//        dd($request->all());
        $validation = Validator::make($request->all(), [
                    'mobile' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('mobile', 'link'));
        }

        $dataToSave = array('mobile' => $request->mobile,
        );

        $link = DB::table('abc_ms_app_link')->select('app_link')->first();

        $id = SendLink::create($dataToSave)->id;

        //$URLtext = str_replace(" ", "+", $link->app_link);

        if (strlen($request->mobile) == 10) {
            $URLmob = "91" . $request->mobile;
        } else {
            $URLmob = $request->mobile;
        }
        $URLsms = 'http://bhashsms.com/api/sendmsg.php?user=Apeejaybusiness&pass=123456&sender=APJABC&priority=ndnd&stype=normal&phone=';
        $URLsms = $URLsms . $URLmob . "&text=" . $URLtext;

        if ($_SERVER['HTTP_HOST'] != 'localhost') {
            get_sms($URLsms);
        }



//        $id = 1;

        $logData = array('subject_id' => $id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_applink',
            'description' => 'insert', 'data_prev' => '', 'data_now' => urldecode(http_build_query($dataToSave))
        );
        saveQueryLog($logData);
        if ($id) {
            return redirect()->back()->with('message', 'Application Link successfully sent');
        } else {
            return redirect()->back()->with('message', 'Error while sending application link');
        }
    }

    /*

     * View list 
     * Sanjit Bhardwaj
     * 11-01-2018
     */

    public function LinkDetails() {
        if (Auth::guard('admin')->user()->admin_role == 1 || Auth::guard('admin')->user()->admin_role == 2) {
            if (Auth::guard('admin')->user()->admin_role == 2) {
                $centreData = Centre::select('centre_id', 'location', 'centre')->where('status', 1)->where('centre_id', Auth::guard('admin')->user()->center_id)->get();
                $pageData = SendLink::orderBy('created_at', 'DESC')->get();
            } else {
                $centreData = Centre::select('centre_id', 'location', 'centre')->where('status', 1)->get();
                $pageData = SendLink::orderBy('created_at', 'DESC')->get();
            }

            return view('admin.send_link.send-applink', array('data' => $pageData, 'centreData' => $centreData));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    /*

     * Edit data
     * Sanjit Bhardwaj
     * 11-01-2018
     */

    public function DeleteLink($id) {
        $data = SendLink::where('id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_ms_applink',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($data->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);
        $status = DB::table('abc_ms_applink')->where('id', base64_decode($id))->delete();
        if ($status) {
            return redirect()->back()->with('message', 'Data successfully deleted');
        } else {
            return redirect()->back()->with('message', 'Error while deleting data');
        }
    }

    public function SendCentreLink(Request $request) {

        $validation = Validator::make($request->all(), [
                    'mobile' => 'required',
                    'link' => 'required',
                    'centre_id' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('mobile', 'link'));
        }

        $dataToSave = array('mobile' => $request->mobile,
            'link' => $request->link,
            'centre_id' => $request->centre_id,
        );


        $id = CentreLink::create($dataToSave)->id;

        $URLtext = str_replace(" ", "+", $request->link);
        if (strlen($request->mobile) == 10) {
            $URLmob = "91" . $request->mobile;
        } else {
            $URLmob = $request->mobile;
        }
		$URLsms = 'https://www.myvaluefirst.com/smpp/sendsms?username=apjabc&password=Smdfb@1234&from=APJABC&to=';
        $URLsms = $URLsms . $URLmob . "&text=" . $URLtext;

        if ($_SERVER['HTTP_HOST'] != 'localhost') {
            get_sms($URLsms);
        }



        $logData = array('subject_id' => $id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_centrelink',
            'description' => 'insert', 'data_prev' => '', 'data_now' => urldecode(http_build_query($dataToSave))
        );
        saveQueryLog($logData);
        if ($id) {
            return redirect()->back()->with('message', 'Centre Location Link successfully sent');
        } else {
            return redirect()->back()->with('message', 'Error while sending centre location link');
        }
    }

    /*

     * View list 
     * Sanjit Bhardwaj
     * 11-01-2018
     */

    public function CentreLinkDetails() {
        if (Auth::guard('admin')->user()->admin_role == 1 || Auth::guard('admin')->user()->admin_role == 2) {
            if (Auth::guard('admin')->user()->admin_role == 2) {
				
				$centreData1 = DB::table('abc_manager_centre_tag')
							->select('cid')
							->where('mid', Auth::guard('admin')->user()->id)
							->get();
				$centre_data = [];
				foreach($centreData1 as $key=>$value){
					array_push($centre_data,$value->cid);
				}
		
                $centreData = Centre::select('centre_id', 'location', 'centre','centre_url')->where('status', 1)
					->whereIn('centre_id', $centre_data)
					->get();
                $pageData = CentreLink::orderBy('created_at', 'DESC')
					->whereIn('centre_id', $centre_data)
					->get();
            } else {
                $centreData = Centre::select('centre_id', 'location', 'centre')->where('status', 1)->get();
                $pageData = CentreLink::orderBy('created_at', 'DESC')->get();
            }


            return view('admin.send_link.send-centrelink', array('data' => $pageData, 'centreData' => $centreData));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    /*

     * Edit data
     * Sanjit Bhardwaj
     * 11-01-2018
     */

    public function DeleteCentreLink($id) {
        $data = CentreLink::where('id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_ms_centrelink',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($data->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);
        $status = DB::table('abc_ms_centrelink')->where('id', base64_decode($id))->delete();
        if ($status) {
            return redirect()->back()->with('message', 'Data successfully deleted');
        } else {
            return redirect()->back()->with('message', 'Error while deleting data');
        }
    }

}
