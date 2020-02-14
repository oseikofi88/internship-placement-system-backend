<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Concern extends Model{


    protected $table = 'concern';
    protected $fillable = ['title','message','user_id'] ;


}


