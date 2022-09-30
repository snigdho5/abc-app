<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Query extends Authenticatable
{
    use Notifiable;
    protected $table = 'abc_ms_query';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'q_name',  'q_email','q_phone','q_loc','q_text','q_cust_id','q_service','q_centre'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

}
