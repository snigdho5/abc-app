<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CompanyClient extends Authenticatable
{
    use Notifiable;
    protected $table = 'abc_client_comp';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cc_name',  'cc_status'
    ];

}
