<?php

namespace App\Models;

use App\Utils\DateUtils;
use Illuminate\Database\Eloquent\Model;

class Called extends Model
{
    protected $fillable = [
        'caller_number',
        'id_establishment',
        'id_link',
        'status',
        'id_problem_cause',
        'next_action',
        'id_user_open',
        'id_user_close',
        'hr_down',
        'hr_up',
        'downtime',
        'work_downtime',
        'massive_call_id',
        'id_attachment'
    ];

    //Relations

    public function establishment(){
        return $this->belongsTo(Establishment::class, 'id_establishment', 'id');
    }

    public function subCallers(){
        return $this->hasMany(SubCaller::class, 'id_caller', 'id');
    }

    public function userOpen(){
        return $this->belongsTo(User::class, 'id_user_open', 'id');
    }

    public function userClose(){
        return $this->belongsTo(User::class, 'id_user_close', 'id');
    }

    //Access e Mutators

    public function setHrDownAttribute($value){
        $this->attributes['hr_down'] = DateUtils::convertDataDataBase($value);
    }

    public function getHrDownAttribute($value){
        return DateUtils::convertDataToBR($value);
    }




}
