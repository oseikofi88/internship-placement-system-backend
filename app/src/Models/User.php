<?php
/**
 * Created by PhpStorm.
 * User: kofi
 * Date: 7/13/17
 * Time: 3:55 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model{

   
    protected $table = 'user';
    protected $fillable = ['user_type_id','password','created_at','updated_at'];
}

