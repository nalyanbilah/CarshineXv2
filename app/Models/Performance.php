<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Performance extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'tasks_completed',
        'performance_score',
        'total_weight',
    ];

    protected $casts = [
        'date' => 'date',
        'performance_score' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getGradeAttribute()
    {
        if ($this->performance_score >= 3.5) return 'A';
        if ($this->performance_score >= 2.5) return 'B';
        if ($this->performance_score >= 1.5) return 'C';
        if ($this->performance_score >= 0.5) return 'D';
        return 'F';
    }

    public function getGradeColorAttribute()
    {
        return match($this->grade) {
            'A' => 'text-emerald-700 bg-emerald-100',
            'B' => 'text-blue-700 bg-blue-100',
            'C' => 'text-yellow-700 bg-yellow-100',
            'D' => 'text-orange-700 bg-orange-100',
            'F' => 'text-red-700 bg-red-100',
            default => 'text-gray-700 bg-gray-100',
        };
    }
}