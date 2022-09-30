<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Offer extends Authenticatable
{
    use Notifiable;
    protected $table = 'abc_ms_offer';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'offer_text',  'offer_banner','offer_url_flg','offer_url','offer_centre_id','offer_service_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

}
