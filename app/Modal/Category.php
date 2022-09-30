<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Category extends Authenticatable
{
    use Notifiable;
    protected $table = 'abc_ms_category';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'acat_name',  'acat_status','acat_intro','acat_type','flag_hour','flag_month','flag_year'
        ,'flag_halfday','flag_fullday','flag_quart','flag_halfyear','acat_img','acat_per_type','acat_addons','acat_detail',
		'business_address','high_internet','it_infra','parking_zone','twentyfour_access','event_activity'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    function MsInfoDetail(){
        return $this->hasMany('App\Modal\Msinfo', 'ms_cat', 'acat_id');
    }
    
    function MeetingroomDetail(){
        return $this->hasMany('App\Modal\Meetingroom', 'ms_cat', 'acat_id');
    }
}
