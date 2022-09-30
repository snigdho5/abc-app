<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TagSupportService extends Authenticatable {

    use Notifiable;

    protected $table = 'abc_ms_tagsupport_centre';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ssid', 'centreid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */


}
