<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use Hash;
use App\Modal\Location;
use App\Modal\Services;
use App\Modal\Admin;
use App\Modal\Centre;
use Mail;

class SubAdminController extends Controller {
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
    public function ChangePassword(Request $request) {
        if ($request->submit == 1) {
            $validation = Validator::make($request->all(), [
                        'old_password' => 'required|min:6',
                        'new_password' => 'required|min:6',
                        'cnf_password' => 'required|min:6',
                            //'confirm_password'  => 'required|min:6',          
            ]);
            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput($request->only('old_password'));
            }
            $user = Admin::Where('id', Auth::guard('admin')->user()->id)->first();
            if ($request->new_password == $request->cnf_password) {
                if (Hash::check($request->old_password, $user->password)) {

                    $hashed = Hash::make($request->new_password);
                    $user->password = $hashed;
                    $user->save();
                    return redirect()->back()->with('message', 'Password changed successfully.');
                } else {
                    return redirect()->back()->withErrors(['Your current password is wrong.'])->withInput($request->only('old_password'));
                }
            } else {
                return redirect()->back()->withErrors(['Your confirm password is wrong.'])->withInput($request->only('old_password'));
            }
        }
        return view('admin.change-password');
    }

    public function SubAdminAvailability() {
		
        $data = Admin::where('admin_role', 2)->orderBy('created_at', 'DESC')->get();
        $centreData = Centre::orderBy('centre', 'ASC')->get();

        return view('admin.subadmin.subAdmin-availability', array('data' => $data, 'centreData' => $centreData));
    }

    public function AddNewSubAdmin(Request $request) {

        $prefix = 'ADM';
        $validation = Validator::make($request->all(), [
                    'user_name' => 'required',
                    'email' => 'required|email',
                    'mobile' => 'required|min:10',
                    'center_id' => 'required',
                    'status' => 'required'
        ]);


        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('user_name', 'email', 'mobile', 'center_id', 'status'));
        }

        $mobile = $request->mobile;
        $email = $request->email;
        $centerid = $request->center_id;
		
		$query = DB::table('abc_admin as admin')
                ->select('admin.id', 'admin.email', 'admin.mobile');
        //$query = $query->Where('admin.id', '!=', $request->id);
		
        //$query = Admin::query();
        //$query = $query->where('center_id', $request->center_id);
		$query = $query->join('abc_manager_centre_tag as centre', 'centre.mid', '=',
                'admin.id');
        //$centerid = $request->center_id;
		for($i=0;$i<count($request->center_id);$i++){
			$query = $query->where('centre.cid', $request->center_id);
		}
        $query = $query->where(function($query) use ($mobile, $email) {
            //$query->where('center_id', $centerid);
            $query->orWhere('admin.email', $email);
            $query->orWhere('admin.mobile', $mobile);
        });

        $status = $query->first();

        if ($status) {
            return redirect()->back()->withErrors(['message', 'Mobile/Email id/Centre Manager  already exists. Please use a different Mobile Number/Email id/Centre']);
        } else {

            $dataToSave = array('user_name' => $request->user_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                //'center_id' => $request->center_id,
                'cmt' => $request->cmt,
                'status' => $request->status,
                'admin_role' => 2
            );
            $id = Admin::create($dataToSave)->id;

			for($i=0;$i<count($request->center_id);$i++){
				//DB::table('abc_manager_centre_tag')
				DB::table('abc_manager_centre_tag')->insert(
					['mid' => $id, 'cid' => $request->center_id[$i]]
				);
			}
			
            $subadminPwd = mt_rand(100000, 999999);

            $data2 = array('custName' => $request->user_name, 'email' => $request->email, 'mobileNumber' => $request->mobile, 'password' => $subadminPwd, 'siteurl' => 'https://getquote.apeejaybusinesscentre.com/abcapp/app/secAdm/public/admin');

            $URLtext = "Dear+" . str_replace(" ", "+", $request->user_name) . ",+Your+account+has+been+created+.%0AUser+ID:+" . str_replace(" ", "+", $request->email) . "%0APassword:+" . $subadminPwd . "+%0ALog+on+to+application.+https://getquote.apeejaybusinesscentre.com/abcapp/app/secAdm/public";
            if (strlen($request->mobile) == 10)
                $URLmob = "91" . $request->mobile;
            else
                $URLmob = $request->mobile;
            $URLsms = 'https://www.myvaluefirst.com/smpp/sendsms?username=apjabc&password=Smdfb@1234&from=APJABC&to=';
            $URLsms = $URLsms . $URLmob . "&text=" . $URLtext;
            if ($_SERVER['HTTP_HOST'] != 'localhost') {

                get_sms($URLsms);

                $retval = Mail::send('admin.emails.admin_new_registration', $data2, function ($message) use ($request) {
                            $message->from('no-reply@studiobrahma.in', 'Apeejay Business Center');
                            $message->to($request->email);
                            $message->subject('Apeejay Business Centre: Manager Account Created');
                        });
						//dd($retval);
            } else {
//	  	$retval = Mail::send('admin.emails.admin_new_registration', $data2, function ($message) use ($request) {
//	    
//	   
//
//                            $message->from('noreply@test.in', 'Apeejay Business Center');
//                            $message->to($request->email);
//                           $message->subject('ABC : New SubAdmin Registration');			   
//                        });
            }
            $idTosave = 1000 + $id;

            $dataToUpdate = array('code' => $prefix . date("y") . $idTosave, 'password' => bcrypt($subadminPwd));

            $logData = array('subject_id' => $id, 'user_id' => Auth::id(), 'table_used' => 'abc_admin',
                'description' => 'insert', 'data_prev' => '', 'data_now' => urldecode(http_build_query($request->all()))
            );
            saveQueryLog($logData);
            if ($id) {
                Admin::where('id', $id)->where('admin_role', 2)->update($dataToUpdate);

                return redirect()->back()->with('message', 'Manager successfully created');
            } else {
                return redirect()->back()->with('message', 'Error while creating new subadmin');
            }
        }
    }

    public function EditSubAdmin(Request $request) {
       
        $validation = Validator::make($request->all(), [
                    'user_name' => 'required',
                    'email' => 'required|email',
                    'mobile' => 'required|min:10',
                    'center_id' => 'required',
                    'status' => 'required'
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('user_name', 'email', 'mobile', 'loc', 'status'));
        }

//DB::enableQueryLog(); 
        $query = DB::table('abc_admin as admin')
                ->select('admin.id', 'admin.email', 'admin.mobile');
        $query = $query->Where('admin.id', '!=', $request->id);
        $id = $request->id;
        $email = $request->email;
        $mobile = $request->mobile;
		
		$query = $query->join('abc_manager_centre_tag as centre', 'centre.mid', '=',
                'admin.id');
        //$centerid = $request->center_id;
		for($i=0;$i<count($request->center_id);$i++){
			$query = $query->where('centre.cid', $request->center_id);
		}
        $query = $query->where(function($query) use ($mobile, $email) {
            $query->orWhere('admin.email', $email);
            $query->orWhere('admin.mobile', $mobile);
        });
        $status = $query->get();
//        dd(DB::getQueryLog());
        $statusv = count($status);
        if (($statusv > 0)) {
            return redirect()->back()->withErrors(['message', 'Mobile/Email id/Centre Manager  already exists. Please use a different Mobile Number/Email id/Centre']);
        } else {

            $data = Admin::Where('id', $request->id)->where('admin_role', 2)->first();

            $changed_data = array('id'=>$request->id,
								'user_name'=>$request->user_name,
								'email'=>$request->email,
								'mobile'=>$request->mobile,
								//'center_id'=>implode(',',$request->center_id),
								'cmt'=>$request->cmt,
								'status'=>$request->status,
								);

			DB::table('abc_manager_centre_tag')->where('mid', $request->id)->delete();
			for($i=0;$i<count($request->center_id);$i++){
				//DB::table('abc_manager_centre_tag')
				DB::table('abc_manager_centre_tag')->insert(
					['mid' => $request->id, 'cid' => $request->center_id[$i]]
				);
			}

            $diff_in_data = array_diff_assoc($data->getOriginal(), $changed_data);

            $keys_to_be_updated = array_keys($diff_in_data);

            $data_to_update = [];
            $diff_in_data_to_save = [];
            for ($i = 0; $i < count($keys_to_be_updated); $i++) {
                if (isset($changed_data[$keys_to_be_updated[$i]])) {

                    $data_to_update[$keys_to_be_updated[$i]] = $changed_data[$keys_to_be_updated[$i]];
                    $diff_in_data_to_save[$keys_to_be_updated[$i]] = $diff_in_data[$keys_to_be_updated[$i]];
                }
            }


            $logData = array('subject_id' => $request->id, 'user_id' => Auth::id(), 'table_used' => 'abc_admin',
                'description' => 'update', 'data_prev' => urldecode(http_build_query($diff_in_data_to_save)), 'data_now' => urldecode(http_build_query($data_to_update))
            );

            //dd($logData);
            saveQueryLog($logData);
            // dd($data_to_update);
            //$changed_data = $request->all();

           // unset($changed_data['_token']);

            $updateUser = Admin::Where('id', $request->id)->update($changed_data);

            if ($updateUser) {

                return redirect()->back()->with('message', 'Manager updated successfully.');
            } else {
                return redirect()->back()->with('message', 'Error while updating subadmin');
            }
        }
    }

    public function GetSubAdminData(Request $request) {
        $subadm = Admin::where('id', base64_decode($request->id))
                ->where('admin_role', 2)
                ->orderBy('created_at', 'DESC')
                ->first();

		$centreData = DB::table('abc_manager_centre_tag')->where('mid', base64_decode($request->id))->get();
		$centre_data = [];
		foreach($centreData as $key=>$value){
			array_push($centre_data,$value->cid);
		}
        $data = array(
            'id' => $subadm['id'],
            'name' => $subadm['user_name'],
            'email' => $subadm['email'],
            'subadmin_id' => $subadm['code'],
            'mobile' => $subadm['mobile'],
            'center_id' => $centre_data,
            'comment' => $subadm['cmt'],
            'status' => $subadm['status'],
        );
		
        echo json_encode($data);
    }

    public function DeleteSubAdmin($id) {
        $data = Admin::where('id', '=', base64_decode($id))->where('admin_role', 2)->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_admin',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($data->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);
        $status = DB::table('abc_admin')->where('id', base64_decode($id))->where('admin_role', 2)->delete();
        if ($status) {
            return redirect('admin/view-manager-availability')->with('message', 'Manager deleted successfully');
        } else {
            return redirect()->back()->with('message', 'Error while deleting subadmin');
        }
    }

}
