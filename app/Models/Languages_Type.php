<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Languages_Type extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name',
        'description',
        'lang_id',
        'created_at',
        'updated_at',
    ];
    public function courses(){
        return $this->hasMany(Course::class,'lang_id','id');
    }
}
