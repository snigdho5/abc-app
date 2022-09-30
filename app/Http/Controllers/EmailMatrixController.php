<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use App\Modal\Centre;
use App\Modal\EmailMatrix;

class EmailMatrixController extends Controller {
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
    public function AddNewEmailMatrix(Request $request) {
//        dd($request->all());

        $validation = Validator::make($request->all(), [
                    'centre_id' => 'required',
                    'em_per' => 'required',
                    'em_email' => 'required',
                    'em_phone' => 'required',
                    'em_status' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('center_id', 'em_per', 'em_email', 'em_phone'));
        }

        $query = EmailMatrix::query();

        $query = $query->where('centre_id', $request->centre_id);

        for ($i = 0; $i < count(array_filter($request->em_per)); $i++) {
            $cperson = $request->em_per[$i];
            $email = $request->em_email[$i];
            $phone = $request->em_phone[$i];
            $query = $query->where(function($query) use ($cperson, $email, $phone) {
                $query->orWhere('em_per', $cperson);
                $query->orWhere('em_email', $email);
                $query->orWhere('em_phone', $phone);
            });
        }

        $status = $query->first();


        if ($status) {
            return redirect()->back()->withErrors(['message', 'Name/Email/Phone Matrix already exists.']);
        }
        $id = 0;

        if (count(array_filter($request->em_per)) > 0) {

            for ($j = 0; $j < count(array_filter($request->em_per)); $j++) {
                if (!is_null($request->em_per[$j])) {
                    $dataToSave = array('centre_id' => $request->centre_id,
                        'em_per' => $request->em_per[$j],
                        'em_email' => $request->em_email[$j],
                        'em_phone' => $request->em_phone[$j],
                        'em_status' => $request->em_status,
                    );
                }

                $emailst = EmailMatrix::create($dataToSave);
            }

            $id = $emailst->id;
            $logData = array('subject_id' => $id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_emailmatrix',
                'description' => 'insert', 'data_prev' => '', 'data_now' => urldecode(http_build_query($dataToSave))
            );
            saveQueryLog($logData);
        }


        if ($id) {
            return redirect()->back()->with('message', 'Email Matrix successfully created');
        } else {
            return redirect()->back()->withErrors(['message', 'An error occurred while creating the email matrix.']);
        }
    }

    /*

     * View list 
     * Sanjit Bhardwaj
     * 11-01-2018
     */

