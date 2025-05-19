<?php

namespace App\Models;
use App\Models\Institution;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use HasFactory;

    // Table name (optional if it matches the model name pluralized)
    protected $table = 'institutions';

    // Mass assignable attributes
    protected $fillable = [
        'firstname',
        'lastname',
        'code',
        'type',
        'sector',
        'email',
        'phone',
        'website',
        'address',
        'location',
        'description',
        'is_active',
    ];

    // Cast attributes to native types
    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationship: One institution has many complaints
    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function users()
    {
    return $this->hasMany(User::class);
    }

}
