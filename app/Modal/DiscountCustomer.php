<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class DiscountCustomer extends Authenticatable {

    use Notifiable;

    protected $table = 'abc_ms_discount_cust';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dc_custid', 'dc_did', 'dc_max_consumed','dc_custmob'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
