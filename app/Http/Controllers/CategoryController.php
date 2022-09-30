<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use Intervention\Image\ImageManagerStatic as Image;
use App\Modal\Category;

class CategoryController extends Controller {
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
    public function AddNewCategory(Request $request) {

        $validation = Validator::make($request->all(), [
                    'acat_name' => 'required',
                    'acat_type' => 'required',
                    'acat_per_type' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('acat_name', 'acat_type'));
        }

        $status = Category::where('acat_name', $request->acat_name)
                ->where('acat_type', $request->acat_type)
                ->first();

        if ($status) {
            return redirect()->back()->withErrors(['message', 'Category already exists']);
        } else {

            $flag_hour = 0;
            $flag_month = 0;
            $flag_year = 0;
            $flag_halfday = 0;
            $flag_fullday = 0;
            $flag_quart = 0;
            $flag_halfyear = 0;
            $acat_per_type = '';

			$business_address= 0;
			$high_internet= 0;
			$it_infra= 0;
			$parking_zone= 0;
			$twentyfour_access= 0;
			$event_activity= 0;
			
            $name = '';
            if ($request->hasFile('acat_img')) {
                $image = $request->file('acat_img');
                $name = time() . str_replace(' ', '_', $request->acat_name) . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/upload/category/');
                $image->move($destinationPath, $name);
            }
            if (isset($request->flag_hour) && $request->flag_hour == 'on') {
                $flag_hour = 1;
            }
            if (isset($request->flag_month) && $request->flag_month == 'on') {
                $flag_month = 1;
            }
            if (isset($request->flag_year) && $request->flag_year == 'on') {
                $flag_year = 1;
            }
            if (isset($request->flag_halfday) && $request->flag_halfday == 'on') {
                $flag_halfday = 1;
            }
            if (isset($request->flag_fullday) && $request->flag_fullday == 'on') {
                $flag_fullday = 1;
            }
            if (isset($request->flag_quart) && $request->flag_quart == 'on') {
                $flag_quart = 1;
            }
            if (isset($request->flag_halfyear) && $request->flag_halfyear == 'on') {
                $flag_halfyear = 1;
            }
			if (isset($request->business_address) && $request->business_address == 'on') {
                $business_address = 1;
            }
			if (isset($request->high_internet) && $request->high_internet == 'on') {
                $high_internet = 1;
            }
			if (isset($request->it_infra) && $request->it_infra == 'on') {
                $it_infra = 1;
            }
			if (isset($request->parking_zone) && $request->parking_zone == 'on') {
                $parking_zone = 1;
            }
			if (isset($request->twentyfour_access) && $request->twentyfour_access == 'on') {
                $twentyfour_access = 1;
            }
			if (isset($request->event_activity) && $request->event_activity == 'on') {
                $event_activity = 1;
            }
			
            if (isset($request->acat_per_type) && $request->acat_per_type != '') {
                $acat_per_type = implode(',', $request->acat_per_type);
            }
            $dataToSave = array('acat_name' => $request->acat_name,
                'acat_intro' => $request->acat_intro,
                'acat_type' => $request->acat_type,
                'flag_hour' => $flag_hour,
                'flag_month' => $flag_month,
                'flag_year' => $flag_year,
                'flag_halfday' => $flag_halfday,
                'flag_fullday' => $flag_fullday,
                'flag_quart' => $flag_quart,
                'flag_halfyear' => $flag_halfyear,
                'acat_img' => $name,
                'acat_per_type' => $acat_per_type,
                'acat_addons' => $request->acat_addons,
                'acat_status' => $request->acat_status,
                'acat_detail' => $request->acat_detail,
				'business_address' => $business_address,
                'high_internet' => $high_internet,
                'it_infra' => $it_infra,
                'parking_zone' => $parking_zone,
                'twentyfour_access' => $twentyfour_access,
                'event_activity' => $event_activity,	
            );
            $id = Category::create($dataToSave)->id;
            $logData = array('subject_id' => $id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_category',
                'description' => 'insert', 'data_prev' => '', 'data_now' => urldecode(http_build_query($dataToSave))
            );
            saveQueryLog($logData);
            if ($id) {
                return redirect()->back()->with('message', 'Category successfully created');
            } else {
                return redirect()->back()->with('message', 'An error occurred while creating the category');
            }
        }
    }

    /*

     * View customers 
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function Categories() {
        if (Auth::guard('admin')->user()->admin_role == 1) {
            $data = [];
            $data = Category::orderBy('created_at', 'DESC')->get();
            return view('admin.services.manage-category', array('data' => $data));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function EditCategory(Request $request) {

        //dd($request->all());
        /*$validation = Validator::make($request->all(), [
                    'acat_name' => 'required',
                    'acat_type' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('acat_name', 'acat_type'));
        }

        $status = Category::where('acat_name', $request->acat_name)
                ->where('acat_type', $request->acat_type)
                ->where('acat_id', '!=', $request->acat_id)
                ->first();
        if ($status) {
            return redirect()->back()->withErrors(['message', 'Category  already exists']);
        } else {*/
            $data = Category::Where('acat_id', $request->acat_id)->first();
            //dd($data);
            $flag_hour = 0;
            $flag_month = 0;
            $flag_year = 0;
            $flag_halfday = 0;
            $flag_fullday = 0;
            $flag_quart = 0;
            $flag_halfyear = 0;
			
