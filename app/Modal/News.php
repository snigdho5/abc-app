<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class News extends Authenticatable {

    use Notifiable;

    protected $table = 'abc_ms_news';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'n_title','n_heading', 'n_url', 'n_img', 'n_featured', 'n_centre_id', 'n_status','n_ordering'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */


}
