<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use DB;
use App\Modal\Customer;

class NewAPIController extends Controller
{
    public function __construct()
    {
    }

    public function test()
    {
        return response()->json(['status' => 0, 'message' => 'Test!', 'respData' => []], 200);
    }



    public function sendRegOTP(Request $request)
    {
        // validation
        $validator = Validator::make($request->all(), [
            'otp_type' => 'required|in:email,phone',
            'email' => 'required_if:otp_type,email|email|unique:abc_ms_cust,cust_email',
            'phone' => ['required_if:otp_type,phone', 'size:10']
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => 'Validation failed!', 'respData' => $validator->errors()], 200);
        } else {
            $otp = 1234;
            // $otp = rand(1000, 9999);
            $user = Customer::where('cust_email', $request->email)
                ->orWhere('cust_mobile',  $request->phone)
                ->first();
            // echo 'ss';die;
            if ($user) {
                return response()->json(['status' => 0, 'message' => 'User already exists!', 'respData' => []], 200);
            }

            if ($request->otp_type == 'email') {
                $userEmail = DB::table('abc_otp_verification')->where('email', $request->email)->first();

                if (isset($userEmail) && $userEmail->id != '') {
                    //update
                    DB::table('abc_otp_verification')->where('id', $userEmail->id)->update(
                        ['email' => $request->email, 'email_otp' => $otp, 'dtime' => date('Y-m-d H:i:s')]
                    );
                } else {
                    //insert
                    DB::table('abc_otp_verification')->insert(
                        ['email' => $request->email, 'email_otp' => $otp, 'dtime' => date('Y-m-d H:i:s')]
                    );
                }

                $data = request()->all();
                $data['name'] = 'User';
                $data['otp'] = $otp;
                // Mail::to(request()->email, request()->name)->send(new EmailVerifyNew($data));
                return response()->json(['status' => 1, 'message' => 'OTP sent successfully!', 'respData' => ['otp' => $otp]], 200);
            } else if ($request->otp_type == 'phone') {
                $userPhone = DB::table('abc_otp_verification')->where('phone', $request->phone)->first();

                // $response = file_get_contents("http://5.189.187.82/sendsms/bulk.php?username=brandsum&password=12345678&type=TEXT&sender=QuestM&entityId=1701159179218527222&templateId=1707161737270600501&mobile=" . request()->phone . "&message=" . urlencode($otp . " is your OTP for phone number verification. Valid for 15 minutes. QUEST PROPERTIES INDIA LTD."));

                if (isset($userPhone) && $userPhone->id != '') {
                    //update
                    DB::table('abc_otp_verification')->where('id', $userPhone->id)->update(
                        ['phone' => $request->phone, 'phone_otp' => $otp]
                    );
                } else {
                    //insert
                    DB::table('abc_otp_verification')->insert(
                        ['phone' => $request->phone, 'phone_otp' => $otp]
                    );
                }

                return response()->json(['status' => 1, 'message' => 'OTP sent successfully!', 'respData' => ['otp' => $otp]], 200);
            } else {
                return response()->json(['status' => 0, 'message' => 'Wrong otp_type!', 'respData' => []], 200);
            }
        }
    }

    public function verifyRegOTP(Request $request)
    {
        // request()->merge(['name' => Crypt::decryptString(request()->name)]);
        // request()->merge(['email' => Crypt::decryptString(request()->email)]);
        // request()->merge(['password' => Crypt::decryptString(request()->password)]);

        // validation
        $validator = Validator::make($request->all(), [
            'otp_type' => 'required|in:email,phone',
            'otp' => 'required|numeric',
            'email' => 'required_if:otp_type,email|email|unique:abc_ms_cust,cust_email',
            'phone' => ['required_if:otp_type,phone', 'size:10']
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => 'Validation failed!', 'respData' => $validator->errors()], 200);
        } else {
            $user = Customer::where('cust_email', $request->email)
                ->orWhere('cust_mobile',  $request->phone)
                ->first();
            if ($user) {
                return response()->json(['status' => 0, 'message' => 'User already exists!', 'respData' => []], 200);
            }

            if ($request->otp_type == 'email') {
                $userEmail = DB::table('abc_otp_verification')->where('email', $request->email)->first();

                if (isset($userEmail) && $userEmail->id != '') {
                    // update
                    if ($request->otp == $userEmail->email_otp) {
                        //update

                        DB::table('abc_otp_verification')->where('id', $userEmail->id)->update(
                            ['email_verified' => 1]
                        );

                        $userEmail = DB::table('abc_otp_verification')->where('email', $request->email)->first();

                        return response()->json(['status' => 1, 'message' => 'OTP verified successfully!', 'respData' => $userEmail], 200);
                    } else {
                        return response()->json(['status' => 0, 'message' => 'OTP not verified!', 'respData' => []], 200);
                    }
                } else {
                    return response()->json(['status' => 0, 'message' => 'OTP not send!', 'respData' => []], 200);
                }
            } else if ($request->otp_type == 'phone') {
                $userPhone = DB::table('abc_otp_verification')->where('phone', $request->phone)->first();

                if (isset($userPhone) && $userPhone->id != '') {
                    // update
                    if ($request->otp == $userPhone->phone_otp) {
                        //
                        DB::table('abc_otp_verification')->where('id', $userPhone->id)->update(
                            ['phone_verified' => 1]
                        );

                        $userPhone = DB::table('abc_otp_verification')->where('phone', $request->phone)->first();

                        return response()->json(['status' => 1, 'message' => 'OTP verified successfully!', 'respData' => $userPhone], 200);
                    } else {
                        return response()->json(['status' => 0, 'message' => 'OTP not verified!', 'respData' => []], 200);
                    }
                } else {
                    return response()->json(['status' => 0, 'message' => 'OTP not send!', 'respData' => []], 200);
                }
            } else {
                return response()->json(['status' => 0, 'message' => 'Wrong otp_type!', 'respData' => []], 200);
            }
        }
    }

    
    public function userProfile(Request $request)
    {
        // request()->merge(['token' => Crypt::decryptString(request()->token)]);
        // request()->merge(['id' => Crypt::decryptString(request()->id)]);

        // validation
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            echo json_encode(array("error" => "Invalid token."));
        } else {
            // $user = Customer::where('cust_id', request()->id)->first();
            $user = Customer::where([['api_token', request()->token], ['cust_id', request()->id]])->first();

            if ($user) {
                return response()->json(['status' => 1, 'message' => 'Details found', 'respData' => $user], 200);
            } else {
                return response()->json(['status' => 0, 'message' => 'Invalid token. Details not found', 'respData' => []], 200);
            }
        }
    }

    public function registerUser(Request $request)
    {
        // request()->merge(['name' => Crypt::decryptString(request()->name)]);
        // request()->merge(['email' => Crypt::decryptString(request()->email)]);
        // request()->merge(['password' => Crypt::decryptString(request()->password)]);

        // validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:abc_ms_cust,cust_email',
            'phone' => ['required', 'size:10']
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => 'Validation failed!', 'respData' => $validator->errors()], 200);
        } else {
            $userPhone = DB::table('abc_otp_verification')->where('phone', $request->phone)->first();
            $userEmail = DB::table('abc_otp_verification')->where('email', $request->email)->first();

            if (isset($userPhone) && $userPhone->phone_verified == 1) {
                $user_phone = 1;
            } else {
                $user_phone = 0;
            }

            if (isset($userEmail) && $userEmail->email_verified == 1) {
                $user_email = 1;
            } else {
                $user_email = 0;
            }

            if ($user_phone == 0 || $user_email == 0) {
                return response()->json(['status' => 0, 'message' => 'Email / Phone not verified!', 'respData' => []], 200);
            }

            // request()->merge(['password' => bcrypt(request('password'))]);
            // request()->merge(['chash' => bin2hex(openssl_random_pseudo_bytes(128))]);
            // request()->merge(['active' => '0']);
            // request()->merge(['api_token' => bin2hex(openssl_random_pseudo_bytes(128))]);

            $newCust = [
                'cust_nme' => $request->name,
                'api_token' => bin2hex(openssl_random_pseudo_bytes(128)),
                'cust_email' => $request->email,
                'cust_mobile' => $request->phone,
                // 'cust_status' => 0,
                'otp_status' => 1

            ];
            $user = Customer::create($newCust);

            // $data = request()->all();
            // Mail::to(request()->email, request()->name)->send(new EmailVerify($data));

            $userData = Customer::where('cust_email', $request->email)
                ->orWhere('cust_id',  $user->cust_id)
                ->first();
            $userData->image_url = ($user->cust_img != '') ? url('storage/users/' . $user->cust_img) : '';

            return response()->json(['status' => 1, 'message' => 'Successfully registered!', 'respData' => $userData], 200);
        }
    }


    public function sendProfileOTP(Request $request)
    {
        // request()->merge(['name' => Crypt::decryptString(request()->name)]);
        // request()->merge(['email' => Crypt::decryptString(request()->email)]);
        // request()->merge(['password' => Crypt::decryptString(request()->password)]);

        // validation
        $validator = Validator::make($request->all(), [
            'otp_type' => 'required|in:email,phone',
            'email' => 'required_if:otp_type,email|email|unique:user,email',
            'phone' => ['required_if:otp_type,phone', 'size:10']
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => 'Validation failed!', 'respData' => $validator->errors()], 200);
        } else {
            $otp = 1234;
            // $otp = rand(1000, 9999);
            $user = Customer::where('cust_email', $request->email)
                ->orWhere('cust_mobile',  $request->phone)
                ->first();
            if ($user) {
                return response()->json(['status' => 0, 'message' => 'User already exists!', 'respData' => []], 200);
            }

            if ($request->otp_type == 'email') {
                $userEmail = DB::table('abc_potp_verification')->where('email', $request->email)->first();

                if (isset($userEmail) && $userEmail->id != '') {
                    //update
                    DB::table('abc_potp_verification')->where('id', $userEmail->id)->update(
                        ['email' => $request->email, 'email_otp' => $otp, 'dtime' => date('Y-m-d H:i:s')]
                    );
                } else {
                    //insert
                    DB::table('abc_potp_verification')->insert(
                        ['email' => $request->email, 'email_otp' => $otp, 'dtime' => date('Y-m-d H:i:s')]
                    );
                }

                $data = request()->all();
                $data['name'] = 'User';
                $data['otp'] = $otp;
                // Mail::to(request()->email, request()->name)->send(new EmailVerifyNew($data));
                return response()->json(['status' => 1, 'message' => 'OTP sent successfully!', 'respData' => ['otp' => $otp]], 200);
            } else if ($request->otp_type == 'phone') {
                $userPhone = DB::table('abc_potp_verification')->where('phone', $request->phone)->first();

                // $response = file_get_contents("http://5.189.187.82/sendsms/bulk.php?username=brandsum&password=12345678&type=TEXT&sender=QuestM&entityId=1701159179218527222&templateId=1707161737270600501&mobile=" . request()->phone . "&message=" . urlencode($otp . " is your OTP for phone number verification. Valid for 15 minutes. QUEST PROPERTIES INDIA LTD."));

                if (isset($userPhone) && $userPhone->id != '') {
                    //update
                    DB::table('abc_potp_verification')->where('id', $userPhone->id)->update(
                        ['phone' => $request->phone, 'phone_otp' => $otp]
                    );
                } else {
                    //insert
                    DB::table('abc_potp_verification')->insert(
                        ['phone' => $request->phone, 'phone_otp' => $otp]
                    );
                }

                return response()->json(['status' => 1, 'message' => 'OTP sent successfully!', 'respData' => ['otp' => $otp]], 200);
            } else {
                return response()->json(['status' => 0, 'message' => 'Wrong otp_type!', 'respData' => []], 200);
            }
        }
    }

    public function verifyProfileOTP(Request $request)
    {
        // request()->merge(['name' => Crypt::decryptString(request()->name)]);
        // request()->merge(['email' => Crypt::decryptString(request()->email)]);
        // request()->merge(['password' => Crypt::decryptString(request()->password)]);

        // validation
        $validator = Validator::make($request->all(), [
            'otp_type' => 'required|in:email,phone',
            'otp' => 'required|numeric',
            'email' => 'required_if:otp_type,email|email|unique:user,email',
            'phone' => ['required_if:otp_type,phone', 'size:10']
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => 'Validation failed!', 'respData' => $validator->errors()], 200);
        } else {
            $user = Customer::where('cust_email', $request->email)
                ->orWhere('cust_mobile',  $request->phone)
                ->first();
            if ($user) {
                return response()->json(['status' => 0, 'message' => 'User does not exists!', 'respData' => []], 200);
            }

            if ($request->otp_type == 'email') {
                $userEmail = DB::table('abc_potp_verification')->where('email', $request->email)->first();

                if (isset($userEmail) && $userEmail->id != '') {
                    // update
                    if ($request->otp == $userEmail->email_otp) {
                        //update

                        DB::table('abc_potp_verification')->where('id', $userEmail->id)->update(
                            ['email_verified' => 1]
                        );

                        $userEmail = DB::table('abc_potp_verification')->where('email', $request->email)->first();

                        return response()->json(['status' => 1, 'message' => 'OTP verified successfully!', 'respData' => $userEmail], 200);
                    } else {
                        return response()->json(['status' => 0, 'message' => 'OTP not verified!', 'respData' => []], 200);
                    }
                } else {
                    return response()->json(['status' => 0, 'message' => 'OTP not send!', 'respData' => []], 200);
                }
            } else if ($request->otp_type == 'phone') {
                $userPhone = DB::table('abc_potp_verification')->where('phone', $request->phone)->first();

                if (isset($userPhone) && $userPhone->id != '') {
                    // update
                    if ($request->otp == $userPhone->phone_otp) {
                        //
                        DB::table('abc_potp_verification')->where('id', $userPhone->id)->update(
                            ['phone_verified' => 1]
                        );

                        $userPhone = DB::table('abc_potp_verification')->where('phone', $request->phone)->first();

                        return response()->json(['status' => 1, 'message' => 'OTP verified successfully!', 'respData' => $userPhone], 200);
                    } else {
                        return response()->json(['status' => 0, 'message' => 'OTP not verified!', 'respData' => []], 200);
                    }
                } else {
                    return response()->json(['status' => 0, 'message' => 'OTP not send!', 'respData' => []], 200);
                }
            } else {
                return response()->json(['status' => 0, 'message' => 'Wrong otp_type!', 'respData' => []], 200);
            }
        }
    }

    public function updateUserProfile(Request $request)
    {
        // validation
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'token' => 'required',
            'name' => 'required',
            'phone' => ['required', 'size:10']
        ]);

        if ($validator->fails()) {
            return response(json_encode($validator->errors()), 400);
        } else {
            $user = Customer::where([['cust_id', request()->id], ['api_token', request()->token]]);
            if ($user->count()) {
                $user = $user->first();
                $user->cust_mobile = request()->phone;
                $user->cust_nme = request()->name;
                if (isset(request()->email)) {
                    $user->cust_email = request()->email;
                    $changed_data = [
                        'cust_mobile' => request()->phone,
                        'cust_email' => request()->email,
                        'cust_nme' => request()->login_device,
                    ];
                }else{
                    $changed_data = [
                        'cust_mobile' => request()->phone,
                        'cust_nme' => request()->login_device,
                    ];
                }
                // $user->save();

                Customer::where('cust_id', $user->cust_id)->update($changed_data);

                return response()->json(['status' => 1, 'message' => 'success', 'respData' => $user], 200);
            } else {
                return response()->json(['status' => 0, 'message' => 'User not found', 'respData' => []], 200);
            }
        }
    }


    public function sendLoginOTP(Request $request)
    {
        // request()->merge(['name' => Crypt::decryptString(request()->name)]);
        // request()->merge(['email' => Crypt::decryptString(request()->email)]);
        // request()->merge(['password' => Crypt::decryptString(request()->password)]);

        // validation
        $validator = Validator::make($request->all(), [
            'otp_type' => 'required|in:email,phone',
            // 'email' => 'required_if:otp_type,email|email|unique:user,email',
            'phone' => ['required_if:otp_type,phone', 'size:10']
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => 'Validation failed!', 'respData' => $validator->errors()], 200);
        } else {
            $otp = 1234;
            // $otp = rand(1000, 9999);
            $user = Customer::where('cust_email', $request->email)
                ->orWhere('cust_mobile',  $request->phone)
                ->first();
            if (!$user) {
                return response()->json(['status' => 0, 'message' => 'User not found!', 'respData' => []], 200);
            }

            if ($request->otp_type == 'email') {
                // $userEmail = DB::table('abc_otp_verification')->where('email', $request->email)->first();

                // if (isset($userEmail) && $userEmail->id != '') {
                //update
                // DB::table('abc_otp_verification')->where('id', $userEmail->id)->update(
                //     ['email' => $request->email, 'email_otp' => $otp, 'dtime' => date('Y-m-d H:i:s')]
                // );
                // } else {
                //insert
                //     DB::table('abc_otp_verification')->insert(
                //         ['email' => $request->email, 'email_otp' => $otp, 'dtime' => date('Y-m-d H:i:s')]
                //     );
                // }

                // $data = request()->all();
                // Mail::to(request()->email, request()->name)->send(new EmailVerify($data));
                // return response()->json(['status' => 1, 'message' => 'OTP sent successfully!', 'respData' => ['otp' => $otp]], 200);
            } else if ($request->otp_type == 'phone') {
                //update
                // $user->cust_otp = $otp;
                // $user->otp_status = 0;
                // $user->save();

                $changed_data = [
                    'cust_otp' => $otp,
                    'otp_status' => 0
                ];

                Customer::where('cust_id', $user->cust_id)->update($changed_data);

                // $response = file_get_contents("http://5.189.187.82/sendsms/bulk.php?username=brandsum&password=12345678&type=TEXT&sender=QuestM&entityId=1701159179218527222&templateId=1707161737270600501&mobile=" . request()->phone . "&message=" . urlencode($otp . " is your OTP for phone number verification. Valid for 15 minutes. QUEST PROPERTIES INDIA LTD."));

                return response()->json(['status' => 1, 'message' => 'OTP sent successfully!', 'respData' => ['otp' => $otp]], 200);
            } else {
                return response()->json(['status' => 0, 'message' => 'Wrong otp_type!', 'respData' => []], 200);
            }
        }
    }


    public function verifyLoginOTP(Request $request)
    {
        // request()->merge(['name' => Crypt::decryptString(request()->name)]);
        // request()->merge(['email' => Crypt::decryptString(request()->email)]);
        // request()->merge(['password' => Crypt::decryptString(request()->password)]);

        // validation
        $validator = Validator::make($request->all(), [
            'otp_type' => 'required|in:email,phone',
            'otp' => 'required|size:4',
            'email' => 'required_if:otp_type,email|email|unique:user,email',
            'phone' => ['required_if:otp_type,phone', 'size:10'],
            'login_device' => 'required',
            'platform' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => 'Validation failed!', 'respData' => $validator->errors()], 200);
        } else {
            $user = Customer::where('cust_email', $request->email)
                ->orWhere('cust_mobile',  $request->phone)
                ->first();
            if (!$user) {
                return response()->json(['status' => 0, 'message' => 'User not found!', 'respData' => []], 200);
            }

            if ($request->otp_type == 'email') {
                // $userEmail = DB::table('abc_otp_verification')->where('email', $request->email)->first();

                // if (isset($userEmail) && $userEmail->id != '') {
                //update
                // DB::table('abc_otp_verification')->where('id', $userEmail->id)->update(
                //     ['email' => $request->email, 'email_otp' => $otp, 'dtime' => date('Y-m-d H:i:s')]
                // );
                // } else {
                //insert
                //     DB::table('abc_otp_verification')->insert(
                //         ['email' => $request->email, 'email_otp' => $otp, 'dtime' => date('Y-m-d H:i:s')]
                //     );
                // }

                // $data = request()->all();
                // Mail::to(request()->email, request()->name)->send(new EmailVerify($data));
                // return response()->json(['status' => 1, 'message' => 'OTP sent successfully!', 'respData' => ['otp' => $otp]], 200);
            } else if ($request->otp_type == 'phone') {

                if ($request->otp == $user->cust_otp) {
                    //update
                    $user->otp_status = 1;
                    $newToken = bin2hex(openssl_random_pseudo_bytes(128));
                    $user->api_token = $newToken;
                    $user->login_device = request()->login_device;
                    $user->platform = request()->platform;

                    // if ($user->is_first_login == 0) {
                    //     $user->is_first_login = 1;
                    // }

                    // $user->save();

                    $changed_data = [
                        'api_token' => $newToken,
                        'login_device' => request()->login_device,
                        'platform' => request()->platform,
                        'otp_status' => 1,
                        'cust_last_login' => date('Y-m-d H:i:s'),
                    ];

                    Customer::where('cust_id', $user->cust_id)->update($changed_data);

                    $user->image_url = ($user->image != '') ? url('storage/users/' . $user->cust_img) : '';

                    return response()->json(['status' => 1, 'message' => 'OTP verified successfully!', 'respData' => $user], 200);
                } else {
                    return response()->json(['status' => 0, 'message' => 'OTP not verified!', 'respData' => []], 200);
                }
            } else {
                return response()->json(['status' => 0, 'message' => 'Wrong otp_type!', 'respData' => []], 200);
            }
        }
    }


    public function logoutUser(Request $request)
    {
        // request()->merge(['token' => Crypt::decryptString(request()->token)]);
        // request()->merge(['id' => Crypt::decryptString(request()->id)]);

        // validation
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(json_encode($validator->errors()), 400);
        } else {
            $user = Customer::where([['api_token', request()->token], ['cust_id', request()->id]]);
            if ($user->count()) {
                $user = $user->first();
                $user->api_token = "";
                $user->last_login = date('Y-m-d H:i:s');
                // $user->save();

                $changed_data = [
                    'api_token' => "",
                    'last_logout' => date('Y-m-d H:i:s'),
                ];

                Customer::where('cust_id', $user->cust_id)->update($changed_data);

                if ($user) {
                    return response()->json(['status' => 1, 'message' => 'Logged out', 'respData' => $user], 200);
                } else {
                    return response()->json(['status' => 0, 'message' => 'User not found', 'respData' => []], 200);
                }
            } else {
                return response()->json(['status' => 0, 'message' => 'User not found', 'respData' => []], 200);
            }
        }
    }



    public function addDeviceFCM(Request $request)
    {

        // validation
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required',
            'device_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(json_encode($validator->errors()), 400);
        } else {
            $getPush = DB::table('push_device_fcms')->where('device_id', request()->device_id)->first();

            if (!$getPush) {
                DB::table('push_device_fcms')->insert(
                    [
                        'fcm_token' => $request->fcm_token,
                        'device_id' => $request->device_id,
                        'created_at' => date('Y-m-d H:i:s')
                    ]
                );

                // if ($user) {
                return response()->json(['status' => 1, 'message' => 'Device ID Added', 'respData' => []], 200);
                // } else {
                // return response()->json(['status' => 0, 'message' => 'User not found', 'respData' => []], 200);
                // }
            } else {
                DB::table('push_device_fcms')->where('id', $getPush->id)->update(
                    [
                        'fcm_token' => $request->fcm_token,
                        // 'device_id' => $request->device_id,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]
                );
                return response()->json(['status' => 0, 'message' => 'Fcm updated!', 'respData' => []], 200);
            }
        }
    }


    public function deleteUser(Request $request)
    {
        // request()->merge(['token' => Crypt::decryptString(request()->token)]);
        // request()->merge(['id' => Crypt::decryptString(request()->id)]);

        // validation
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(json_encode($validator->errors()), 400);
        } else {
            $user = Customer::where([['api_token', request()->token], ['cust_id', request()->id]]);
            if ($user->count()) {
                $user = $user->first();

                if ($user) {
                    Customer::where('cust_id',$user->cust_id)->delete();
                    // $user->delete();
                    // $user->deleted = 1;
                    // $user->save();S
                    return response()->json(['status' => 1, 'message' => 'Deleted!', 'respData' => []], 200);
                } else {
                    return response()->json(['status' => 0, 'message' => 'User not found', 'respData' => []], 200);
                }
            } else {
                return response()->json(['status' => 0, 'message' => 'User not found', 'respData' => []], 200);
            }
        }
    }
}
