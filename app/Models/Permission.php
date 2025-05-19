<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'role', 'menu', 'can_read', 'can_create', 'can_edit', 'can_delete', 'can_approve'
    ];
}
