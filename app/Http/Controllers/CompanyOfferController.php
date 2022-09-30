<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use Intervention\Image\ImageManagerStatic as Image;
use App\Modal\Customer;
use App\Modal\CompanyClient;
use App\Modal\Category;
use App\Modal\CompanyOffer;

class CompanyOfferController extends Controller {
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
    public function AddNewCompanyOffer(Request $request) {

		//dd($request->all());
        $validation = Validator::make($request->all(), [
                    'co_compid' => 'required',
                    'co_catid' => 'required',
                    //'co_configid' => 'required',
                    'co_allctedhrs' => 'required',
					'co_allctedmnthhrs' => 'required',
                    'con_date_range' => 'required',
					'co_offerdays'=>'required',
					'co_ofrtimefrom'=>'required',
					'co_ofrtimeto'=>'required',
                    'co_status' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('co_compid', 'co_catid', 'co_allctedhrs', 'con_date_range', 'co_status'));
        }

        $status = CompanyOffer::where('co_compid', $request->co_compid)
                ->where('co_catid', $request->co_catid)
                ->where('co_offerdays', $request->co_offerdays)
                ->first();

        if ($status) {
            return redirect()->back()->withErrors(['message', 'Client offer already exists']);
        } else {

            if (!empty($request->con_date_range)) {
                $dates = explode('and', $request->con_date_range);
                $from = date("Y-m-d", strtotime($dates[0]));
                $to = date("Y-m-d", strtotime($dates[1]));
            }
            $dataToSave = array('co_compid' => $request->co_compid,
                'co_catid' => $request->co_catid,
                'co_allctedmnthhrs' => $request->co_allctedmnthhrs,
                'co_allctedhrs' => $request->co_allctedhrs,
                'co_cntrctstrtdte' => $from,
                'co_cntrctenddte' => $to,
				'co_offerdays'=>implode(',',$request->co_offerdays),
				'co_ofrtimefrom' => $request->co_ofrtimefrom,
				'co_ofrtimeto' => $request->co_ofrtimeto,
                'co_status' => $request->co_status,
            );
            $id = CompanyOffer::create($dataToSave)->id;
            $logData = array('subject_id' => $id, 'user_id' => Auth::id(), 'table_used' => 'abc_client_offer',
                'description' => 'insert', 'data_prev' => '', 'data_now' => urldecode(http_build_query($dataToSave))
            );
            saveQueryLog($logData);
            if ($id) {
                return redirect()->back()->with('message', 'Client offer successfully created');
            } else {
                return redirect()->back()->with('message', 'An error occurred while creating the client offer');
            }
        }
    }

    /*

     * View customers 
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function CompanyOffers(Request $request) {

        if (Auth::guard('admin')->user()->admin_role == 1 || Auth::guard('admin')->user()->admin_role == 2) {
            $catData = Category::select('acat_id', 'acat_name')->Where('acat_type', '!=', 'Package')->orderby('acat_name', 'asc')->get();
            $custData = CompanyClient::select('cc_name', 'cc_id')->where('cc_status', 1)->get();
            $pageData = CompanyOffer::orderby('created_at', 'desc')->get();

            return view('admin.company.manage-companyoffer', array('custdata' => $custData, 'catData' => $catData, 'data' => $pageData));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function EditCompanyOffer(Request $request) {
        
//        dd($request->all());
        $validation = Validator::make($request->all(), [
                    'co_compid' => 'required',
                    'co_catid' => 'required',
                    //'co_configid' => 'required',
                    'co_allctedhrs' => 'required',
					'co_allctedmnthhrs' => 'required',
                    //'con_date_range' => 'required',
					'co_offerdays'=>'required',
					'co_ofrtimefrom'=>'required',
					'co_ofrtimeto'=>'required',
                    'co_status' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('co_compid', 'co_catid', 'co_configid', 'co_allctedhrs', 'con_date_range', 'co_status'));
        }

        $status = CompanyOffer::where('co_compid', $request->co_compid)
                ->where('co_catid', $request->co_catid)
                ->where('co_offerdays', $request->co_offerdays)
                ->where('co_id', '!=', $request->id)
                ->first();

        if ($status) {
            return redirect()->back()->withErrors(['message', 'Client offer already exists']);
        } else {
            $data = CompanyOffer::Where('co_id', $request->id)->first();
			$from = '';
			$to = '';
            if (!empty($request->con_date_range)) {
                $dates = explode('and', $request->con_date_range);
                $from = date("Y-m-d", strtotime($dates[0]));
                $to = date("Y-m-d", strtotime($dates[1]));
            }
            
            $changed_data = array('co_compid' => $request->co_compid,
                'co_catid' => $request->co_catid,
              'co_allctedmnthhrs' => $request->co_allctedmnthhrs,
                'co_allctedhrs' => $request->co_allctedhrs,
                'co_cntrctstrtdte' => $from == ''? $data['co_cntrctstrtdte'] : $from,
                'co_cntrctenddte' => $to == ''? $data['co_cntrctenddte'] : $to,
				'co_offerdays'=>implode(',',$request->co_offerdays),
				'co_ofrtimefrom' => $request->co_ofrtimefrom,
				'co_ofrtimeto' => $request->co_ofrtimeto,
                'co_status' => $request->co_status,
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
            $logData = array('subject_id' => $request->id, 'user_id' => Auth::id(), 'table_used' => 'abc_client_offer',
                'description' => 'update', 'data_prev' => urldecode(http_build_query($diff_in_data_to_save)), 'data_now' => urldecode(http_build_query($changed_data))
            );
            //dd($logData);
            saveQueryLog($logData);
            $updateCat = CompanyOffer::Where('co_id', $request->id)->update($changed_data);
            if ($updateCat) {
                return redirect()->back()->with('message', 'Client offer successfully updated');
            } else {
                return redirect()->back()->with('message', 'Error while updating the client offer');
            }
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function DeleteCompanyOffer($id) {
        $data = CompanyOffer::where('co_id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_client_offer',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($data->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);
        $status = DB::table('abc_client_offer')->where('co_id', base64_decode($id))->delete();
        if ($status) {
            return redirect()->back()->with('message', 'Client offer successfully deleted');
        } else {
            return redirect()->back()->with('message', 'An error occurred while deleting the client offer');
        }
    }

    public function GetCompanyOfferData(Request $request) {
        $data = CompanyOffer::Where('co_id', base64_decode($request->id))->first();
        echo json_encode($data);
    }

}
