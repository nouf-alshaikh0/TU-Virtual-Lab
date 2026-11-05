<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Std_Section extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'std_id',
        'semester_id',
        'section_id',
        'created_at',
        'updated_at',
    ];
    public function semester()
    {
        return $this->hasOne(Semester::class, 'id', 'semester_id');
    }
    public function section()
    {
        return $this->hasOne(Section::class, 'id', 'section_id');
    }
    public function student()
    {
        return $this->hasOne(User::class, 'id', 'std_id');
    }
}
