<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'citizen_id',
        'institution_id',
        'title',
        'description',
        'attachment',
        'status',
        'submitted_at',
        'reviewed_at',
    ];

    protected $dates = ['submitted_at', 'reviewed_at', 'created_at', 'updated_at'];

        public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    public function citizen()
    {
    return $this->belongsTo(User::class, 'citizen_id');
    }

public function institution()
{
    return $this->belongsTo(User::class, 'institution_id');
}
}
