<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model{


    protected $table = 'student';
    protected $fillable = ['user_id','index_number','surname','other_names','foreign_student','want_placement','sub_department_id','location_id','phone','email','time_of_registration','acceptance_letter_url','picture_url','company_id','time_of_starting_internship','supervisor_name','supervisor_email','supervisor_contact','registered_company','rejected_placement','reason_for_rejection'];

    public function sub_department(){
        return $this->belongsTo('App\Models\SubDepartment');
    }

    public function location(){
        return $this->belongsTo('App\Models\Location');
    }

    public function company(){
        return $this->belongsTo('App\Models\Company','company_id','user_id');
    }
}

