<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Centre extends Authenticatable {

    use Notifiable;

    protected $table = 'abc_ms_centre';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'centre', 'location', 'centre_address', 'centre_email', 'centre_mobile', 'centre_phone', 'centre_image', 'centre_content', 'mr_cfgn', 'facility_detail', 'centre_url', 'status', 'cstatus', 'centre_code', 'centre_vtlink', 'centre_gallery', 'centre_lat', 'centre_long', 'flag_abc_lounge', 'flag_built_to_suit', 'flag_virtual_office', 'flag_ser_office', 'flag_co_working', 'flag_meeting_room','flag_payment'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    function RateDetail() {
        return $this->hasMany('App\Modal\Meetingroom', 'center_id', 'centre_id');
    }

}
