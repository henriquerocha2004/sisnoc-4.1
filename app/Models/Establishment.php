<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Utils\TextUtil;

class Establishment extends Model
{

    protected $table = 'establishment';

    protected $fillable = [
        'establishment_code',
        'address',
        'neighborhood',
        'city',
        'state',
        'document_establishment',
        'document_establishment_alternate',
        'phone_establishment',
        'branch_establishment',
        'opening_hours',
        'manager_name',
        'manager_contact',
        'regional_manager_code',
        'technician_code',
        'holiday',
        'establishment_status',
        'id_user',
    ];

    //Relations

    public function regionalManager()
    {
        return $this->hasOne(RegionalManager::class, 'id', 'regional_manager_code');
    }

    public function technicalManager()
    {
        return $this->hasOne(TechnicalManager::class, 'id', 'technician_code');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }

    public function calls()
    {
        return $this->hasMany(Called::class, 'id_establishment', 'id');
    }

    public function links()
    {
        return $this->hasMany(Links::class, 'establishment_id', 'id');
    }


    //Access e Mutators
    public function setDocumentEstablishmentAttribute($value)
    {
        $this->attributes['document_establishment'] = TextUtil::clearText($value);
    }

    public function setPhoneEstablishmentAttribute($value)
    {
        $this->attributes['phone_establishment'] = TextUtil::clearText($value);
    }

    public function setManagerContactAttribute($value)
    {
        $this->attributes['manager_contact'] = TextUtil::clearText($value);
    }
}
