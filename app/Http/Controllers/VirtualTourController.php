<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use App\Modal\Centre;
use App\Modal\VirtualTour;

class VirtualTourController extends Controller {
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
    public function AddNewVirtualTour(Request $request) {
        //dd($request->all());
        $validation = Validator::make($request->all(), [
                    'vt_centre_id' => 'required',
                    'vt_title' => 'required',
                    'vt_subtitle' => 'required',
                    'vt_embed_map' => 'required',
                    'vt_status' => 'required',
                    'vt_th_img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('vt_centre_id', 'vt_title', 'vt_subtitle', 'vt_embed_map', 'vt_th_img'));
        }
        $status = VirtualTour::Where('vt_centre_id', $request->vt_centre_id)
                ->Where('vt_embed_map', $request->vt_embed_map)
                ->first();

        if ($status) {
            return redirect()->back()->withErrors(['message', 'This Intro already exists']);
        } else {
            $name = '';
            if ($request->hasFile('vt_th_img')) {
                $image = $request->file('vt_th_img');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/upload/vtour/');
                $valImage = validateImage($image->getClientOriginalExtension());
                if ($valImage) {
                    $image->move($destinationPath, $name);
                } else {
                    return redirect()->back()->withErrors(['message', 'Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed.']);
                }
            }


            $dataToSave = array('vt_centre_id' => $request->intro_text,
                'vt_th_img' => $name == '' ? '' : $name,
                'vt_status' => $request->vt_status,
                'vt_centre_id' => $request->vt_centre_id,
                'vt_title' => $request->vt_title,
                'vt_subtitle' => $request->vt_subtitle,
                'vt_embed_map' => $request->vt_embed_map,
            );
            $id = VirtualTour::create($dataToSave)->id;


            $logData = array('subject_id' => $id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_virtual_tour',
                'description' => 'insert', 'data_prev' => '', 'data_now' => urldecode(http_build_query($dataToSave))
            );
            saveQueryLog($logData);
            if ($id) {
                return redirect()->back()->with('message', 'Virtual tour successfully created');
            } else {
                return redirect()->back()->with('message', 'An error occurred while creating the virtual tour');
            }
        }
    }

    /*

     * View customers 
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function VirtualTours(Request $request) {

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
			$centreData = Centre::orderBy('centre', 'ASC')->whereIn('centre_id', $centre_data)->get();
	    }else{
		   $centreData = Centre::orderBy('centre', 'ASC')->get();
		
	    }
            $query = VirtualTour::query();
            if (!empty($request->search)) {
                $search = $request->search;

                $query = $query->where(function($query) use ($search) {
                    $query->orWhere('intro_text', 'LIKE', '%' . $search . '%');
                });
            }
			 if(Auth::guard('admin')->user()->admin_role == 2){
				
				$centreData1 = DB::table('abc_manager_centre_tag')
							->select('cid')
							->where('mid', Auth::guard('admin')->user()->id)
							->get();
				$centre_data = [];
				foreach($centreData1 as $key=>$value){
					array_push($centre_data,$value->cid);
				 }
				$query = $query->whereIn('vt_centre_id', $centre_data);
			 }
            $pageData = $query->orderby('created_at', 'DESC')
                    ->paginate(10)
                    ->withPath('?search=' . $request->search);
            return view('admin.virtual_tour.manage-virtualtour', array('data' => $pageData, 'centreData' => $centreData));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function EditVirtualTour(Request $request) {
//        dd($request->all());
        $validation = Validator::make($request->all(), [
                    'vt_centre_id' => 'required',
                    'vt_title' => 'required',
                    'vt_subtitle' => 'required',
                    'vt_embed_map' => 'required',
                    'vt_status' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('vt_centre_id', 'vt_title', 'vt_subtitle', 'vt_embed_map'));
        }

        $status = VirtualTour::Where('vt_centre_id', $request->vt_centre_id)
                ->Where('vt_embed_map', $request->vt_embed_map)
                ->where('vt_id', '!=', $request->id)
                ->first();


        if ($status) {
            return redirect()->back()->withErrors(['message', 'This virtual tour already exists']);
        } else {
            $data = VirtualTour::Where('vt_id', $request->id)->first();
            $name = '';
            if ($request->hasFile('vt_th_img')) {
                $image = $request->file('vt_th_img');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/upload/vtour/');
                $valImage = validateImage($image->getClientOriginalExtension());
                if ($valImage) {
                    $image->move($destinationPath, $name);
                } else {
                    return redirect()->back()->withErrors(['message', 'Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed.']);
                }
            }


            $changed_data = array('vt_centre_id' => $request->intro_text,
                'vt_th_img' => $name == '' ? $data['vt_th_img'] : $name,
                'vt_status' => $request->vt_status,
                'vt_centre_id' => $request->vt_centre_id,
                'vt_title' => $request->vt_title,
                'vt_subtitle' => $request->vt_subtitle,
                'vt_embed_map' => $request->vt_embed_map,
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
            $logData = array('subject_id' => $request->id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_virtual_tour',
                'description' => 'update', 'data_prev' => urldecode(http_build_query($diff_in_data_to_save)), 'data_now' => urldecode(http_build_query($changed_data))
            );
            saveQueryLog($logData);
            $status = VirtualTour::Where('vt_id', $request->id)->update($changed_data);
            if ($status) {
                return redirect()->back()->with('message', 'Virtual tour successfully updated');
            } else {
                return redirect()->back()->with('message', 'An error occurred while updating the virtual tour');
            }
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function DeleteVirtualTour($id) {
        $users = VirtualTour::where('vt_id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_ms_virtual_tour',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($users->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);

        $status = DB::table('abc_ms_virtual_tour')->where('vt_id', base64_decode($id))->delete();
        if ($status) {
            return redirect()->back()->with('message', 'Virtual tour  successfully deleted');
        } else {
            return redirect()->back()->with('message', 'Error while deleting virtual tour ');
        }
    }

    public function GetVirtualTourData(Request $request) {
        $data = VirtualTour::Where('vt_id', base64_decode($request->id))->first();
        echo json_encode($data);
    }

}
