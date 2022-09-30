<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Event extends Authenticatable
{
    use Notifiable;
    protected $table = 'abc_ms_event';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'e_name',  'e_from','e_to','e_detail','e_img','e_gallery','e_status','e_centre_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
