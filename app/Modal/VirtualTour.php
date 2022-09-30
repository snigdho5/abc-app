<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class VirtualTour extends Authenticatable
{
    use Notifiable;
    protected $table = 'abc_ms_virtual_tour';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vt_centre_id',  'vt_title','vt_subtitle','vt_th_img','vt_embed_map','vt_status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

}
