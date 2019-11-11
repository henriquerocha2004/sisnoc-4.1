<?php

namespace App\Models;

use App\Utils\DateUtils;
use Illuminate\Database\Eloquent\Model;

class NotesEstablishment extends Model
{
    protected $table = 'notes_establishment';

    //relation
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    //access
    public function setValidateAttribute($value){
        $this->attributes['validate'] = DateUtils::convertDataDataBase($value);
    }
}
