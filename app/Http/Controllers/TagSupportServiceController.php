<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use Mail;
use App\Modal\TagSupportService;
use App\Modal\SupportService;
use App\Modal\Centre;

class TagSupportServiceController extends Controller {
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
    public function AddNewTagSupportService(Request $request) {
//        dd($request->all());
        $validation = Validator::make($request->all(), [
                    'centreid' => 'required',
                    'ssid' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('center_id', 'ssid'));
        }
		
		TagSupportService::Where('centreid', $request->centreid)
                ->delete();
				


            for ($i = 0; $i < count($request->ssid); $i++) {
                $dataToSave = array('centreid' => $request->centreid,
                    'ssid' => $request->ssid[$i]
                );
                $id = TagSupportService::create($dataToSave)->id;
            }

            $logData = array('subject_id' => $id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_tagsupport_centre',
                'description' => 'insert', 'data_prev' => '', 'data_now' => urldecode(http_build_query($dataToSave))
            );
            saveQueryLog($logData);
            if ($id) {
                return redirect()->back()->with('message', 'SupportService successfully created');
            } else {
                return redirect()->back()->with('message', 'An error occurred while creating the offer');
            }
    }

    /*

     * View customers 
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function TagSupportService(Request $request) {
        if (Auth::guard('admin')->user()->admin_role == 1 || Auth::guard('admin')->user()->admin_role == 2) {
	    
	      if(Auth::guard('admin')->user()->admin_role == 2){
	    
		
		$centreData = DB::table('abc_manager_centre_tag')
					->select('cid')
					->where('mid', Auth::guard('admin')->user()->id)
					->get();
		$centre_data = [];
		foreach($centreData as $key=>$value){
			array_push($centre_data,$value->cid);
		}
		$centreData = Centre::where('status', 1)->whereIn('centre_id', $centre_data)->orderby('created_at', 'DESC')->get();
	    
		}else{
		   $centreData = Centre::where('status', 1)->orderby('created_at', 'DESC')->get();
		
	    }

            $supportserviceData = SupportService::orderby('created_at', 'DESC')->get();
            if (Auth::guard('admin')->user()->admin_role == 1){
            $pageData = TagSupportService::orderby('created_at', 'DESC')->get();
            }else if (Auth::guard('admin')->user()->admin_role == 2){
                $pageData = TagSupportService::whereIn('centreid',$centre_data)->orderby('created_at', 'DESC')->get();
            }
            return view('admin.support_serv.tag-supportservice', array('data' => $pageData, 'centreData' => $centreData, 'supportserviceData' => $supportserviceData));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function EditTagSupportService(Request $request) {
//        dd($request->all());
        $validation = Validator::make($request->all(), [
                    'centreid' => 'required',
                    'ssid' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('center_id', 'ssid'));
        }
        TagSupportService::Where('centreid', $request->centreid)
                ->delete();




            for ($i = 0; $i < count($request->ssid); $i++) {
                $dataToSave = array('centreid' => $request->centreid,
                    'ssid' => $request->ssid[$i]
                );
                $id = TagSupportService::create($dataToSave)->id;
            }

            $logData = array('subject_id' => $id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_tagsupport_centre',
                'description' => 'insert', 'data_prev' => '', 'data_now' => urldecode(http_build_query($dataToSave))
            );
            saveQueryLog($logData);
            if ($id) {
                return redirect()->back()->with('message', 'SupportService Tagging successfully updated');
            } else {
                return redirect()->back()->with('message', 'An error occurred while updating the SupportService tagging');
            }

    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function DeleteTagSupportService($id) {
        $users = TagSupportService::where('id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_ms_tagsupport_centre',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($users->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);

        $status = TagSupportService::Where('id', base64_decode($id))
                ->delete();
        if ($status) {
            return redirect()->back()->with('message', 'SupportService successfully deleted');
        } else {
            return redirect()->back()->with('message', 'Error while deleting SupportService');
        }
    }

    public function GetTagSupportServiceData(Request $request) {
        $arr = array();
        $data = TagSupportService::Where('centreid', base64_decode($request->id))->get();
        foreach($data as $key =>$value){
            $arr[$value['centreid']][] = $value['ssid'];
        }
       // dd($arr);
        echo json_encode($arr);
    }

}
