<?php

namespace App\Modal;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable {

    use Notifiable;

    protected $table = 'abc_admin';
    //protected $primaryKey = 'user_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_name', 'email', 'password', 'admin_role', 'code', 'mobile', 'loc','center_id','cat_id', 'cmt', 'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    public function servicesmap() {
        return $this->hasMany('App\Modal\TechToServiceMap', 'tech_id', 'id');
    }

}
