<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class BookingComment extends Authenticatable {

    use Notifiable;

    protected $table = 'abc_booking_cmnt';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'book_id', 'payment_type', 'payment_amnt', 'admin_cmnt'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */


}
