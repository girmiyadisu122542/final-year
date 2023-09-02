<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function department(){
        return $this->belongsTo(Department::class,'department_id');
    }
    public function year(){
        return $this->belongsTo(Year::class,'year_id');
    }
    public function advisor(){
        return $this->belongsTo(User::class,'advisor_id');
    }
}
