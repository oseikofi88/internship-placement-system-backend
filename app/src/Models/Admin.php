<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model{


    public $timestamps = false;
    protected $table = 'admin';
    protected $fillable = ['user_id','username'] ;

}

