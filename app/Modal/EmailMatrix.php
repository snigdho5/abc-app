<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class EmailMatrix extends Authenticatable {

    use Notifiable;

    protected $table = 'abc_ms_emailmatrix';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'centre_id', 'em_per', 'em_email', 'em_phone','updated_at','em_status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */


}
