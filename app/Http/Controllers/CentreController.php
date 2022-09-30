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
use App\Modal\TagCentreCategory;
use App\Modal\Category;

class CentreController extends Controller {
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
    public function AddNewCentre(Request $request) {
        //dd($request->all());
        $validation = Validator::make($request->all(), [
                    'location' => 'required',
                    'centre' => 'required',
                    'centre_address' => 'required',
                    'centre_email' => 'required',
                    'centre_phone' => 'required',
                    'centre_url' => 'required',
                    'status' => 'required',
                    'centre_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
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
            $prefix = "CENTREABC";
            $name = '';
            if ($request->hasFile('centre_image')) {
                $image = $request->file('centre_image');
                $name = time() . str_replace(' ', '_', $image->getClientOriginalName()) ;
                $destinationPath = public_path('/upload/centre/');
                $valImage = validateImage($image->getClientOriginalExtension());
                if ($valImage) {
                    $image->move($destinationPath, $name);
                } else {
                    return redirect()->back()->withErrors(['message', 'Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed.']);
                }
            }
            $mname = '';
            if ($request->hasFile('centre_menu')) {
                $image = $request->file('centre_menu');
                $mname = time() . str_replace(' ', '_', $image->getClientOriginalName()) ;
                $destinationPath = public_path('/upload/centre/');
                $valImage = validateImage($image->getClientOriginalExtension());
                if ($valImage) {
                    $image->move($destinationPath, $mname);
                } else {
                    return redirect()->back()->withErrors(['message', 'Uploaded menu file is not a valid image. Only JPG, PNG and GIF files are allowed.']);
                }
            }


            $gal_name = '';
            $gal_name_arr = array();

            if (count($request->centre_gallery) > 0) {
                for ($i = 0; $i < count($request->centre_gallery); $i++) {
                    if ($request->hasFile('centre_gallery')) {
                        $image = $request->file('centre_gallery')[$i];
                        $gal_name =time() . str_replace(' ', '_', $image->getClientOriginalName()) ;
                        array_push($gal_name_arr, $gal_name);
                        $destinationPath = public_path('/upload/centre/');
                        $valImage = validateImage($image->getClientOriginalExtension());
                        if ($valImage) {
                            $image->move($destinationPath, $gal_name);
                        } else {
                            return redirect()->back()->withErrors(['message', 'Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed.']);
                        }
                    }
                }
            }
            $flag_meeting_room = 0;
            $flag_co_working = 0;
            $flag_ser_office = 0;
            $flag_virtual_office = 0;
            $flag_built_to_suit = 0;
            $flag_abc_lounge = 0;

            $cat_id = 0;
            if (isset($request->flag_meeting_room) && $request->flag_meeting_room == 'on') {
                $cat_id = getCategoryIdByName('Meeting Room');
                $flag_meeting_room = $cat_id;
                $cat_id = 0;
            }
            if (isset($request->flag_co_working) && $request->flag_co_working == 'on') {
                $cat_id = getCategoryIdByName('Co-Working');
                $flag_co_working = $cat_id;
                $cat_id = 0;
            }
            if (isset($request->flag_ser_office) && $request->flag_ser_office == 'on') {
                $cat_id = getCategoryIdByName('Serviced office');
                $flag_ser_office = $cat_id;
                $cat_id = 0;
            }
            if (isset($request->flag_virtual_office) && $request->flag_virtual_office == 'on') {
                $cat_id = getCategoryIdByName('Virtual Office');
                $flag_virtual_office = $cat_id;
                $cat_id = 0;
            }
            if (isset($request->flag_built_to_suit) && $request->flag_built_to_suit == 'on') {
                $cat_id = getCategoryIdByName('Built To Suit');
                $flag_built_to_suit = $cat_id;
                $cat_id = 0;
            }
            if (isset($request->flag_abc_lounge) && $request->flag_abc_lounge == 'on') {
                $cat_id = getCategoryIdByName('ABC Lounge');
                $flag_abc_lounge = $cat_id;
                $cat_id = 0;
            }
            $online_flag = 0;
            $offline_flag = 0;
            $payment_flag = 0;
            if (isset($request->flag_payment_online) && $request->flag_payment_online == 'on') {
                $online_flag = 1;
            }
            if (isset($request->flag_payment_offline) && $request->flag_payment_offline == 'on') {
                $offline_flag = 1;
            }

            if ($online_flag && $offline_flag) {
                $payment_flag = 3;
            } else if ($online_flag && !$offline_flag) {
                $payment_flag = 1;
            } else if (!$online_flag && $offline_flag) {
                $payment_flag = 2;
            }



            $dataToSave = array('location' => $request->location,
                'centre' => $request->centre,
                'centre_address' => $request->centre_address,
                'centre_email' => $request->centre_email,
                'centre_mobile' => $request->centre_mobile,
                'centre_phone' => $request->centre_phone,
                'centre_url' => $request->centre_url,
                'centre_content' => $request->centre_content,
                'centre_vtlink' => $request->centre_vtlink,
                'centre_lat' => $request->centre_lat,
                'centre_long' => $request->centre_long,
                'flag_meeting_room' => $flag_meeting_room,
                'flag_co_working' => $flag_co_working,
                'flag_ser_office' => $flag_ser_office,
                'flag_virtual_office' => $flag_virtual_office,
                'flag_built_to_suit' => $flag_built_to_suit,
                'flag_abc_lounge' => $flag_abc_lounge,
                'flag_payment' => $payment_flag,
                'centre_image' => $name == '' ? '' : $name,
                'centre_menu' => $mname == '' ? '' : $mname,
                'centre_gallery' => $gal_name == '' ? '' : implode(',', $gal_name_arr),
                'status' => $request->status
            );
            $id = Centre::create($dataToSave)->id;

            $idTosave = 1000 + $id;
            $dataToUpdate = array('centre_code' => $prefix . date("y") . $idTosave);

            $logData = array('subject_id' => $id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_centre',
                'description' => 'insert', 'data_prev' => '', 'data_now' => urldecode(http_build_query($dataToSave))
            );
            saveQueryLog($logData);
            if ($id) {
                Centre::where('centre_id', $id)->update($dataToUpdate);
                $catData = Category::select('acat_id')->where('acat_type', 'Service')->where('acat_status', 1)->get();
                foreach ($catData as $key => $value) {
                    $dataToSave = array('centre_id' => $id, 'cat_id' => $value->acat_id);
                    TagCentreCategory::create($dataToSave);
                }
                return redirect()->back()->with('message', 'Centre successfully created');
            } else {
                return redirect()->back()->with('message', 'An error occurred while creating the centre');
            }
        }
    }

    public function getStatusTagCategory() {
        if (Auth::guard('admin')->user()->admin_role == 2) {
            $data = TagCentreCategory::where('centre_id', Auth::guard('admin')->user()->center_id)->orderBy('created_at', 'DESC')->get();

            return view('admin.services.manage-centerservice-category', array('data' => $data));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    public function ServiceChangeStatus($id) {

        $category = TagCentreCategory::where('id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_tag_centre_to_category',
            'description' => 'change center service status', 'data_prev' => urldecode(http_build_query($category->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);
        $dataToUpdate = array();
        if ($category['tstatus'] == 1) {
            $dataToUpdate = array('tstatus' => 0, 'updated_at' => date("Y-m-d H:i:s"));
        } else {
            $dataToUpdate = array('tstatus' => 1);
        }

        $status = DB::table('abc_tag_centre_to_category')->where('id', base64_decode($id))->update($dataToUpdate);
        if ($status) {
            return redirect()->back()->with('message', 'Service status changed successfully');
        } else {
            return redirect()->back()->with('message', 'Error while changing service status');
        }
    }

    /*

     * View customers 
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function Centres(Request $request) {

        if (Auth::guard('admin')->user()->admin_role == 1) {
            $locationData = Location::orderBy('loc_name', 'ASC')->get();
            $query = Centre::query();
            if (!empty($request->search)) {
                $search = $request->search;

                $query = $query->where(function($query) use ($search) {
                    $query->orWhere('centre', 'LIKE', '%' . $search . '%');
                    $query->orWhere('centre_address', 'LIKE', '%' . $search . '%');
                });
            }
            $pageData = $query->orderby('created_at', 'DESC')
                    ->paginate(10)
                    ->withPath('?search=' . $request->search);
            return view('admin.services.manage-centre', array('data' => $pageData, 'locationData' => $locationData));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function EditCentre(Request $request) {
        //dd($request->all());
        $validation = Validator::make($request->all(), [
                    'location' => 'required',
                    'centre' => 'required',
                    'centre_address' => 'required',
                    'centre_email' => 'required',
                    'centre_phone' => 'required',
                    'centre_url' => 'required',
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
                $name = time() . str_replace(' ', '_', $image->getClientOriginalName()) ;
                $destinationPath = public_path('/upload/centre/');
                $valImage = validateImage($image->getClientOriginalExtension());
                if ($valImage) {
                    $image->move($destinationPath, $name);
                } else {
                    return redirect()->back()->withErrors(['message', 'Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed.']);
                }
            }
            $gal_name = '';
            $gal_name_arr = array();

            if (isset($request->centre_gallery)) {
                if (count($request->centre_gallery) > 0) {
                    for ($i = 0; $i < count($request->centre_gallery); $i++) {
                        if ($request->hasFile('centre_gallery')) {
                            $image = $request->file('centre_gallery')[$i];
                            $gal_name = time() . str_replace(' ', '_', $image->getClientOriginalName()) ;
                            array_push($gal_name_arr, $gal_name);
                            $destinationPath = public_path('/upload/centre/');
                            $valImage = validateImage($image->getClientOriginalExtension());
                            if ($valImage) {
                                $image->move($destinationPath, $gal_name);
                            } else {
                                return redirect()->back()->withErrors(['message', 'Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed.']);
                            }
                        }
                    }
                }
            }

            $mname = '';
            if ($request->hasFile('centre_menu')) {
                $image = $request->file('centre_menu');
                $mname = time() . str_replace(' ', '_', $image->getClientOriginalName()) ;
                $destinationPath = public_path('/upload/centre/');
                $valImage = validateImage($image->getClientOriginalExtension());
                if ($valImage) {
                    $image->move($destinationPath, $mname);
                } else {
                    return redirect()->back()->withErrors(['message', 'Uploaded menu file is not a valid image. Only JPG, PNG and GIF files are allowed.']);
                }
            }


            $flag_meeting_room = 0;
            $flag_co_working = 0;
            $flag_ser_office = 0;
            $flag_virtual_office = 0;
            $flag_built_to_suit = 0;
            $flag_abc_lounge = 0;

            $cat_id = 0;
            if (isset($request->flag_meeting_room) && $request->flag_meeting_room == 'on') {
                $cat_id = getCategoryIdByName('Meeting Room');
                $flag_meeting_room = $cat_id;
                $cat_id = 0;
            }
            if (isset($request->flag_co_working) && $request->flag_co_working == 'on') {
                $cat_id = getCategoryIdByName('Co-Working');
                $flag_co_working = $cat_id;
                $cat_id = 0;
            }
            if (isset($request->flag_ser_office) && $request->flag_ser_office == 'on') {
                $cat_id = getCategoryIdByName('Serviced office');
                $flag_ser_office = $cat_id;
                $cat_id = 0;
            }
            if (isset($request->flag_virtual_office) && $request->flag_virtual_office == 'on') {
                $cat_id = getCategoryIdByName('Virtual Office');
                $flag_virtual_office = $cat_id;
                $cat_id = 0;
            }
            if (isset($request->flag_built_to_suit) && $request->flag_built_to_suit == 'on') {
                $cat_id = getCategoryIdByName('Built To Suit');
                $flag_built_to_suit = $cat_id;
                $cat_id = 0;
            }
            if (isset($request->flag_abc_lounge) && $request->flag_abc_lounge == 'on') {
                $cat_id = getCategoryIdByName('ABC Lounge');
                $flag_abc_lounge = $cat_id;
                $cat_id = 0;
            }
            $online_flag = 0;
            $offline_flag = 0;
            $payment_flag = 0;
            if (isset($request->flag_payment_online) && $request->flag_payment_online == 'on') {
                $online_flag = 1;
            }
            if (isset($request->flag_payment_offline) && $request->flag_payment_offline == 'on') {
                $offline_flag = 1;
            }

            if ($online_flag && $offline_flag) {
                $payment_flag = 3;
            } else if ($online_flag && !$offline_flag) {
                $payment_flag = 1;
            } else if (!$online_flag && $offline_flag) {
                $payment_flag = 2;
            }

            $changed_data = array('location' => $request->location,
                'centre' => $request->centre,
                'centre_address' => $request->centre_address,
                'centre_email' => $request->centre_email,
                'centre_mobile' => $request->centre_mobile,
                'centre_phone' => $request->centre_phone,
                'centre_url' => $request->centre_url,
                'centre_content' => $request->centre_content,
                'centre_vtlink' => $request->centre_vtlink,
                'centre_lat' => $request->centre_lat,
                'centre_long' => $request->centre_long,
                'flag_meeting_room' => $flag_meeting_room,
                'flag_co_working' => $flag_co_working,
                'flag_ser_office' => $flag_ser_office,
                'flag_virtual_office' => $flag_virtual_office,
                'flag_built_to_suit' => $flag_built_to_suit,
                'flag_abc_lounge' => $flag_abc_lounge,
                'flag_payment' => $payment_flag,
                'centre_image' => $name == '' ? $data['centre_image'] : $name,
                'centre_menu' => $mname == '' ? $data['centre_menu'] : $mname,
                'centre_gallery' => $gal_name == '' ? $data['centre_gallery'] : implode(',', $gal_name_arr),
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

    public function DeleteCentre($id) {
        $users = Centre::where('centre_id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_ms_centre',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($users->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);

        $status = DB::table('abc_ms_centre')->where('centre_id', base64_decode($id))->delete();
        if ($status) {
            return redirect()->back()->with('message', 'Centre successfully deleted');
        } else {
            return redirect()->back()->with('message', 'Error while deleting centre');
        }
    }

    public function GetCentreData(Request $request) {
        $data = Centre::Where('centre_id', base64_decode($request->id))->first();
        echo json_encode($data);
    }

    public function GetCentreLocation(Request $request) {
        $data = Centre::select('centre_url')->Where('centre_id', ($request->id))->first();
        echo json_encode($data);
    }

}
