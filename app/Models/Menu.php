<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    // Include user_id to allow mass assignment when saving a menu for a specific user
  protected $fillable = ['menu_name', 'link', 'icon', 'visible_in_sidebar', 'user_role'];


    // Define relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
