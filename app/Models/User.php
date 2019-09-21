<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //Relations

    public function openCallers(){
        return $this->hasMany(Called::class, 'id_user_open', 'id');
    }

    public function closeCallers(){
        return $this->hasMany(Called::class, 'id_user_close', 'id');
    }

    public function openSubCallers(){
        return $this->hasMany(SubCaller::class, 'id_user', 'id');
    }


    //Access e Mutators

    public function setPasswordAttribute($value){
        $this->attributes['password'] = bcrypt($value);
    }


}
