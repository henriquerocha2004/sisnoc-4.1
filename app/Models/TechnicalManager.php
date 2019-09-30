<?php

namespace App\Models;

use App\Utils\TextUtil;
use Illuminate\Database\Eloquent\Model;

class TechnicalManager extends Model
{

    protected $table = 'technical_manager';

    protected $fillable = [
        'name',
        'contact',
        'email',
        'status'
    ];

    //Relations

    public function establishments()
    {
        return $this->hasMany(Establishment::class, 'technician_code', 'id');
    }

    // Access e Mutators

    public function setContactAttribute($value)
    {
        $this->attributes['contact'] = TextUtil::clearText($value);
    }

    public function getStatusAttribute($value)
    {
        return ($value == 'active' ? 'Ativo' : 'Inativo');
    }

    //Methods

    public function idEstablishments()
    {

        $collect = collect($this->establishments()->select('id')->get()->toArray())->pluck('id');

        $ids = null;

        foreach ($collect as $value) {
            $ids['ids'][] = $value;
        }

        return $ids;
    }

    public function createDefaultTechnical()
    {
        $defaultTechnical = new TechnicalManager();
        $defaultTechnical->id = 1;
        $defaultTechnical->name = 'Sisnoc';
        $defaultTechnical->contact = 99999999999;
        $defaultTechnical->email = 'default@sisnoc.com';
        $defaultTechnical->status = 'inactive';
        return $defaultTechnical->save();
    }
}
