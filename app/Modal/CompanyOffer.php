<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CompanyOffer extends Authenticatable {

    use Notifiable;

    protected $table = 'abc_client_offer';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'co_compid', 'co_catid', 'co_configid', 'co_allctedhrs','co_allctedmnthhrs',
		'co_cntrctstrtdte', 'co_cntrctenddte','co_status','co_consumedhrs','co_offerdays','co_ofrtimefrom','co_ofrtimeto'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */


}
