<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coordinator extends Model{


    public $timestamps = false;
    protected $table = 'coordinator';
    protected $fillable = ['user_id','email'] ;

    
    public function sub_departments(){
        return $this->hasMany('App\Models\SubDepartment','coordinator_id','user_id');
    }

}

