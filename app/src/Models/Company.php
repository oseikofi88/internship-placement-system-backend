<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model{


    public $timestamps = false;
    protected $table = 'company';
    protected $fillable = ['user_id','name', 'email','location_id','postal_address','representative_name','representative_phone','representative_email' ,'time_of_registration', 'order_made'];

    public function department(){
        return $this->belongsTo('App\Models\Department');
    }

    public function location(){
        return $this->belongsTo('App\Models\Location');
    }
    public function departments(){
        return $this->belongsTo('App\Models\CompanyDepartment');
    }
    public function students(){
        return $this->hasMany('App\Models\Student','company_id','user_id');

    }
}

