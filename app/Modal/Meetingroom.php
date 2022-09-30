<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Meetingroom extends Authenticatable {

    use Notifiable;

    protected $table = 'abc_room_rate';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'center_id', 'ms_id', 'rr_no', 'ms_name', 'ms_cat', 'ms_type', 'ms_hour', 'ms_half', 'ms_full', 'ms_month', 'ms_status'
        , 'SF_MtgRoom', 'activation_fee', 'security_deposit', 'ms_thour', 'ms_year', 'nw_month', 'fs_month', 'ms_pln_quart','ms_pln_hy','ms_pln_yr'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
