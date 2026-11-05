<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
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
    public function pro_lang(){
        return $this->hasOne(Languages_Type::class,'id','lang_id');
    }
}
