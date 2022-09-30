<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SendLink extends Authenticatable {

    use Notifiable;

    protected $table = 'abc_ms_applink';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mobile'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
