<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class BookingDetail extends Authenticatable {

    use Notifiable;

    protected $table = 'abc_booking_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'booking_id', 'service_id', 'service_name', 'is_add_service', 'book_date_from', 'book_date_to', 'book_time_from', 'book_time_to', 'service_amnt', 'service_tax_amnt', 'service_tot_amnt','book_hrs'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */


}
