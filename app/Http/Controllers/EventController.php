<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use Intervention\Image\ImageManagerStatic as Image;
use App\Modal\Event;
use App\Modal\Centre;

class EventController extends Controller {
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
    public function AddNewEvent(Request $request) {

        $validation = Validator::make($request->all(), [
                    'e_name' => 'required',
                    'event_date_range' => 'required',
                    'e_detail' => 'required',
                    'e_centre_id' => 'required',
                    'e_status' => 'required',
                    'e_img' => 'required',
                    'e_gallery' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('e_name', 'event_date_range', 'e_detail', 'e_centre_id', 'e_status', 'e_img', 'e_gallery'));
        }

        $e_img = '';
        if ($request->hasFile('e_img')) {
            $image = $request->file('e_img');
            $e_img = $request->e_img->getClientOriginalName();
//            $name = time() . str_replace(' ', '_', $request->n_title) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/upload/event/');
            $valImage = validateImage($image->getClientOriginalExtension());
            if ($valImage) {

                $image_resize1 = Image::make($image->getRealPath());
                $image_resize1->resize(640, 500);
                $image_resize1->save(public_path('/upload/event/' . $e_img));

                $image_resize2 = Image::make($image->getRealPath());
                $image_resize2->resize(633, 256);
                $image_resize2->save(public_path('/upload/event/th/' . $e_img));

                $image_resize3 = Image::make($image->getRealPath());
                $image_resize3->resize(200, 200);
                $image_resize3->save(public_path('/upload/event/thm/' . $e_img));

//                $image->move($destinationPath, $n_img);
            } else {
                return redirect()->back()->withErrors(['message', 'Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed.']);
            }
        }

        $gal_name = '';
        $gal_name_arr = array();

        if (count($request->e_gallery) > 0) {
            for ($i = 0; $i < count($request->e_gallery); $i++) {
                if ($request->hasFile('e_gallery')) {
                    $image = $request->file('e_gallery')[$i];
                    $gal_name = $request->e_gallery[$i]->getClientOriginalName();
                    array_push($gal_name_arr, $gal_name);
                    $destinationPath = public_path('/upload/event/');
                    $valImage = validateImage($image->getClientOriginalExtension());
                    if ($valImage) {
                        $image_resize1 = Image::make($image->getRealPath());
                        $image_resize1->resize(640, 500);
                        $image_resize1->save(public_path('/upload/event/' . $gal_name));

                        $image_resize2 = Image::make($image->getRealPath());
                        $image_resize2->resize(633, 256);
                        $image_resize2->save(public_path('/upload/event/th/' . $gal_name));

                        $image_resize3 = Image::make($image->getRealPath());
                        $image_resize3->resize(200, 200);
                        $image_resize3->save(public_path('/upload/event/thm/' . $gal_name));
                    } else {
                        return redirect()->back()->withErrors(['message', 'Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed.']);
                    }
                }
            }
        }

        $dateArr = explode('and', $request->event_date_range);
        $e_centre_id = 0;
        if ($request->e_centre_id != '') {
            $e_centre_id = implode(',', $request->e_centre_id);
        }
        $dataToSave = array('e_name' => $request->e_name,
            'e_from' => date("Y-m-d H:i:s", strtotime($dateArr[0])),
            'e_to' => date("Y-m-d H:i:s", strtotime($dateArr[1])),
            'e_detail' => $request->e_detail,
            'e_centre_id' => $e_centre_id,
            'e_img' => $e_img == '' ? '' : $e_img,
            'e_gallery' => $gal_name == '' ? '' : implode(',', $gal_name_arr),
            'e_status' => $request->e_status,
        );

        $id = Event::create($dataToSave)->id;
        $logData = array('subject_id' => $id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_event',
            'description' => 'insert', 'data_prev' => '', 'data_now' => urldecode(http_build_query($dataToSave))
        );
        saveQueryLog($logData);
        if ($id) {
            return redirect()->back()->with('message', 'Event successfully created');
        } else {
            return redirect()->back()->with('message', 'An error occurred while creating the event');
        }
    }

    /*

     * View list 
     * Sanjit Bhardwaj
     * 11-01-2018
     */

