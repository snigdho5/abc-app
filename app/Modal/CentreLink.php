<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CentreLink extends Authenticatable {

    use Notifiable;

    protected $table = 'abc_ms_centrelink';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mobile', 'link','centre_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
