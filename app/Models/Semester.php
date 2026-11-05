<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name',
        'created_at',
        'updated_at',
    ];
    public function students(){
        return $this->hasMany(User::class,'semester_id','id');
    }
}
