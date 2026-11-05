<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name',
        'course_id',
        'instructor_id',
        'created_at',
        'updated_at',
    ];
    public function instructor()
    {
        return $this->hasOne(User::class, 'id', 'instructor_id');
    }
    public function course()
    {
        return $this->hasOne(Course::class, 'id', 'course_id');
    }
    public function students()
    {
        return $this->hasMany(Std_Section::class, 'section_id', 'id');
    }
    public function schedules()
    {
        return $this->hasMany(Schedules::class, 'section_id', 'id');
    }
}
