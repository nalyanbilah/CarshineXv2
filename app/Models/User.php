<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'employee_id',
        'position',
        'department',
        'avatar',
        'status',
        'current_workload',
        'max_workload',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'user_skills')
                    ->withPivot('proficiency_level')
                    ->withTimestamps();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function performances()
    {
        return $this->hasMany(Performance::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }
    
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function isAvailable()
    {
        return $this->status === 'active' && 
               $this->current_workload < $this->max_workload;
    }

    public function getAvailableCapacity()
    {
        return $this->max_workload - $this->current_workload;
    }
}