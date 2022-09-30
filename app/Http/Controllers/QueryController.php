<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\QueryExport;
use App\Exports\QuoteExport;
use Intervention\Image\ImageManagerStatic as Image;
use App\Modal\Query;

class QueryController extends Controller {
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

    public function Queries() {
        if (Auth::guard('admin')->user()->admin_role == 1) {
            $data = Query::where('q_service', 0)->orderBy('created_at', 'DESC')->get();
            return view('admin.form_data.manage-queries', array('data' => $data));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function DeleteQuery($id) {
        $data = Query::where('q_id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_ms_query',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($data->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);
        $status = DB::table('abc_ms_query')->where('q_id', base64_decode($id))->delete();
        if ($status) {
            return redirect()->back()->with('message', 'Query successfully deleted');
        } else {
            return redirect()->back()->with('message', 'An error occurred while deleting the query');
        }
    }

    public function exportToExcelQuery() {
        $exporter = app()->makeWith(QueryExport::class);
        return $exporter->download(date('Y-m-d-H-i-s') . '-Customers_Query.xlsx');
    }

    public function Quotes() {
        if (Auth::guard('admin')->user()->admin_role == 1) {
            $data = Query::where('q_service', '!=', 0)->orderBy('created_at', 'DESC')->get();
            return view('admin.form_data.manage-quotes', array('data' => $data));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function DeleteQuote($id) {
        $data = Query::where('q_id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_ms_query',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($data->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);
        $status = DB::table('abc_ms_query')->where('post_id', base64_decode($id))->delete();
        if ($status) {
            return redirect()->back()->with('message', 'Quote successfully deleted');
        } else {
            return redirect()->back()->with('message', 'An error occurred while deleting the quote');
        }
    }

    public function exportToExcelQuote() {
        $exporter = app()->makeWith(QuoteExport::class);
        return $exporter->download(date('Y-m-d-H-i-s') . '-Customers_Quote.xlsx');
    }

}
