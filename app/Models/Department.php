<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    public function college(){
        return $this->belongsTo(College::class,'college_id');
    }
    public function admission(){
        return $this->belongsTo(AdmissionType::class,'admission_type_id');


    }
}
