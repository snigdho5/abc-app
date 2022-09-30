<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Booking extends Authenticatable {

    use Notifiable;

    protected $table = 'abc_booking';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'booking_code', 'loc_id', 'loc_name', 'centre_id', 'centre_name', 'book_date_from', 'book_date_to', 'book_time_from', 'book_time_to', 'user_id', 'user_name', 'user_email', 'user_phone', 'user_address', 'book_charge_amnt', 'book_tax_amnt', 'tot_book_amnt', 'book_status', 'cust_ip', 'book_cancel_status', 'book_cancel_by', 'book_cancl_date', 'book_cancel_before_status', 'book_trans_status', 'book_trans_msg', 'book_trans_date', 'book_trans_mode', 'book_trans_bank_nm', 'pu_order_status','book_user_amnt','book_user_opt','status_remarks','package_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */


}
