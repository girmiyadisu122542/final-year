<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function document(){
        return $this->belongsTo(Document::class,'document_id');
    }
    public function role(){
        return $this->belongsTo(Role::class,'role_id');
    }
   
}
