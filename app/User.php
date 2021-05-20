<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    public $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'plan_id', 'planStartDate', 'planEndDate', 'israeliId', 'isAdmin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function plan()
    {
        return $this->belongsTo('App\Plan');
    }

    public function IsAdmin()
    {
        if ($this->isAdmin == 1) {return true;}
        return false;
    }

    public function businesses()
    {
        return $this->hasMany('App\Business');
    }
}
