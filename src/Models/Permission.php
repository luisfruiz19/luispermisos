<?php

namespace LuisRolesPermisos\LuisPermisos\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'name', 'slug', 'description',
    ];

    public function roles()
    {
        return $this->belongsToMany('LuisRolesPermisos\LuisPermisos\Models\Role')->withTimestamps();
    }
}
