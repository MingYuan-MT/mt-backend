<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Joiner extends Model
{
    use HasFactory;

    protected $fillable = ['metting_id','user_id','user_name','type','status','is_deleted','created_by','updated_by'];
}
