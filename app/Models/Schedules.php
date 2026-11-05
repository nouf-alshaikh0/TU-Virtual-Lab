<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Schedules extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'section_id',
        'week_id',
        'date',
        'hrs',
        'status_id',
        'created_at',
        'updated_at',
    ];
    
    protected $casts = [
        'date' => 'datetime',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
    public function week()
    {
        return $this->hasOne(Weeks::class, 'id', 'week_id');
    }
    public function submissions()
    {
        return $this->hasMany(submissions::class, 'schedule_id', 'id');
    }
    public function submission()
    {
        return $this->hasOne(submissions::class, 'schedule_id', 'id')->where('std_id',Auth::user()->id);
    }
    public function attendances()
    {
        return $this->hasMany(attendances::class, 'schedule_id', 'id');
    }
    

}
