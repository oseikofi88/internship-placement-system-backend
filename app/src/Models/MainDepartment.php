<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class MainDepartment extends Model{

    protected $table = 'main_department';
    protected $fillable = ['name'];

	public function students(){


		return $this->hasMany('App\Models\Student');

	}

	public function sub_departments(){


		return $this->hasMany('App\Models\SubDepartment');

	}
}
