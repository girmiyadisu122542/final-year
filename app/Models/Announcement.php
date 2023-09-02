<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function year(){
        return $this->belongsTo(Year::class,'year_id');
    }
}
