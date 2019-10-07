<?php

namespace App\Models;

use App\Utils\DateUtils;
use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{

    protected $table = 'notes';

    protected $fillable = [
        'id_sub_caller',
        'content'
    ];

    //Relations
    public function subCaller(){
        return $this->belongsTo(SubCaller::class, 'id_sub_caller', 'id');
    }

    //Access
    public function getCreatedAtAttribute($value){
        return DateUtils::convertDataToBR($value, true);
    }
}
