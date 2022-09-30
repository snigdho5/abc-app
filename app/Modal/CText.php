<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CText extends Authenticatable
{
    use Notifiable;
    protected $table = 'abc_ms_ctext';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ctext_inf',  'ctext_status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

}
