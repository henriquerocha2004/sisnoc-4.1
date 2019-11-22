<?php

namespace App\Models;

use App\Utils\TextUtil;
use Illuminate\Database\Eloquent\Model;

class RegionalManager extends Model
{

    protected $table = 'regional_manager';

    protected $fillable = [
        'name',
        'email',
        'contact',
        'status'
    ];

    //Relation

    public function establishments()
    {
        return $this->hasMany(Establishment::class, 'regional_manager_code', 'id');
    }

    public function idEstablishments()
    {
        $idsEstablishments = $this->establishments()->select('id')->get()->pluck('id')->all();
        return $idsEstablishments;
    }

    public function createDefaultRegional()
    {
        $defaultRegional = new RegionalManager();
        $defaultRegional->id = 1;
        $defaultRegional->name = 'Sisnoc';
        $defaultRegional->contact = 99999999999;
        $defaultRegional->email = 'default@sisnoc.com';
        $defaultRegional->status = 'inactive';
        return $defaultRegional->save();
    }

    //Access e Mutators

    public function setContactAttribute($value)
    {
        $this->attributes['contact'] = TextUtil::clearText($value);
    }

    public function getStatusAttribute($value)
    {
        return ($value == 'active' ? 'Ativo' : 'Inativo');
    }
}
