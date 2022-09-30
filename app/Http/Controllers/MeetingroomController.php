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
use App\Modal\EmailMatrix;

class MeetingroomController extends Controller {
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
    public function AddNewMeetingroom(Request $request) {


        $validation = Validator::make($request->all(), [
                    'location' => 'required',
                    'centre' => 'required',
                    'centre_address' => 'required',
                    'centre_email' => 'required',
                    'centre_mobile' => 'required',
                    'centre_phone' => 'required',
                    'centre_url' => 'required',
                    'centre_content' => 'required',
                    'status' => 'required',
                    'centre_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('location', 'centre', 'centre_address', 'centre_email', 'centre_mobile', 'centre_phone', 'centre_url', 'centre_content', 'status', 'centre_image'));
        }
        $status = Centre::Where('location', $request->location)
                ->where('centre_address', $request->centre_address)
                ->first();

        if ($status) {
            return redirect()->back()->withErrors(['message', 'This centre already exists']);
        } else {

            if ($request->hasFile('centre_image')) {
                $image = $request->file('centre_image');
                $name = time() . str_replace(' ', '_', $request->centre) . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/upload/centre/');
                $image->move($destinationPath, $name);
            }
            $dataToSave = array('location' => $request->location,
                'centre' => $request->centre,
                'centre_address' => $request->centre_address,
                'centre_email' => $request->centre_email,
                'centre_mobile' => $request->centre_mobile,
                'centre_phone' => $request->centre_phone,
                'centre_url' => $request->centre_url,
                'centre_content' => $request->centre_content,
                'centre_image' => $name == '' ? '' : $name,
                'status' => $request->status
            );
            $id = Centre::create($dataToSave)->id;
            $logData = array('subject_id' => $id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_centre',
                'description' => 'insert', 'data_prev' => '', 'data_now' => urldecode(http_build_query($dataToSave))
            );
            saveQueryLog($logData);
            if ($id) {
                return redirect()->back()->with('message', 'Centre successfully created');
            } else {
                return redirect()->back()->with('message', 'An error occurred while creating the centre');
            }
        }
    }

    /*

     * View customers 
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function Meetingrooms(Request $request) {

//echo '<pre>';
//print_r($minfoData1);
//die();
        if (Auth::guard('admin')->user()->admin_role == 1 || Auth::guard('admin')->user()->admin_role == 2) {


            $locationData = Location::orderBy('loc_name', 'ASC')->get();




            $minfoData = Category::whereHas('MsInfoDetail', function($q) {
                        $q->where('ms_cat', '!=', 0);
                    })->whereHas('MeetingroomDetail', function($q) {
                        $q->where('ms_cat', '!=', 0);
                    })->orderBy('acat_name', 'ASC')->get();

            $query = Centre::query();
            if (!empty($request->search)) {
                $search = $request->search;




                $query = $query->where(function($query) use ($search) {
                    $query->orWhere('centre', 'LIKE', '%' . $search . '%');
                    $query->orWhere('centre_address', 'LIKE', '%' . $search . '%');
                });
            }

            if (Auth::guard('admin')->user()->admin_role == 2) {
//		       $query =  $query->where('location', Auth::guard('admin')->user()->loc);
                $query = $query->where('centre_id', Auth::guard('admin')->user()->center_id);
            }


            $pageData = $query->orderby('created_at', 'DESC')
                    ->paginate(10)
                    ->withPath('?search=' . $request->search);
            return view('admin.services.manage-meetingroom', array('data' => $pageData, 'locationData' => $locationData, 'minfoData' => $minfoData));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function EditMeetingroom(Request $request) {

//dd($request->all());
        $validation = Validator::make($request->all(), [
//                    'centre' => 'required',
//                    'centre_address' => 'required',
//                    'centre_email' => 'required',
//                    'centre_mobile' => 'required',
//                    'centre_phone' => 'required',
                    'status' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('location', 'centre', 'centre_address', 'centre_email', 'centre_mobile', 'centre_phone', 'centre_url', 'centre_content', 'status', 'centre_image'));
        }

        $location = $request->location;
        $addr = $request->centre_address;


        $status = Centre::where(function($query) use($location, $addr) {
                    $query->where('location', $location)
                    ->Where('centre_address', $addr);
                })
                ->where('centre_id', '!=', $request->id)
                ->first();



        if ($status) {
            return redirect()->back()->withErrors(['message', 'This centre already exists']);
        } else {
            $data = Centre::Where('centre_id', $request->id)->first();
            $name = '';
            if ($request->hasFile('centre_image')) {
                $image = $request->file('centre_image');
                $name = time() . str_replace(' ', '_', $request->centre) . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/upload/centre/');
                $image->move($destinationPath, $name);
            }

            $changed_data = array(
                'status' => $request->status
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
            $logData = array('subject_id' => $request->id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_centre',
                'description' => 'update', 'data_prev' => urldecode(http_build_query($diff_in_data_to_save)), 'data_now' => urldecode(http_build_query($changed_data))
            );
            saveQueryLog($logData);
            $status = Centre::Where('centre_id', $request->id)->update($changed_data);

            if (isset($request->rr_id)) {
                if (count($request->rr_id) > 0) {
                    for ($i = 0; $i < count($request->rr_id); $i++) {

                        $req_hr = 'ratehour_' . $request->rr_id[$i];
                        $req_half = 'ratehalf_' . $request->rr_id[$i];
                        $req_full = 'ratefull_' . $request->rr_id[$i];
                        $req_mon = 'ratemonth_' . $request->rr_id[$i];
                        $req_qtr = 'ratequart_' . $request->rr_id[$i];
                        $req_hy = 'ratehy_' . $request->rr_id[$i];
                        $req_yr = 'rateyr_' . $request->rr_id[$i];
                        $req_inv = 'countinv_' . $request->rr_id[$i];
						$req_status='conf_status_'.$request->rr_id[$i];
						$rr_pkg='package_status_'.$request->rr_id[$i];

                        $updateData = array(
                            'ms_hour' => $request->$req_hr,
                            'ms_half' => $request->$req_half,
                            'ms_full' => $request->$req_full,
                            'ms_month' => $request->$req_mon,
                            'ms_pln_quart' => $request->$req_qtr,
                            'ms_pln_hy' => $request->$req_hy,
                            'ms_pln_yr' => $request->$req_yr,
                            'rr_no' => $request->$req_inv,
			    'ms_status' => $request->$req_status,
			    'rr_pkg' => $request->$rr_pkg,
                        );
//			print_r($updateData);
//			echo '<br>';
//			print_r($updateData);
//			die();

                        $status = Meetingroom::Where('rr_id', $request->rr_id[$i])->update($updateData);
                    }
                }
            }
            if ($status) {
                return redirect()->back()->with('message', 'Centre successfully updated');
            } else {
                return redirect()->back()->with('message', 'An error occurred while updating the centre');
            }
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function GetMeetingroomData(Request $request) {


        if (Auth::guard('admin')->user()->admin_role == 1 || Auth::guard('admin')->user()->admin_role == 2) {
            $session_data = $request->session()->all();
            $cat_selc = 0;
            if (isset($session_data['cat_changed'])) {
                $cat_selc = $session_data['cat_changed'];
            }
            $cid = base64_decode($request->id);


//            $minfoData1 = Category::with('MsInfoDetail', 'MeetingroomDetail')->where('acat_type','=','Service')->orwhere('acat_type','=','Add. Service')->where('acat_name','!=','Other')->orderBy('acat_name', 'ASC')->get();

            $minfoData1 = Category::with('MsInfoDetail', 'MeetingroomDetail')->where('acat_type', '=', 'Service')->where('acat_status', '1')->where('acat_name', '!=', 'Other')->orwhere('acat_type', '=', 'Add. Service')->orderBy('acat_name', 'ASC')->get();

//echo '<pre>';
//print_r($minfoData1);
//die();
//	    echo '<pre>';
//print_r($request->session()->all());
//die();
            $minfoData = collect();
            $minfoData = $minfoData->merge($minfoData1);

//$minfoData = $minfoData->merge($minfoData2);

            $cdata = Centre::Where('centre_id', base64_decode($request->id))->first();

//            dd($cdata->centre_id);
            $locationData = Location::orderBy('loc_name', 'ASC')->get();

            $query = Centre::query();
            if (!empty($request->search)) {
                $search = $request->search;

                $query = $query->where(function($query) use ($search) {
                    $query->orWhere('centre', 'LIKE', '%' . $search . '%');
                    $query->orWhere('centre_address', 'LIKE', '%' . $search . '%');
                });
            }

            
            if (Auth::guard('admin')->user()->admin_role == 2) {
				$centreData = DB::table('abc_manager_centre_tag')
					->select('cid')
					->where('mid', Auth::guard('admin')->user()->id)
					->get();
				$centre_data = [];
				foreach($centreData as $key=>$value){
					array_push($centre_data,$value->cid);
				}
//		       $query =  $query->where('location', Auth::guard('admin')->user()->loc);
                $query = $query->where('centre_id', $centre_data);
            }

            $pageData = $query->orderby('created_at', 'DESC')
                    ->paginate(10)
                    ->withPath('?search=' . $request->search);
//dd($minfoData[8]);die;
            return view('admin.services.edit-meetingroom', array('data' => $pageData, 'locationData' => $locationData, 'minfoData' => $minfoData, 'cinfoData' => $cdata, 'cid' => $cid, 'cat_changed' => $cat_selc));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
        echo json_encode($data);
    }

    public function GetConfigData(Request $request) {
        DB::enableQueryLog(); // Enable query log
        $cid = $request->centreid;
        $catid = $request->id;

        $data = Msinfo::whereNotIn('ms_id', function($query) use($cid, $catid) {
                    $query->select('ms_id')
                    ->from(with(new Meetingroom)->getTable())
                    ->where('center_id', $cid)
                    ->where('ms_cat', $catid);
                })->where('ms_cat', $catid)
                ->get();

        $html = '';
        foreach ($data as $key => $value) {
            $html .= '<tr class="pointer">
                            <td><input type="checkbox" class="config-checkbox" value="' . $value->ms_id . '"></td>

                            <td>' . $value->ms_name . '</td>
                            <td>' . $value->ms_type . '</td>
                        </tr>';
        }
        if (count($data) > 0) {
            echo json_encode(array('success' => 1, 'html' => $html));
        } else {
            echo json_encode(array('success' => 0));
        }
    }

    public function SaveConfigData(Request $request) {
        $ms_idArr = $request->configids;
        $centre_id = $request->centreid;

        //dd($request->all());
        for ($i = 0; $i < count($ms_idArr); $i++) {
            $configData = Msinfo::where('ms_id', $ms_idArr[$i])->first();

            $catData = Category::where('acat_id', $configData->ms_cat)->first();
            $dataToInsert = array('center_id' => $centre_id,
                'ms_id' => $ms_idArr[$i],
                'ms_name' => $configData->ms_name,
                'ms_cat' => $catData->acat_id,
                'ms_type' => $configData->ms_type
            );
            //debug($dataToInsert);
            $id = Meetingroom::create($dataToInsert)->id;
        }
        //die;
        session(['cat_changed' => $catData->acat_id]);
        if ($id > 0) {
            echo json_encode(array('status' => 1, 'category' => $catData->acat_id));
        } else {
            echo json_encode(array('status' => 0));
        }
    }

    public function GetMeetingroomDatawithcenter(Request $request) {

        /*if (Auth::guard('admin')->user()->admin_role == 1 || Auth::guard('admin')->user()->admin_role == 2) {
            $session_data = $request->session()->all();
            $cat_selc = 0;
            if (isset($session_data['cat_changed'])) {
                $cat_selc = $session_data['cat_changed'];
            }


            $emailData = EmailMatrix::select('em_id','em_per', 'em_email', 'em_phone')->where('centre_id', Auth::guard('admin')->user()->center_id)->orderBy('created_at', 'DESC')->get();

			if( Auth::guard('admin')->user()->admin_role == 2){
				$centreData = DB::table('abc_manager_centre_tag')
					->select('cid')
					->where('mid', Auth::guard('admin')->user()->id)
					->get();
					$centre_data = [];
					foreach($centreData as $key=>$value){
						array_push($centre_data,$value->cid);
					}
				$cid = $centre_data;
			}else{
				$cid = Auth::guard('admin')->user()->center_id;
			}
            


            $minfoData1 = Category::with('MsInfoDetail', 'MeetingroomDetail')->where('acat_type', '=', 'Service')->where('acat_status', '1')->where('acat_name', '!=', 'Other')->orwhere('acat_type', '=', 'Add. Service')->orderBy('acat_name', 'ASC')->get();
            ;

            $minfoData = collect();
            $minfoData = $minfoData->merge($minfoData1);


			if( Auth::guard('admin')->user()->admin_role == 2){
				$cdata = Centre::WhereIn('centre_id', $cid)->get();
						$loc_data = [];
				foreach($cdata as $key=>$value){
					array_push($loc_data,$value->location);
				}
				$locationData = Location::WhereIn('loc_id', $loc_data)->orderBy('loc_name', 'ASC')->get();
			}else{
				$cdata = Centre::Where('centre_id', $cid)->first();
				$locationData = Location::Where('loc_id', $cdata->location)->orderBy('loc_name', 'ASC')->first();
			}
            

            

            $query = Centre::query();
            if (!empty($request->search)) {
                $search = $request->search;

                $query = $query->where(function($query) use ($search) {
                    $query->orWhere('centre', 'LIKE', '%' . $search . '%');
                    $query->orWhere('centre_address', 'LIKE', '%' . $search . '%');
                });
            }

            if (Auth::guard('admin')->user()->admin_role == 2) {
                $query = $query->whereIn('centre_id', $cid);
            }


            $pageData = $query->orderby('created_at', 'DESC')
                    ->paginate(10)
                    ->withPath('?search=' . $request->search);
            return view('admin.services.config-meetingroom', array('data' => $pageData, 'locationData' => $locationData, 'minfoData' => $minfoData, 'cinfoData' => $cdata, 'cid' => $cid, 'cat_changed' => $cat_selc, 'emaildata' => $emailData));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }*/
		
		 if (Auth::guard('admin')->user()->admin_role == 1 || Auth::guard('admin')->user()->admin_role == 2) {


            $locationData = Location::orderBy('loc_name', 'ASC')->get();

            $minfoData = Category::whereHas('MsInfoDetail', function($q) {
                        $q->where('ms_cat', '!=', 0);
                    })->whereHas('MeetingroomDetail', function($q) {
                        $q->where('ms_cat', '!=', 0);
                    })->orderBy('acat_name', 'ASC')->get();


            $query = Centre::query();
            if (!empty($request->search)) {
                $search = $request->search;




                $query = $query->where(function($query) use ($search) {
                    $query->orWhere('centre', 'LIKE', '%' . $search . '%');
                    $query->orWhere('centre_address', 'LIKE', '%' . $search . '%');
                });
            }

            if (Auth::guard('admin')->user()->admin_role == 2) {
				$centreData = DB::table('abc_manager_centre_tag')
					->select('cid')
					->where('mid', Auth::guard('admin')->user()->id)
					->get();
					$centre_data = [];
					foreach($centreData as $key=>$value){
						array_push($centre_data,$value->cid);
					}
//		       $query =  $query->where('location', Auth::guard('admin')->user()->loc);
                $query = $query->whereIn('centre_id', $centre_data);
            }


            $pageData = $query->orderby('created_at', 'DESC')
                    ->paginate(10)
                    ->withPath('?search=' . $request->search);
            return view('admin.services.manage-meetingroom', array('data' => $pageData, 'locationData' => $locationData, 'minfoData' => $minfoData));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    public function DeleteMeetingroom($id) {

        $users = Meetingroom::where('rr_id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_room_rate',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($users->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);

        $status = DB::table('abc_room_rate')->where('rr_id', base64_decode($id))->delete();
        if ($status) {
            return redirect()->back()->with('message', 'Room successfully deleted');
        } else {
            return redirect()->back()->with('message', 'Error while deleting centre');
        }
    }

    public function EditMeetingroommanager(Request $request) {


        $data = Centre::Where('centre_id', Auth::guard('admin')->user()->center_id)->first();

        $changed_data = array(
            'status' => $request->status
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
        $logData = array('subject_id' => $request->id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_centre',
            'description' => 'update', 'data_prev' => urldecode(http_build_query($diff_in_data_to_save)), 'data_now' => urldecode(http_build_query($changed_data))
        );
        saveQueryLog($logData);
        $status = Centre::Where('centre_id', $request->id)->update($changed_data);

        if (isset($request->rr_id)) {
            if (count($request->rr_id) > 0) {
                for ($i = 0; $i < count($request->rr_id); $i++) {

                    $req_hr = 'ratehour_' . $request->rr_id[$i];
                    $req_half = 'ratehalf_' . $request->rr_id[$i];
                    $req_full = 'ratefull_' . $request->rr_id[$i];
                    $req_mon = 'ratemonth_' . $request->rr_id[$i];
                    $req_qtr = 'ratequart_' . $request->rr_id[$i];
                    $req_hy = 'ratehy_' . $request->rr_id[$i];
                    $req_yr = 'rateyr_' . $request->rr_id[$i];
                    $req_inv = 'countinv_' . $request->rr_id[$i];
//			$req_status='conf_status_'.$request->rr_id[$i];

                    $updateData = array(
                        'ms_hour' => $request->$req_hr,
                        'ms_half' => $request->$req_half,
                        'ms_full' => $request->$req_full,
                        'ms_month' => $request->$req_mon,
                        'ms_pln_quart' => $request->$req_qtr,
                        'ms_pln_hy' => $request->$req_hy,
                        'ms_pln_yr' => $request->$req_yr,
                        'rr_no' => $request->$req_inv,
//			    'ms_status' => $request->$req_status,
                    );
//			print_r($updateData);
//			echo '<br>';
//			print_r($updateData);
//			die();

                    $status = Meetingroom::Where('rr_id', $request->rr_id[$i])->update($updateData);
                }
            }
        }
        if ($status) {
            return redirect()->back()->with('message', 'Service successfully updated');
        } else {
            return redirect()->back()->with('message', 'An error occurred while updating the centre');
        }
    }

}
