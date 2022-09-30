<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use Intervention\Image\ImageManagerStatic as Image;
use App\Modal\Post;

class PostController extends Controller {
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

    /*

     * View customers 
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function Posts() {
        if (Auth::guard('admin')->user()->admin_role == 1) {
            $data = Post::orderBy('created_at', 'DESC')->get();
            return view('admin.business_connect.manage-posts', array('data' => $data));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function EditPost(Request $request) {

        $validation = Validator::make($request->all(), [
                    'post_content' => 'required',
                    'post_status' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('post_content', 'post_status'));
        }


        /*$status = Post::where('post_content', $request->post_content)
                ->where('post_id', '!=', $request->post_id)
                ->first();
				
		dd($status);;
        if ($status) {
            return redirect()->back()->withErrors(['message', 'Post  already exists by another user']);
        } else {*/

            if ($request->post_status == 1) {
                $changed_data = array('post_content' => $request->post_content,
                    'post_status' => $request->post_status,
                    'aprroved_by' => Auth::id(),
                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                    'published_date' => date("Y-m-d H:i:s"),
                );
            } else {
                $changed_data = array('post_content' => $request->post_content,
                    'post_status' => $request->post_status,
                    'unpublished_date' => date("Y-m-d H:i:s"),
                );
            }
            $data = Post::Where('post_id', $request->post_id)->first();
            $diff_in_data = array_diff_assoc($data->getOriginal(), $changed_data);
            $diff_in_data_to_save = array();
            $keys_to_be_updated = array_keys($diff_in_data);

            for ($i = 0; $i < count($keys_to_be_updated); $i++) {
                if (isset($changed_data[$keys_to_be_updated[$i]])) {
                    $data_to_update[$keys_to_be_updated[$i]] = $changed_data[$keys_to_be_updated[$i]];
                    $diff_in_data_to_save[$keys_to_be_updated[$i]] = $diff_in_data[$keys_to_be_updated[$i]];
                }
            }
            $logData = array('subject_id' => $request->post_id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_post',
                'description' => 'update', 'data_prev' => urldecode(http_build_query($diff_in_data_to_save)), 'data_now' => urldecode(http_build_query($changed_data))
            );
            //dd($logData);
            saveQueryLog($logData);
            $updateCat = Post::Where('post_id', $request->post_id)->update($changed_data);
            if ($updateCat) {
                return redirect()->back()->with('message', 'Post successfully updated');
            } else {
                return redirect()->back()->with('message', 'Error while updating the post');
            }
        //}
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function DeletePost($id) {
        $data = Post::where('post_id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_ms_post',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($data->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);
        $status = DB::table('abc_ms_post')->where('post_id', base64_decode($id))->delete();
        if ($status) {
            return redirect()->back()->with('message', 'Category successfully deleted');
        } else {
            return redirect()->back()->with('message', 'An error occurred while deleting the category');
        }
    }

    public function GetPostData(Request $request) {
        $data = Post::Where('post_id', base64_decode($request->id))->first();
        echo json_encode($data);
    }

}
