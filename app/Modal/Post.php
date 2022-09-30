<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Post extends Authenticatable {

    use HasApiTokens,
        Notifiable;

    protected $table = 'abc_ms_post';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_content', 'post_status', 'user_id', 'user_name', 'user_mobile', 'user_email', 'published_date', 'unpublished_date', 'aprroved_by', 'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
