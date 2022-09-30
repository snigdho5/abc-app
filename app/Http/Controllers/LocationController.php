<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use App\Modal\Location;

class LocationController extends Controller {
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
    public function AddNewLocation(Request $request) {

        $data = [];
        $validation = Validator::make($request->all(), [
                    'loc_name' => 'required',
                    'loc_status' => 'required'
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('loc_name', 'loc_status'));
        }
        $name = $request->loc_name;
        $status = Location::where(function($query) use($name) {
                    $query->Where('loc_name', $name);
                })
                ->first();

        if ($status) {
            return redirect()->back()->withErrors(['message', 'Location already exists']);
        } else {
            $name = '';
            if ($request->hasFile('loc_img')) {
                $image = $request->file('loc_img');
                $name = time() . str_replace(' ', '_', $request->loc_name) . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/upload/location/');
                $valImage = validateImage($image->getClientOriginalExtension());
                if ($valImage) {
                    $image->move($destinationPath, $name);
                } else {
                    return redirect()->back()->withErrors(['message', 'Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed.']);
                }
            }

            $dataToSave = array('loc_name' => $request->loc_name,
                'loc_status' => $request->loc_status,
                'loc_img' => $name == '' ? '' : $name,
            );
            $id = Location::create($dataToSave)->id;
            $logData = array('subject_id' => $id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_loc',
                'description' => 'insert', 'data_prev' => '', 'data_now' => urldecode(http_build_query($request->all()))
            );
            saveQueryLog($logData);
            if ($id) {
                return redirect()->back()->with('message', 'Location successfully created');
            } else {
                return redirect()->back()->with('message', 'Error while creating the location');
            }
        }
    }

    /*

     * View list 
     * Sanjit Bhardwaj
     * 11-01-2018
     */

    public function Locations() {
        if (Auth::guard('admin')->user()->admin_role == 1) {
            $locationData = Location::orderBy('loc_name', 'ASC')->get();
            return view('admin.services.view-locations', array('data' => $locationData));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    /*

     * Edit data
     * Sanjit Bhardwaj
     * 11-01-2018
     */

    public function EditLocation(Request $request) {

        $validation = Validator::make($request->all(), [
                    'loc_name' => 'required',
                    'loc_status' => 'required'
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('loc_name', 'loc_status'));
        }

        $name = $request->loc_name;
        $status = Location::where(function($query) use($name) {
                    $query->orWhere('loc_name', $name);
                })
                ->where('loc_id', '!=', $request->loc_id)
                ->first();
        if ($status) {
            return redirect()->back()->withErrors(['message', 'Location already exists']);
        } else {
            $name = '';
            $data = Location::where('loc_id', $request->loc_id)->first();
            if ($request->hasFile('loc_img')) {
                $image = $request->file('loc_img');
                $name = time() . str_replace(' ', '_', $request->loc_name) . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/upload/location/');
                $valImage = validateImage($image->getClientOriginalExtension());
                if ($valImage) {
                    $image->move($destinationPath, $name);
                } else {
                    return redirect()->back()->withErrors(['message', 'Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed.']);
                }
            }
            $data_to_update = array();
            $changed_data = array('loc_name' => $request->loc_name,
                'loc_status' => $request->loc_status,
                'loc_img' => $name == '' ? $data['loc_img'] : $name,
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
            $logData = array('subject_id' => $request->loc_id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_location',
                'description' => 'update', 'data_prev' => urldecode(http_build_query($diff_in_data_to_save)), 'data_now' => urldecode(http_build_query($changed_data))
            );
            saveQueryLog($logData);

            $updateLocation = Location::where('loc_id', $request->loc_id)->update($changed_data);

            if ($updateLocation) {
                return redirect('admin/view-location')->with('message', 'Location successfully updated');
            } else {
                return redirect()->back()->with('message', 'Error while updating the location');
            }
        }
    }

    /*

     * Edit data
     * Sanjit Bhardwaj
     * 11-01-2018
     */

    public function DeleteLocation($id) {
        $data = Location::where('loc_id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_ms_loc',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($data->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);
        $status = DB::table('abc_ms_location')->where('loc_id', base64_decode($id))->delete();
        if ($status) {
            return redirect('admin/view-location')->with('message', 'Location successfully deleted');
        } else {
            return redirect()->back()->with('message', 'Error while deleting the location');
        }
    }

    public function GetLocationData(Request $request) {
        $cat = Location::Where('loc_id', base64_decode($request->id))->first();
        // dd($cat);
        $data = array(
            'id' => $cat['loc_id'],
            'name' => $cat['loc_name'],
            'location_id' => $cat['loc_code'],
            'status' => $cat['loc_status'],
            'loc_img' => $cat['loc_img'],
        );
        echo json_encode($data);
    }

}
