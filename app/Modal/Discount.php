<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Discount extends Authenticatable {

    use Notifiable;

    protected $table = 'abc_ms_discount';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'd_code', 'd_date_start', 'd_date_end','d_cat', 'd_amnt', 'd_status', 'd_min_ordr_amnt', 'd_max_consumed','d_max_ofr_amnt','d_cust_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
