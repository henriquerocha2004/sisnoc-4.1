<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = 'config';
    protected $fillable = ['ad_integration', 'path_web_terminal', 'send_email'];


    //Mutator
    public function setAdIntegrationAttribute($val){
        $this->attributes['ad_integration'] = $val == 'on' ? 1 : 0;
    }

    public function setSendEmailAttribute($val){
        $this->attributes['send_email'] = $val == 'on' ? 1 : 0;
    }

}
