<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PQuote extends Authenticatable
{
    use Notifiable;
    protected $table = 'abc_ms_partnership_opper';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'po_name',  'po_email','po_phone','po_loc','po_text','po_cust_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

}