			$business_address= 0;
			$high_internet= 0;
			$it_infra= 0;
			$parking_zone= 0;
			$twentyfour_access= 0;
			$event_activity= 0;
			
            $acat_per_type = '';

			if (isset($request->business_address) && $request->business_address == 'on') {
                $business_address = 1;
            }
			if (isset($request->high_internet) && $request->high_internet == 'on') {
                $high_internet = 1;
            }
			if (isset($request->it_infra) && $request->it_infra == 'on') {
                $it_infra = 1;
            }
			if (isset($request->parking_zone) && $request->parking_zone == 'on') {
                $parking_zone = 1;
            }
			if (isset($request->twentyfour_access) && $request->twentyfour_access == 'on') {
                $twentyfour_access = 1;
            }
			if (isset($request->event_activity) && $request->event_activity == 'on') {
                $event_activity = 1;
            }
            if (isset($request->flag_hour) && $request->flag_hour == 'on') {
                $flag_hour = 1;
            }
            if (isset($request->flag_month) && $request->flag_month == 'on') {
                $flag_month = 1;
            }
            if (isset($request->flag_year) && $request->flag_year == 'on') {
                $flag_year = 1;
            }
            if (isset($request->flag_halfday) && $request->flag_halfday == 'on') {
                $flag_halfday = 1;
            }
            if (isset($request->flag_fullday) && $request->flag_fullday == 'on') {
                $flag_fullday = 1;
            }
            if (isset($request->flag_quart) && $request->flag_quart == 'on') {
                $flag_quart = 1;
            }
            if (isset($request->flag_halfyear) && $request->flag_halfyear == 'on') {
                $flag_halfyear = 1;
            }
            if (isset($request->acat_per_type) && $request->acat_per_type != '') {
                $acat_per_type = implode(',', $request->acat_per_type);
            }

            $changed_data = array('acat_name' => $data['acat_name'],
                'acat_type' => $data['acat_type'],
                'flag_hour' => $flag_hour,
                'flag_month' => $flag_month,
                'flag_year' => $flag_year,
                'flag_halfday' => $flag_halfday,
                'flag_fullday' => $flag_fullday,
                'flag_quart' => $flag_quart,
                'flag_halfyear' => $flag_halfyear,
                'business_address' => $business_address,
                'high_internet' => $high_internet,
                'it_infra' => $it_infra,
                'parking_zone' => $parking_zone,
                'twentyfour_access' => $twentyfour_access,
                'event_activity' => $event_activity,
                'acat_detail' => $request->acat_detail,
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
            $logData = array('subject_id' => $request->acat_id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_category',
                'description' => 'update', 'data_prev' => urldecode(http_build_query($diff_in_data_to_save)), 'data_now' => urldecode(http_build_query($changed_data))
            );
            //dd($logData);
            saveQueryLog($logData);
            $updateCat = Category::Where('acat_id', $request->acat_id)->update($changed_data);
            if ($updateCat) {
                return redirect('admin/view-category')->with('message', 'Category successfully updated');
            } else {
                return redirect()->back()->with('message', 'Error while updating the category');
            }
        //}
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function DeleteCategory($id) {
        $data = Category::where('acat_id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_ms_category',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($data->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);
        $status = DB::table('abc_ms_category')->where('acat_id', base64_decode($id))->delete();
        if ($status) {
            return redirect()->back()->with('message', 'Category successfully deleted');
        } else {
            return redirect()->back()->with('message', 'An error occurred while deleting the category');
        }
    }

    public function GetCategoryData(Request $request) {
        $data = Category::Where('acat_id', base64_decode($request->id))->first();
        echo json_encode($data);
    }

    public function GetCatFlagData(Request $request) {
        $data = Category::Where('acat_id', $request->id)->first();
        echo json_encode($data);
    }
    

    
     public function ServiceChangeStatus($id) {

        $category = Category::where('acat_id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_ms_cust',
            'description' => 'change venter service status', 'data_prev' => urldecode(http_build_query($category->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);
        $dataToUpdate = array();
        if($category['acat_status'] == 1){
            $dataToUpdate = array('acat_status'=>0,'updated_at'=>date("Y-m-d H:i:s"));
        }else{
            $dataToUpdate = array('acat_status'=>1);
        }
        
        $status = DB::table('abc_ms_category')->where('acat_id', base64_decode($id))->update($dataToUpdate);
        if ($status) {
            return redirect()->back()->with('message', 'Service status changed successfully');
        } else {
            return redirect()->back()->with('message', 'Error while changing service status');
        }
    }

}