    public function EmailMatrixs() {


        if (Auth::guard('admin')->user()->admin_role != '') {
			$centre_data = [];
            if (Auth::guard('admin')->user()->admin_role == 2) {
				$centreData = DB::table('abc_manager_centre_tag')
							->select('cid')
							->where('mid', Auth::guard('admin')->user()->id)
							->get();
				$centre_data = [];
				foreach($centreData as $key=>$value){
					array_push($centre_data,$value->cid);
				}
				
                $pageData = EmailMatrix::whereIn('centre_id', $centre_data)->orderBy('created_at', 'DESC')->get();

                $cData = Centre::whereIn('centre_id', $centre_data)->orderBy('centre', 'ASC')->get();

            } else {

                $pageData = EmailMatrix::orderBy('created_at', 'DESC')->get();
                $cData = Centre::orderBy('centre', 'ASC')->get();
            }

            return view('admin.email_matrix.manage-email-matrix', array('data' => $pageData, 'centreData' => $cData,'centreList'=>$centre_data));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    /*

     * Edit data
     * Sanjit Bhardwaj
     * 11-01-2018
     */

    public function EditEmailMatrix(Request $request) {
//dd($request->all());
        $validation = Validator::make($request->all(), [
                    'centre_id' => 'required',
                    'em_per' => 'required',
                    'em_email' => 'required',
                    'em_phone' => 'required',
                    'em_status' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('center_id', 'em_per', 'em_email', 'em_phone'));
        }

        DB::enableQueryLog();
        $flag =0; 
//        $query = EmailMatrix::query();

//        $query = $query->where('centre_id', $request->centre_id);

        for ($i = 1; $i < count(array_filter($request->em_per)); $i++) {
            
        $query = EmailMatrix::query();
            
            
            
            $cperson = $request->em_per[$i];
            $email = $request->em_email[$i];
            $phone = $request->em_phone[$i];
            $em_id = $request->em_id[$i];
            if($em_id>0) $query = $query->where('em_id', '!=', $em_id);
            $query = $query->where(function($query) use ($cperson, $email, $phone) {
                $query->orWhere('em_email', $email);
                $query->orWhere('em_phone', $phone);
            });
            $status = $query->first();
            if ($status) {
                $flag++;
            }
        }
        
        if($flag){
              return redirect()->back()->withErrors(['message', 'Name/Email/Phone Matrix already exists.']);
        }

        $data = DB::table('abc_ms_emailmatrix')->where('centre_id', '=', $request->centre_id)->get()->toArray();


        if (count(array_filter($request->em_per)) > 0) {

            for ($j = 0; $j < count(array_filter($request->em_per)); $j++) {
                if (!is_null($request->em_id[$j])) {
                    $changed_data = array('centre_id' => $request->centre_id,
                        'em_per' => $request->em_per[$j],
                        'em_email' => $request->em_email[$j],
                        'em_phone' => $request->em_phone[$j],
                        'em_id' => $request->em_id[$j],
                        'em_status' => $request->em_status,
                    );
                    $status = EmailMatrix::Where('centre_id', $request->centre_id)->Where('em_id', $request->em_id[$j])->update($changed_data);
                } else {

                    $query = EmailMatrix::query();

                    $query = $query->where('centre_id', $request->centre_id);

                    $cperson = $request->em_per[$j];
                    $email = $request->em_email[$j];
                    $phone = $request->em_phone[$j];
                    $query = $query->where(function($query) use ($cperson, $email, $phone) {
                        $query->orWhere('em_email', $email);
                        $query->orWhere('em_phone', $phone);
                    });

                    $status = $query->first();


                    if ($status) {
                        return redirect()->back()->withErrors(['message', 'Name/Email/Phone Matrix already exists.']);
                    }
                    $changed_data = array('centre_id' => $request->centre_id,
                        'em_per' => $request->em_per[$j],
                        'em_email' => $request->em_email[$j],
                        'em_phone' => $request->em_phone[$j],
                        'em_status' => $request->em_status,
                    );
                    $status = EmailMatrix::create($changed_data);
                }
            }
//die;
            $logData = array('subject_id' => $request->centre_id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_emailmatrix',
                'description' => 'update', 'data_prev' => urldecode(http_build_query($data)), 'data_now' => urldecode(http_build_query($changed_data))
            );
            saveQueryLog($logData);
        }

        if ($status) {
            return redirect()->back()->with('message', 'Email Matrix successfully updated');
        } else {
            return redirect()->back()->withErrors(['message', 'An error occurred while editing the email matrix']);
        }
    }

    /*

     * Edit data
     * Sanjit Bhardwaj
     * 11-01-2018
     */

    public function DeleteEmailMatrix($id) {
        $data = EmailMatrix::where('em_id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_ms_emailmatrix',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($data)), 'data_now' => ''
        );

        saveQueryLog($logData);

        $status = DB::table('abc_ms_emailmatrix')->where('em_id', base64_decode($id))->delete();
        if ($status) {
            return redirect()->back()->with('message', 'Email Matrix successfully deleted');
        } else {
            return redirect()->back()->withErrors(['message', 'An error occurred while deleting the email matrix']);
        }
    }

    public function GetEmailMatrixData(Request $request) {
        $data = EmailMatrix::Where('centre_id', base64_decode($request->id))->get();
        echo json_encode($data);
    }

    public function GetEmailMatrixFirstData(Request $request) {
        $data = Centre::select('centre_email', 'centre_mobile', 'centre')->Where('centre_id', ($request->id))->first();
        echo json_encode($data);
    }

}
