<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'type',
        'reason',
        'status',
        'approved_by',
        'remarks',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getDurationAttribute()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'approved' => 'text-green-700 bg-green-100',
            'rejected' => 'text-red-700 bg-red-100',
            'pending' => 'text-yellow-700 bg-yellow-100',
            'cancelled' => 'text-gray-700 bg-gray-100',
            default => 'text-gray-700 bg-gray-100',
        };
    }
}