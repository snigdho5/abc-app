<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class IntroData extends Authenticatable
{
    use Notifiable;
    protected $table = 'abc_ms_intro';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'intro_text',  'intro_image','intro_url','intro_status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

}
