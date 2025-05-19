<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name'];

    // One Role has many Permissions
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    // One Role has many Users
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // This method typically belongs to User model, 
    // but here it is added as you requested
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
