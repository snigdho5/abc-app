<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
//use App\Admin;
use App\Modal\Admin; 
date_default_timezone_set('Asia/Kolkata'); 

class LoginController extends Controller
{
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
    // protected $redirectTo = '/home';



       public function __construct()
       {
           $this->middleware('guest:admin')->except('logout');
       }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        return view('admin.auth.login');
    }


    public function loginAdmin(Request $request)
    {
      // Validate the form data
      $this->validate($request, [
        'email'   => 'required|email',
        'password' => 'required|min:6'
      ]);
      // Attempt to log the user in
      if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
        // if successful, then redirect to their intended location
       $data = array(
            'email' => Auth::guard('admin')->user()->email,
            'admin_role'=> Auth::guard('admin')->user()->admin_role,   
            'user_name'=>  Auth::guard('admin')->user()->user_name,  
            'mobile'=>Auth::guard('admin')->user()->mobile,  
			'location'=>$_SERVER['REMOTE_ADDR'],
			'agent'=>$_SERVER ['HTTP_USER_AGENT'],  
                );
         $logData = array('subject_id' => Auth::guard('admin')->user()->id, 'user_id' => Auth::guard('admin')->user()->id, 'table_used' => 'abc_admin',
            'description' => 'login', 'data_prev' => '', 'data_now' => urldecode(http_build_query($data))
        );
        saveQueryLog($logData);  
		$updated_at=date('Y-m-d H:i:s');
        $changed_data = array('updated_at' => $updated_at );  
        $status = Admin::Where('id', Auth::guard('admin')->user()->id)->update($changed_data);
	    return redirect()->intended(route('admin.dashboard'));
      }
      // if unsuccessful, then redirect back to the login with the form data
     return redirect()->back()->withErrors(['message', 'User name or password is not correct. '])->withInput($request->only('email', 'remember'));
    }

    public function logout()
    {
       $data = array(
            'email' => Auth::guard('admin')->user()->email,
            'admin_role'=> Auth::guard('admin')->user()->admin_role,   
            'user_name'=>  Auth::guard('admin')->user()->user_name,  
            'mobile'=>Auth::guard('admin')->user()->mobile,  
			'location'=>$_SERVER['REMOTE_ADDR'],
			'agent'=>$_SERVER ['HTTP_USER_AGENT'],  
                );
         $logData = array('subject_id' => Auth::guard('admin')->user()->id, 'user_id' => Auth::guard('admin')->user()->id, 'table_used' => 'abc_admin',
            'description' => 'logout', 'data_prev' => '', 'data_now' => urldecode(http_build_query($data))
        );
        Auth::guard('admin')->logout();
         saveQueryLog($logData);
        return redirect()->route('admin.auth.login');
    }

}