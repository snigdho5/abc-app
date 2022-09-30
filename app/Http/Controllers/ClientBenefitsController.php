<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use App\Modal\ClientBenefits;
use Intervention\Image\ImageManagerStatic as Image;

class ClientBenefitsController extends Controller {
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
    public function AddNewClientBenefits(Request $request) {

        $data = [];
        $validation = Validator::make($request->all(), [
                    'cb_name' => 'required',
                    'cb_detail' => 'required',
                    'cb_image' => 'required'
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('cb_name', 'cb_detail'));
        }
        $name = $request->cb_name;
        $status = ClientBenefits::where(function($query) use($name) {
                    $query->Where('cb_name', $name);
                })
                ->first();

        if ($status) {
            return redirect()->back()->withErrors(['message', 'Client Benefits already exists']);
        } else {
			$cb_image = '';
			if ($request->hasFile('cb_image')) {
				$image = $request->file('cb_image');
				$cb_image = time()  . '.' . $image->getClientOriginalExtension();
	//            $name = time() . str_replace(' ', '_', $request->n_title) . '.' . $image->getClientOriginalExtension();
				$destinationPath = public_path('/upload/clientbenefits/');
				$valImage = validateImage($image->getClientOriginalExtension());
				if ($valImage) {

					$image_resize1 = Image::make($image->getRealPath());
					$image_resize1->resize(640, 500);
					$image_resize1->save(public_path('/upload/clientbenefits/' . $cb_image));

					$image_resize2 = Image::make($image->getRealPath());
					$image_resize2->resize(633, 256);
					$image_resize2->save(public_path('/upload/clientbenefits/th/' . $cb_image));

					$image_resize3 = Image::make($image->getRealPath());
					$image_resize3->resize(200, 200);
					$image_resize3->save(public_path('/upload/clientbenefits/thm/' . $cb_image));

	//                $image->move($destinationPath, $n_img);
				} else {
					return redirect()->back()->withErrors(['message', 'Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed.']);
				}
			}
		
            $name = '';

            $dataToSave = array('cb_name' => $request->cb_name,
                'cb_detail' => $request->cb_detail,
				'cb_image' => $cb_image == '' ? '' : $cb_image,
				'cb_status' => $request->cb_status,
            );
            $id = ClientBenefits::create($dataToSave)->id;
            $logData = array('subject_id' => $id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_client_benefits',
                'description' => 'insert', 'data_prev' => '', 'data_now' => urldecode(http_build_query($request->all()))
            );
            saveQueryLog($logData);
            if ($id) {
                return redirect()->back()->with('message', 'Client Benefits successfully created');
            } else {
                return redirect()->back()->with('message', 'Error while creating the Client Benefits');
            }
        }
    }

    /*

     * View list 
     * Sanjit Bhardwaj
     * 11-01-2018
     */

    public function ClientBenefits() {
        if (Auth::guard('admin')->user()->admin_role == 1) {
            $locationData = ClientBenefits::orderBy('cb_id', 'ASC')->get();
            return view('admin.clientbenefits.manage-clientbenefits', array('data' => $locationData));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    /*

     * Edit data
     * Sanjit Bhardwaj
     * 11-01-2018
     */

    public function EditClientBenefits(Request $request) {

        $validation = Validator::make($request->all(), [
                    'cb_name' => 'required',
                    'cb_detail' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('cb_name', 'cb_detail'));
        }

        $name = $request->cb_header;
        $status = ClientBenefits::where(function($query) use($name) {
                    $query->orWhere('cb_name', $name);
                })
                ->where('cb_id', '!=', $request->cb_id)
                ->first();
        if ($status) {
            return redirect()->back()->withErrors(['message', 'Client Benefits already exists']);
        } else {
			
			$cb_image = '';
			if ($request->hasFile('cb_image')) {
				$image = $request->file('cb_image');
				$cb_image = time() . '.' . $image->getClientOriginalExtension();
	//            $name = time() . str_replace(' ', '_', $request->n_title) . '.' . $image->getClientOriginalExtension();
				$destinationPath = public_path('/upload/clientbenefits/');
				$valImage = validateImage($image->getClientOriginalExtension());
				if ($valImage) {

					$image_resize1 = Image::make($image->getRealPath());
					$image_resize1->resize(640, 500);
					$image_resize1->save(public_path('/upload/clientbenefits/' . $cb_image));

					$image_resize2 = Image::make($image->getRealPath());
					$image_resize2->resize(633, 256);
					$image_resize2->save(public_path('/upload/clientbenefits/th/' . $cb_image));

					$image_resize3 = Image::make($image->getRealPath());
					$image_resize3->resize(200, 200);
					$image_resize3->save(public_path('/upload/clientbenefits/thm/' . $cb_image));

	//                $image->move($destinationPath, $n_img);
				} else {
					return redirect()->back()->withErrors(['message', 'Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed.']);
				}
			}
			
            $name = '';
            $data = ClientBenefits::where('cb_id', $request->id)->first();

            $data_to_update = array();
            $changed_data = array('cb_name' => $request->cb_name,
                'cb_detail' => $request->cb_detail,
				'cb_image' => $cb_image == '' ? $data->cb_image : $cb_image,
				'cb_status' => $request->cb_status,
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
            $logData = array('subject_id' => $request->id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_client_benefitsation',
                'description' => 'update', 'data_prev' => urldecode(http_build_query($diff_in_data_to_save)), 'data_now' => urldecode(http_build_query($changed_data))
            );
            saveQueryLog($logData);

            $updateLocation = ClientBenefits::where('cb_id', $request->id)->update($changed_data);

            if ($updateLocation) {
                return redirect()->back()->with('message', 'Client Benefits updated successfully.');
            } else {
                return redirect()->back()->with('message', 'Error while updating the Client Benefits');
            }
        }
    }

    /*

     * Edit data
     * Sanjit Bhardwaj
     * 11-01-2018
     */

    public function DeleteClientBenefits($id) {
        $data = ClientBenefits::where('cb_id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_ms_client_benefits',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($data->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);
        $status = DB::table('abc_ms_client_benefits')->where('cb_id', base64_decode($id))->delete();
        if ($status) {
            return redirect()->back()->with('message', 'Client Benefits deleted successfully');
        } else {
            return redirect()->back()->with('message', 'Error while deleting the Client Benefits');
        }
    }

    public function GetClientBenefitsData(Request $request) {
        $cat = ClientBenefits::Where('cb_id', base64_decode($request->id))->first();

        echo json_encode($cat);
    }

}
