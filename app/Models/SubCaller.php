<?php

namespace App\Models;

use App\Utils\DateUtils;
use Illuminate\Database\Eloquent\Model;

class SubCaller extends Model
{

    protected $table = 'sub_caller';

    protected $fillable = [
        'id_caller',
        'status',
        'id_user',
        'sisman',
        'otrs',
        'call_telecommunications_company_number',
        'deadline',
        'hr_open_call_telecommunications_company'
    ];


    //Relations

    public function called()
    {
        return $this->belongsTo(Called::class, 'id_caller', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function actionTake()
    {
        return ActionTakeCalled::where(['id_caller' => $this->attributes['id']])->get();
    }

    public function typeProblem()
    {
        return TypeProblemCalled::where(['id_called' => $this->attributes['id']])->get();
    }

    public function notes()
    {
        return $this->hasMany(Notes::class, 'id_sub_caller', 'id');
    }

    // Access e Mutators

    public function setDeadlineAttribute($value)
    {
        $this->attributes['deadline'] = DateUtils::convertDataDataBase($value);
    }

    public function getDeadlineAttributte($value)
    {
        return DateUtils::convertDataToBR($value);
    }

    public function setHrOpenCallTelecommunicationsCompanyAttribute($value)
    {
        $this->attributes['hr_open_call_telecommunications_company'] = $value;
    }

    public function getHrOpenCallTelecommunicationsCompanyAttribute($value)
    {
        return DateUtils::convertDataToBR($value);
    }
}
