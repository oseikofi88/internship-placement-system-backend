<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class SubDepartment extends Model{

    protected $table = 'sub_department';
    protected $fillable = ['name,main_department_id,coordinator'];

	public function students(){


		return $this->hasMany('App\Models\Student');

	}


    //this is the main department that the sub department belongs to 
    public function main_department(){
        return $this->belongsTo('App\Models\MainDepartment');
    } 

    public function coordinator(){
        return $this->belongsTo('App\Models\Coordinator','coordinator_id','user_id');
    } 
}
