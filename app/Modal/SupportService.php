<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SupportService extends Authenticatable {

    use HasApiTokens,
        Notifiable;

    protected $table = 'abc_ms_sprtserv';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ss_text', 'ss_img', 'ss_status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
