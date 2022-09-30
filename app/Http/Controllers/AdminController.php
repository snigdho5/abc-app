<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Modal\Admin;

class AdminController extends Controller {

    public function __construct() {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('admin.dashboard');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('admin.auth.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        // validate the data
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);

        // store in the database
        $admins = new Admin;
        $admins->name = $request->name;
        $admins->email = $request->email;
        $admins->password = bcrypt($request->password);

        $admins->save();


        return redirect()->route('admin.auth.login');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

    public function ChangePassword(Request $request) {
        if ($request->submit == 1) {
            $validation = Validator::make($request->all(), [
                        'old_password' => 'required|min:6',
                        'new_password' => 'required|min:6',
                        'cnf_password' => 'required|min:6',
                            //'confirm_password'  => 'required|min:6',          
            ]);
            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput($request->only('old_password'));
            }
            $user = Admin::Where('id', Auth::guard('admin')->user()->id)->first();
            if ($request->new_password == $request->cnf_password) {
                if (Hash::check($request->old_password, $user->password)) {

                    $hashed = Hash::make($request->new_password);
                    $user->password = $hashed;
                    $user->save();
                    return redirect()->back()->with('message', 'Password changed successfully.');
                } else {
                    return redirect()->back()->withErrors(['Your current password is wrong.'])->withInput($request->only('old_password'));
                }
            } else {
                return redirect()->back()->withErrors(['Your confirm password is wrong.'])->withInput($request->only('old_password'));
            }
        }
        return view('admin.change-password');
    }

}
