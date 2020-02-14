<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySubDepartment extends Model{


    protected $table = 'company_sub_department';
    protected $fillable = ['company_id','sub_department_id','number_needed'];
        
    public function sub_department(){
        return $this->belongsTo('App\Models\SubDepartment');
    }

    public function company(){
        return $this->belongsTo('App\Models\Company');
    }
}

