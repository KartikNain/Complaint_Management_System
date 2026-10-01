<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class complaint extends Model
{

    protected $table = 'complaints';

    protected $fillable = [
        'user_id',
        'assigned_to',
        'title',
        'category',
        'department',
        'priority',
        'description',
        'attachment',
        'status',
        'resolution_summary',
    ];

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    // public function assignedStaff()
    // {
    //     return $this->belongsTo(User::class, 'assigned_to');
    // }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    
    public function comments()
    {
        return $this->hasMany(\App\Models\ComplaintComment::class, 'complaint_id')->latest();
    }
}


