<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class RoomBooking extends Authenticatable {

    use Notifiable;

    protected $table = 'abc_room_booking';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id', 'center_id', 'ms_id', 'cat_id', 'ms_type', 'booking_date', 'st_time', 'end_time', 'booking_hour', 'rb_hour', 'rb_half', 'rb_full', 'tot_val'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
