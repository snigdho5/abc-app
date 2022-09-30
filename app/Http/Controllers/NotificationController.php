<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Auth;
use DB;
use App\Modal\Customer;
use App\Modal\AbcCampaign;
use App\Modal\NotificationDetail;

class NotificationController extends Controller {
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
    public function SendNotifications(Request $request) {



        $noti_type1 = '';
        $noti_type2 = '';

        $tcnt = 0;
        $smsmsg = '';
        $pushmsg = '';
        $validation = Validator::make($request->all(), [
                    'customRadio' => 'required',
                    'camp_name' => 'required',
                    'camp_img' => 'max:40|dimensions:max_width=600,max_height=300',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('customRadio', 'camp_name'));
        }

        $noti_img = '';
        if ($request->hasFile('camp_img')) {
            $image = $request->file('camp_img');
            $noti_img = time() . $request->file('camp_img')->getClientOriginalName(); //. str_replace(' ', '_', $request->camp_name) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/upload/pushn/');
            $valImage = validateImage($image->getClientOriginalExtension());
            if ($valImage) {
                $storagePath = Storage::disk('s3')->put($noti_img, file_get_contents($image));
                $image->move($destinationPath, $noti_img);
            } else if ($image->getClientOriginalExtension() == 'svg') {
                $storagePath = Storage::disk('s3')->put($noti_img, file_get_contents($image));
                $image->move($destinationPath, $noti_img);
            } else {
                return redirect()->back()->withErrors(['message', 'Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed.']);
            }
            Storage::disk('s3')->setVisibility($noti_img, 'public');
            $noti_img = Storage::disk('s3')->url($noti_img);
        }
        $campaignData = array('camp_name' => $request->camp_name, 'camp_img' => $noti_img);
        //dd($request->all());

        if (isset($request->sms) && $request->sms == 'on') {
            $noti_type1 = 'sms';
        }
        if (isset($request->push) && $request->push == 'on') {
            $noti_type2 = 'push';
        }

        $camp_id = AbcCampaign::create($campaignData)->id;
        if ($request->customRadio == 'allCust') {
            if ($noti_type2 == 'push') {
                $pn_image = $noti_img;
                $message = $request->camp_name;
                $headln_tit = 'ABC Alert';
                $uid = 0;
                $appURls = '/dashboard/';
                $res = prepare_push_noti_all($pn_image, $message, $headln_tit, $uid, $appURls, $camp_id, 1);
            }

            $customerData = Customer::all();


            foreach ($customerData as $key => $value) {
                $dataToSave = array('cust_id' => $value['cust_id'], 'cust_nm' => $value['cust_nme'], 'cust_mob' => $value['cust_mobile'], 'noti_type1' => $noti_type1, 'noti_type2' => $noti_type2, 'camp_id' => $camp_id,'u_platform'=>$request->select_platform);
                //dd($dataToSave);
                $insertid = NotificationDetail::create($dataToSave)->id;
                if ($noti_type1 == 'sms') {
                    $cMobile = $value['cust_mobile'];
                    $URLtext = str_replace(' ', '+', $request->camp_name);
                    if (strlen($cMobile) == 10)
                        $URLmob = "91" . $cMobile;
                    else
                        $URLmob = $cMobile;
                    $URLsms = 'https://www.myvaluefirst.com/smpp/sendsms?username=apjabc&password=Smdfb@1234&from=APJABC&to=';
                    $URLsms1 = $URLsms . $URLmob . "&Text=" . $URLtext;

                    if ($_SERVER['HTTP_HOST'] != 'localhost') {
                        get_sms($URLsms1);
                    } else {
//echo $URLsms.$URLmob."&text=".$URLtext;
//echo $URLsms.$URLmobtest."&text=".$URLtext;    
                    }
                }

//                if ($noti_type2 == 'push') {
//                    $pn_image = $noti_img;
//                    $message = $request->camp_name;
//                    $headln_tit = 'ABC Alert';
//                    $uid = $value['cust_mobile'];
//                    $appURls = '/dashboard/';
//                    $res = prepare_push_noti_check($pn_image, $message, $headln_tit, $uid, $appURls, $camp_id, 0);
//                    if ($res) {
//                        $tcnt = $tcnt + 1;
//                    }
//                }
            }
            if ($noti_type1 == 'sms') {
                $smsmsg = count($customerData) . ' SMS has been sent to user.';
            }

            if ($noti_type2 == 'push') {
                $pushmsg = count($customerData) . ' Push Notification has been sent to user.';
            }
            $campUpdate = array('camp_count1' => count($customerData), 'camp_count2' => $tcnt, 'camp_name' => $request->camp_name);
            DB::table('abc_ms_camp_cron')->where('camp_id', $camp_id)->update($campUpdate);
        } else {

            if (isset($request->seleCust) && count($request->seleCust) > 0) {
                for ($i = 0; $i < count($request->seleCust); $i++) {
                    $custdata = explode('-', $request->seleCust[$i]);
                    $dataToSave = array('cust_id' => $custdata[1], 'cust_nm' => $custdata[2], 'cust_mob' => $custdata[0], 'noti_type1' => $noti_type1, 'noti_type2' => $noti_type2, 'camp_id' => $camp_id,'u_platform'=>$request->select_platform);
                    //dd($dataToSave);
                    $insertid = NotificationDetail::create($dataToSave)->id;
                    if ($noti_type1 == 'sms') {
                        $cMobile = $custdata[0];
                        $URLtext = str_replace(' ', '+', $request->camp_name);
                        if (strlen($cMobile) == 10)
                            $URLmob = "91" . $cMobile;
                        else
                            $URLmob = $cMobile;
                        $URLsms = 'https://www.myvaluefirst.com/smpp/sendsms?username=apjabc&password=Smdfb@1234&from=APJABC&to=';
                        $URLsms1 = $URLsms . $URLmob . "&Text=" . $URLtext;
                        if ($_SERVER['HTTP_HOST'] != 'localhost') {
                            get_sms($URLsms1);
                        } else {
//echo $URLsms.$URLmob."&text=".$URLtext;
//echo $URLsms.$URLmobtest."&text=".$URLtext;    
                        }
                    }
                    if ($noti_type2 == 'push') {



                        $pn_image = $noti_img;
                        $message = $request->camp_name;
                        $headln_tit = 'ABC Alert';
                        $uid = $custdata[0];
                        $appURls = '/dashboard/';
//
                        DB::table('abc_camp_flag')->insert(
                                ['camp_id' => $camp_id, 'user_id' => $uid]
                        );
//                        $res = prepare_push_noti_check($pn_image, $message, $headln_tit, $uid, $appURls, $camp_id, 1);
//                        if ($res) {
//                            $tcnt = $tcnt + 1;
//                        }
                        prepare_push_noti_all($pn_image, $message, $headln_tit, $uid, $appURls, $camp_id, 2);
                    }
                }
            } else {
                return redirect()->back()->withErrors(['message', 'Please select at least 1 user']);
            }
            if ($noti_type1 == 'sms') {
                $smsmsg = count($request->seleCust) . ' SMS has been sent to user.';
            }

            if ($noti_type2 == 'push') {
                $pushmsg = count($request->seleCust) . ' Push Notification has been sent to user.';
            }
            $campUpdate = array('camp_count1' => count($request->seleCust), 'camp_count2' => $tcnt, 'camp_name' => $request->camp_name);
            DB::table('abc_ms_camp_cron')->where('camp_id', $camp_id)->update($campUpdate);
        }


        return redirect()->back()->with('message', $smsmsg . $pushmsg);
    }

    /*

     * View customers 
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function Notifications() {
        $data = Customer::orderBy('cust_nme', 'ASC')->get();
        return view('admin.notification.manage-notifications', array('data' => $data));
    }

}
