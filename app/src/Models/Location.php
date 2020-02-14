<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model{

    protected $table = 'location';
    protected $fillable = ['name','address','detailed_address','district','region','longitude','latitude','updated_by'];

	public function company(){


		return $this->hasMany('App\Models\Company');

    }

    public function student(){
        return $this->hasMany('App\Models\Student');
        }
}
