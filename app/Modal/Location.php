<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Location extends Authenticatable
{
    use Notifiable;
    protected $table = 'abc_ms_location';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'loc_name',  'loc_access','norder','fil_nm','loc_img'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

}
