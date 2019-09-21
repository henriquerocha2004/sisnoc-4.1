<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Links extends Model
{
    protected $table = 'links';

    protected $fillable = [
        'type_link',
        'bandwidth',
        'link_identification',
        'telecommunications_company',
        'monitoring_ip',
        'installed_router_model',
        'local_ip_router',
        'establishment_id'
    ];


    //Relations
    public function establishment()
    {
        return $this->belongsTo(Establishment::class, 'establishment_id', 'id');
    }

    public function called()
    {
        return $this->hasMany(Called::class, 'id_link', 'id');
    }
}
