<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ClientBenefits extends Authenticatable {

    use Notifiable;

    protected $table = 'abc_ms_client_benefits';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cb_name','cb_detail','cb_image','cb_status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */


}
