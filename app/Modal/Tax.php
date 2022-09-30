<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Tax extends Authenticatable
{
    use Notifiable;
    protected $table = 'abc_ms_tax';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tax_cgst_rate',  'tax_cgst_amt','tax_sgst_rate','tax_sgst_amt','tax_status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

}