    public function Events() {
        if (Auth::guard('admin')->user()->admin_role == 1) {
            $pageData = Event::orderBy('created_at', 'DESC')->get();
            $cData = Centre::orderBy('centre', 'ASC')->get();
            return view('admin.event.manage-event', array('data' => $pageData, 'cData' => $cData));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    /*

     * Edit data
     * Sanjit Bhardwaj
     * 11-01-2018
     */

    public function EditEvent(Request $request) {
//        dd($request->all());

        $validation = Validator::make($request->all(), [
                    'e_name' => 'required',
                    'e_detail' => 'required',
                    'e_centre_id' => 'required',
                    'e_status' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('e_name', 'req_date_range', 'e_detail', 'e_centre_id', 'e_status', 'e_img', 'e_gallery'));
        }

        $status = Event::Where('e_name', '=', $request->e_name)
                ->Where('e_id', '!=', $request->id)
                ->first();

        $data = DB::table('abc_ms_event')->where('e_id', '=', $request->id)->get()->toArray();

        // dd($data);

        $e_img = '';
        if ($request->hasFile('e_img')) {
            $image = $request->file('e_img');
            $e_img = $request->e_img->getClientOriginalName();
//            $name = time() . str_replace(' ', '_', $request->n_title) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/upload/event/');
            $valImage = validateImage($image->getClientOriginalExtension());
            if ($valImage) {

                $image_resize1 = Image::make($image->getRealPath());
                $image_resize1->resize(640, 500);
                $image_resize1->save(public_path('/upload/event/' . $e_img));

                $image_resize2 = Image::make($image->getRealPath());
                $image_resize2->resize(633, 256);
                $image_resize2->save(public_path('/upload/event/th/' . $e_img));

                $image_resize3 = Image::make($image->getRealPath());
                $image_resize3->resize(200, 200);
                $image_resize3->save(public_path('/upload/event/thm/' . $e_img));

//                $image->move($destinationPath, $n_img);
            } else {
                return redirect()->back()->withErrors(['message', 'Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed.']);
            }
        }

        $gal_name = '';
        $gal_name_arr = array();
        if (isset($request->centre_gallery)) {
            if (count($request->e_gallery) > 0) {
                for ($i = 0; $i < count($request->e_gallery); $i++) {
                    if ($request->hasFile('e_gallery')) {
                        $image = $request->file('e_gallery')[$i];
                        $gal_name = $request->e_gallery[$i]->getClientOriginalName();
                        array_push($gal_name_arr, $gal_name);
                        $destinationPath = public_path('/upload/event/');
                        $valImage = validateImage($image->getClientOriginalExtension());
                        if ($valImage) {
                            $image_resize1 = Image::make($image->getRealPath());
                            $image_resize1->resize(640, 500);
                            $image_resize1->save(public_path('/upload/event/' . $gal_name));

                            $image_resize2 = Image::make($image->getRealPath());
                            $image_resize2->resize(633, 256);
                            $image_resize2->save(public_path('/upload/event/th/' . $gal_name));

                            $image_resize3 = Image::make($image->getRealPath());
                            $image_resize3->resize(200, 200);
                            $image_resize3->save(public_path('/upload/event/thm/' . $gal_name));
                        } else {
                            return redirect()->back()->withErrors(['message', 'Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed.']);
                        }
                    }
                }
            }
        }

        $dateArr = '';
        $fromdate = '';
        $todate = '';
        if (isset($request->event_date_range)) {
            $dateArr = explode('and', $request->event_date_range);
            $fromdate = $dateArr[0];
            $todate = $dateArr[1];
        } else {
            $fromdate = $data[0]->e_from;
            $todate = $data[0]->e_to;
        }
        $changed_data = array('e_name' => $request->e_name,
            'e_from' => date("Y-m-d H:i:s", strtotime($fromdate)),
            'e_to' => date("Y-m-d H:i:s", strtotime($todate)),
            'e_detail' => $request->e_detail,
            'e_centre_id' => implode(',', $request->e_centre_id),
            'e_img' => $e_img == '' ? $data[0]->e_img : $e_img,
            'e_gallery' => $gal_name == '' ? $data[0]->e_gallery : implode(',', $gal_name_arr),
            'e_status' => $request->e_status,
            'updated_at' => date("Y-m-d H:i:s")
        );


        $diff_in_data_to_save = array();
        $diff_in_data = array_diff_assoc($data, $changed_data);

        $keys_to_be_updated = array_keys($diff_in_data);

        for ($i = 0; $i < count($keys_to_be_updated); $i++) {
            if (isset($changed_data[$keys_to_be_updated[$i]])) {
                $data_to_update[$keys_to_be_updated[$i]] = $changed_data[$keys_to_be_updated[$i]];
                $diff_in_data_to_save[$keys_to_be_updated[$i]] = $diff_in_data[$keys_to_be_updated[$i]];
            }
        }
        $logData = array('subject_id' => $request->id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_event',
            'description' => 'update', 'data_prev' => urldecode(http_build_query($diff_in_data_to_save)), 'data_now' => urldecode(http_build_query($changed_data))
        );
        saveQueryLog($logData);

        $status = Event::Where('e_id', $request->id)->update($changed_data);
        //$status = Ad::find($request->id)->update($changed_data);
        if ($status) {
            return redirect()->back()->with('message', 'Event successfully updated');
        } else {
            return redirect()->back()->with('message', 'An error occurred while editing the event');
        }
    }

    /*

     * Edit data
     * Sanjit Bhardwaj
     * 11-01-2018
     */

    public function DeleteEvent($id) {
        $data = Event::where('e_id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_ms_event',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($data->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);
        $status = DB::table('abc_ms_event')->where('e_id', base64_decode($id))->delete();
        if ($status) {
            return redirect()->back()->with('message', 'Event successfully deleted');
        } else {
            return redirect()->back()->with('message', 'An error occurred while deleting the event');
        }
    }

    public function GetEventData(Request $request) {

        $data = Event::Where('e_id', base64_decode($request->id))->first();
        echo json_encode($data);
    }

}
