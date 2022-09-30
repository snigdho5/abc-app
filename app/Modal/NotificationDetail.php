<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class NotificationDetail extends Authenticatable
{
    use Notifiable;
    protected $table = 'abc_ms_noti_detail';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'cust_id', 'cust_nm',  'cust_mob','noti_type1','noti_type2','camp_id','u_platform'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

}
