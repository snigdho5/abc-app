<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ReferClient extends Authenticatable
{
    use Notifiable;
    protected $table = 'abc_ms_refer_client';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rc_name',  'rc_email','rc_mobile','rc_refby'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

}
