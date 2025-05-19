<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'phone',
        'password',
        'role',
        'institution_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the submissions made by the user.
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * Get the institution associated with the user.
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }
    public function permissions()
    {
    return $this->hasMany(Permission::class);
    }
    // A citizen's submissions
public function submissionsSent()
{
    return $this->hasMany(Submission::class, 'citizen_id');
}

// An institution's received submissions
public function submissionsReceived()
{
    return $this->hasMany(Submission::class, 'institution_id');
}

public function complaints()
{
    return $this->hasMany(Complaint::class);
}
public function feedbacks()
{
    return $this->hasMany(Feedback::class);
}
public function user()
{
    return $this->belongsTo(User::class);
}

public function complaint()
{
    return $this->belongsTo(Complaint::class);
}

}

