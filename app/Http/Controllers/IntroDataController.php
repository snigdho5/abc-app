<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use Mail;
use App\Modal\IntroData;

class IntroDataController extends Controller {
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
    public function AddNewIntroData(Request $request) {
        //dd($request->all());
        $validation = Validator::make($request->all(), [
                    'intro_text' => 'required',
                    'intro_status' => 'required',
                    'intro_url' => 'required',
                    'intro_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('intro_text', 'intro_status', 'intro_url','intro_image'));
        }
        $status = IntroData::Where('intro_text', $request->intro_text)
                ->first();

        if ($status) {
            return redirect()->back()->withErrors(['message', 'This Intro already exists']);
        } else {
            $name = '';
            if ($request->hasFile('intro_image')) {
                $image = $request->file('intro_image');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/upload/intro/');
                $valImage = validateImage($image->getClientOriginalExtension());
                if ($valImage) {
                    $image->move($destinationPath, $name);
                } else {
                    return redirect()->back()->withErrors(['message', 'Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed.']);
                }
            }


            $dataToSave = array('intro_text' => $request->intro_text,
                'intro_image' => $name == '' ? '' : $name,
                'intro_url' => $request->intro_url,
                'intro_status' => $request->intro_status,
            );
            $id = IntroData::create($dataToSave)->id;


            $logData = array('subject_id' => $id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_intro',
                'description' => 'insert', 'data_prev' => '', 'data_now' => urldecode(http_build_query($dataToSave))
            );
            saveQueryLog($logData);
            if ($id) {
                return redirect()->back()->with('message', 'Intro successfully created');
            } else {
                return redirect()->back()->with('message', 'An error occurred while creating the intro');
            }
        }
    }

    /*

     * View customers 
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function IntroDatas(Request $request) {

        if (Auth::guard('admin')->user()->admin_role == 1) {
            $query = IntroData::query();
            if (!empty($request->search)) {
                $search = $request->search;

                $query = $query->where(function($query) use ($search) {
                    $query->orWhere('intro_text', 'LIKE', '%' . $search . '%');
                });
            }
            $pageData = $query->orderby('created_at', 'DESC')
                    ->paginate(10)
                    ->withPath('?search=' . $request->search);
            return view('admin.offer.manage-intro', array('data' => $pageData));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function EditIntroData(Request $request) {
//        dd($request->all());
        $validation = Validator::make($request->all(), [
                    'intro_text' => 'required',
                    'intro_status' => 'required',
                    'intro_url' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('intro_text', 'intro_status', 'intro_url','intro_image'));
        }

        $status = IntroData::Where('intro_text', $request->intro_text)
                ->where('intro_id', '!=', $request->id)
                ->first();


        if ($status) {
            return redirect()->back()->withErrors(['message', 'This intro already exists']);
        } else {
            $data = IntroData::Where('intro_id', $request->id)->first();
            $name = '';
            if ($request->hasFile('intro_image')) {
                $image = $request->file('intro_image');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/upload/intro/');
                $valImage = validateImage($image->getClientOriginalExtension());
                if ($valImage) {
                    $image->move($destinationPath, $name);
                } else {
                    return redirect()->back()->withErrors(['message', 'Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed.']);
                }
            }

            $changed_data = array('intro_text' => $request->intro_text,
                'intro_image' => $name == '' ? $data['intro_image'] : $name,
                'intro_url' => $request->intro_url,
                'intro_status' => $request->intro_status
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
            $logData = array('subject_id' => $request->id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_intro',
                'description' => 'update', 'data_prev' => urldecode(http_build_query($diff_in_data_to_save)), 'data_now' => urldecode(http_build_query($changed_data))
            );
            saveQueryLog($logData);
            $status = IntroData::Where('intro_id', $request->id)->update($changed_data);
            if ($status) {
                return redirect()->back()->with('message', 'Intro successfully updated');
            } else {
                return redirect()->back()->with('message', 'An error occurred while updating the intro');
            }
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function DeleteIntroData($id) {
        $users = IntroData::where('intro_id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_ms_intro',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($users->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);

        $status = DB::table('abc_ms_intro')->where('intro_id', base64_decode($id))->delete();
        if ($status) {
            return redirect()->back()->with('message', 'Intro successfully deleted');
        } else {
            return redirect()->back()->with('message', 'Error while deleting intro');
        }
    }

    public function GetIntroData(Request $request) {
        $data = IntroData::Where('intro_id', base64_decode($request->id))->first();
        echo json_encode($data);
    }

}
