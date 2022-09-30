<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable {

    use HasApiTokens,
        Notifiable;

    protected $table = 'abc_ms_cust';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cust_nme', 'cust_email', 'cust_code', 'cust_mobile', 'custloc', 'cust_service_add1', 'cust_service_add2', 'cust_landmark', 'cust_pin', 'cust_status', 'cust_ip', 'cust_src', 'cust_mdm', 'cust_lat', 'cust_lng', 'cust_pwd', 'api_token', 'cust_dob', 'cust_comp', 'cust_desig', 'centre_id', 'sc_skills', 'cust_img', 'sc_intro', 'sc_status', 'sc_reg_date', 'cust_last_login', 'sc_last_used','sc_appr_date','comp_gst','comp_status','comp_flag','cust_centre','blc'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
