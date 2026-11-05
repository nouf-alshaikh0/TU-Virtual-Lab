<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendances extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'schedule_id',
        'std_id',
        'created_at',
        'updated_at',
    ];
    public function student(){
        return $this->hasOne(User::class,'id','std_id');
    }
    public function schedule(){
        return $this->hasOne(schedule::class,'id','schedule_id');
    }
}
