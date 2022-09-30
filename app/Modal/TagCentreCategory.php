<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TagCentreCategory extends Authenticatable
{
    use Notifiable;
    protected $table = 'abc_tag_centre_to_category';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'centre_id',  'cat_id','tstatus'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

}
