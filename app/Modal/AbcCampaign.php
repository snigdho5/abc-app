<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AbcCampaign extends Authenticatable
{
    use Notifiable;
    protected $table = 'abc_ms_camp_cron';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'camp_name',  'camp_count1','camp_count2','camp_img'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

}
