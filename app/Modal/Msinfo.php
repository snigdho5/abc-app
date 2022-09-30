<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Msinfo extends Authenticatable
{
    use Notifiable;
    protected $table = 'abc_ms_info';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ms_name', 'ms_cat','ms_type','ms_hour','ms_half','ms_full','ms_month','ms_status','ms_year','ms_quart','ms_hy'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

   function GetMeetingConfig(){
        return $this->hasMany('App\Modal\Meetingroom', 'ms_id', 'ms_id');
    }
}
