<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;
use Mail;
use App\Modal\Offer;
use App\Modal\Centre;
use App\Modal\Category;
use Intervention\Image\ImageManagerStatic as Image;

class OfferController extends Controller {
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
    public function AddNewOffer(Request $request) {
        //dd($request->all());
        $validation = Validator::make($request->all(), [
                    'offer_text' => 'required',
                    'offer_status' => 'required',
                    'offer_url' => 'required',
                    'offer_banner' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('offer_text', 'offer_status', 'offer_banner'));
        }
        $status = Offer::Where('offer_text', $request->offer_text)
                ->first();

        if ($status) {
            return redirect()->back()->withErrors(['message', 'This Offer already exists']);
        } else {
            $name = '';
            if ($request->hasFile('offer_banner')) {
                $image = $request->file('offer_banner');
                $name = time()  . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/upload/offer/');
                $valImage = validateImage($image->getClientOriginalExtension());
                if ($valImage) {
					if (Image::make($image->getRealPath())->width() == 633 && Image::make($image->getRealPath())->height() == 256) {
						$image->move($destinationPath, $name);
					}else{
						return redirect()->back()->withErrors(['message', 'Uploaded file width and height should be  633 X 256 .']);
					}
                } else {
                    return redirect()->back()->withErrors(['message', 'Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed.']);
                }
            }

            $flag_ofr_url = 0;

            if (isset($request->flag_internal_url) && $request->flag_internal_url == 'on') {
                $flag_ofr_url = 1;
            }

            $dataToSave = array('offer_text' => $request->offer_text,
                'offer_banner' => $name == '' ? '' : $name,
                'offer_url' => $request->offer_url,
                'offer_centre_id' => $request->offer_centre_id,
                'offer_category_id' => $request->offer_category_id,
                'offer_status' => $request->offer_status
            );
            $id = Offer::create($dataToSave)->id;


            $logData = array('subject_id' => $id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_centre',
                'description' => 'insert', 'data_prev' => '', 'data_now' => urldecode(http_build_query($dataToSave))
            );
            saveQueryLog($logData);
            if ($id) {
                return redirect()->back()->with('message', 'Offer successfully created');
            } else {
                return redirect()->back()->with('message', 'An error occurred while creating the offer');
            }
        }
    }

    /*

     * View customers 
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function Offers(Request $request) {

        if (Auth::guard('admin')->user()->admin_role == 1) {
            $centreData = Centre::orderBy('centre', 'ASC')->get();
            $categoryData = Category::where('acat_type', 'Service')->orderBy('acat_name', 'ASC')->get();
            $query = Offer::query();
            if (!empty($request->search)) {
                $search = $request->search;

                $query = $query->where(function($query) use ($search) {
                    $query->orWhere('offer_text', 'LIKE', '%' . $search . '%');
                });
            }
            $pageData = $query->orderby('created_at', 'DESC')
                    ->paginate(10)
                    ->withPath('?search=' . $request->search);
            return view('admin.offer.manage-offer', array('data' => $pageData, 'centreData' => $centreData, 'categoryData' => $categoryData));
        } else {
            return redirect('admin/dashboard')->with('message', 'Not Allowed');
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function EditOffer(Request $request) {
//        dd($request->all());
        $validation = Validator::make($request->all(), [
                    'offer_text' => 'required',
                    'offer_status' => 'required',
                    'offer_url' => 'required',
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->only('offer_text', 'offer_status', 'offer_banner'));
        }


        $status = Offer::Where('offer_text', $request->offer_text)
                ->where('offer_id', '!=', $request->id)
                ->first();


        if ($status) {
            return redirect()->back()->withErrors(['message', 'This offer already exists']);
        } else {
            $data = Offer::Where('offer_id', $request->id)->first();
            $name = '';
            if ($request->hasFile('offer_banner')) {
                $image = $request->file('offer_banner');
                $name = time() .  '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/upload/offer/');
                $valImage = validateImage($image->getClientOriginalExtension());
                if ($valImage) {
					if (Image::make($image->getRealPath())->width() == 633 && Image::make($image->getRealPath())->height() == 256) {
						$image->move($destinationPath, $name);
					}else{
						return redirect()->back()->withErrors(['message', 'Uploaded file width and height should be  633 X 256 .']);
					}
                } else {
                    return redirect()->back()->withErrors(['message', 'Uploaded file is not a valid image. Only JPG, PNG and GIF files are allowed.']);
                }
            }

            $flag_ofr_url = 0;

            if (isset($request->flag_internal_url) && $request->flag_internal_url == 'on') {
                $flag_ofr_url = 1;
            }


            $changed_data = array('offer_text' => $request->offer_text,
                'offer_banner' => $name == '' ? $data['offer_banner'] : $name,
                'offer_url' => $request->offer_url,
                'offer_centre_id' => $request->offer_centre_id,
                'offer_category_id' => $request->offer_category_id,
                'offer_status' => $request->offer_status
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
            $logData = array('subject_id' => $request->id, 'user_id' => Auth::id(), 'table_used' => 'abc_ms_offer',
                'description' => 'update', 'data_prev' => urldecode(http_build_query($diff_in_data_to_save)), 'data_now' => urldecode(http_build_query($changed_data))
            );
            saveQueryLog($logData);
            $status = Offer::Where('offer_id', $request->id)->update($changed_data);
            if ($status) {
                return redirect()->back()->with('message', 'Offer successfully updated');
            } else {
                return redirect()->back()->with('message', 'An error occurred while updating the offer');
            }
        }
    }

    /*

     * Edit customers
     * Sanjit Bhardwaj
     * 10-01-2018
     */

    public function DeleteOffer($id) {
        $users = Offer::where('offer_id', '=', base64_decode($id))->first();

        $logData = array('subject_id' => base64_decode($id), 'user_id' => Auth::id(), 'table_used' => 'abc_ms_offer',
            'description' => 'delete', 'data_prev' => urldecode(http_build_query($users->toArray())), 'data_now' => ''
        );

        saveQueryLog($logData);

        $status = DB::table('abc_ms_offer')->where('offer_id', base64_decode($id))->delete();
        if ($status) {
            return redirect()->back()->with('message', 'Offer successfully deleted');
        } else {
            return redirect()->back()->with('message', 'Error while deleting offer');
        }
    }

    public function GetOfferData(Request $request) {
        $data = Offer::Where('offer_id', base64_decode($request->id))->first();
        echo json_encode($data);
    }

}
