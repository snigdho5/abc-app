<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use Intervention\Image\ImageManagerStatic as Image;
use App\Modal\News;
use App\Modal\Centre;

class NewsController extends Controller {
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
    public function AddNewNews(Request $request) {
//dd($request->all());
        $validation = Validator::make($request->all(), [
                    'n_title' => 'required',
                    'n_heading' => 'required',
                    'n_url' => 'required',
                    'n_ordering' => 'required',
                    'n_status' => 'required',
                    'n_img' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('n_title', 'n_url', 'n_ordering', 'n_status', 'n_heading'));
        }

        $n_img = '';
        if ($request->hasFile('n_img')) {
            $image = $request->file('n_img');
            $n_img = $request->n_img->getClientOriginalName();
//            $name = time() . str_replace(' ', '_', $request->n_title) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/upload/news/');
            $valImage = validateImage($image->getClientOriginalExtension());
            if ($valImage) {

                $image_resize1 = Image::make($image->getRealPath());
                $image_resize1->resize(640, 500);
                $image_resize1->save(public_path('/upload/news/' . $n_img));

                $image_resize2 = Image::make($image->getRealPath());
                $image_resize2->resize(633, 256);
                $image_resize2->save(public_path('/upload/news/th/' . $n_img));

                $image_resize2 = Image::make($image->getRealPath());
                $image_resize2->resize(200, 200);
                $image_resize2->save(public_path('/upload/news/thm/' . $n_img));

//                $image->move($destinationPath, $n_img);
            } else {
                return redirect()->back()->withErrors(['message', 'Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed.']);
            }
        }
        $n_featured = 0;
        //dd($request->all());
        if ($request->n_featured == 'on') {
            $n_featured = 1;
        }
        $dataToSave = array('n_title' => $request->n_title,
            'n_heading' => $request->n_heading,
            'n_url' => $request->n_url,
            'n_centre_id' => implode(',', $request->n_centre_id),
            'n_ordering' => $request->n_ordering,
            'n_img' => $n_img == '' ? '' : $n_img,
            'n_status' => $request->n_status,
            'n_featured' => $n_featured
        );

        $id = News::create($dataToSave)->id;
        $logData = array('subject_id' => $id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_news',
            'description' => 'insert', 'data_prev' => '', 'data_now' => urldecode(http_build_query($dataToSave))
        );
        saveQueryLog($logData);
        if ($id) {
            return redirect()->back()->with('message', 'News successfully created');
        } else {
            return redirect()->back()->with('message', 'An error occurred while creating the news');
        }
    }

    /*

     * View list 
     * Sanjit Bhardwaj
     * 11-01-2018
     */

    public function News() {
        if (Auth::guard('admin')->user()->admin_role == 1) {
            $pageData = News::orderBy('created_at', 'DESC')->get();
            $cData = Centre::orderBy('centre', 'ASC')->get();
            return view('admin.news.manage-news', array('data' => $pageData, 'cData' => $cData));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    /*

     * Edit data
     * Sanjit Bhardwaj
     * 11-01-2018
     */

    public function EditNews(Request $request) {
//        dd($request->all());
        $validation = Validator::make($request->all(), [
                    'n_title' => 'required',
                    'n_heading' => 'required',
                    'n_url' => 'required',
                    'n_ordering' => 'required',
                    'n_status' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('n_title', 'n_url', 'n_ordering', 'n_status'));
        }
        $status = News::Where('n_title', '=', $request->n_title)
                ->Where('n_id', '!=', $request->id)
                ->first();

        $data = DB::table('abc_ms_news')->where('n_id', '=', $request->id)->get()->toArray();

        // dd($data);

        $n_img = '';
        if ($request->hasFile('n_img')) {
            $image = $request->file('n_img');
            $n_img = $request->n_img->getClientOriginalName();
//            $name = time() . str_replace(' ', '_', $request->n_title) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/upload/news/');
            $valImage = validateImage($image->getClientOriginalExtension());
            if ($valImage) {
                $image_resize1 = Image::make($image->getRealPath());
                $image_resize1->resize(640, 500);
                $image_resize1->save(public_path('/upload/news/' . $n_img));

                $image_resize2 = Image::make($image->getRealPath());
                $image_resize2->resize(633, 256);
                $image_resize2->save(public_path('/upload/news/th/' . $n_img));

                $image_resize2 = Image::make($image->getRealPath());
                $image_resize2->resize(200, 200);
                $image_resize2->save(public_path('/upload/news/thm/' . $n_img));
//                $image->move($destinationPath, $name);
            } else {
                return redirect()->back()->withErrors(['message', 'Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed.']);
            }
        }
        $n_featured = 0;
        //dd($request->all());
        if ($request->n_featured == 'on') {
            $n_featured = 1;
        }
        $changed_data = array('n_title' => $request->n_title,
            'n_heading' => $request->n_heading,
            'n_url' => $request->n_url,
            'n_centre_id' => implode(',', $request->n_centre_id),
            'n_ordering' => $request->n_ordering,
            'n_img' => $n_img == '' ? $data[0]->n_img : $n_img,
            'n_status' => $request->n_status,
            'n_featured' => $n_featured,
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
        $logData = array('subject_id' => $request->id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_news',
            'description' => 'update', 'data_prev' => urldecode(http_build_query($diff_in_data_to_save)), 'data_now' => urldecode(http_build_query($changed_data))
        );
        saveQueryLog($logData);

        $status = News::Where('n_id', $request->id)->update($changed_data);
        //$status = Ad::find($request->id)->update($changed_data);
        if ($status) {
            return redirect()->back()->with('message', 'News successfully updated');
        } else {
            return redirect()->back()->with('message', 'An error occurred while editing the news');
        }
    }

    /*

     * Edit data
     * Sanjit Bhardwaj
     * 11-01-2018
     */

    public function DeleteNews($id) {
        $data = News::where('n_id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_ms_news',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($data->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);
        $status = DB::table('abc_ms_news')->where('n_id', base64_decode($id))->delete();
        if ($status) {
            return redirect()->back()->with('message', 'News successfully deleted');
        } else {
            return redirect()->back()->with('message', 'An error occurred while deleting the news');
        }
    }

    public function GetNewsData(Request $request) {

        $data = News::Where('n_id', base64_decode($request->id))->first();
        echo json_encode($data);
    }

}
