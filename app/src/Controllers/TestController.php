<?php

namespace App\Controllers;
/* include './../../../ChromePhp.php'; */

use \League\Fractal\Resource\Collection as Collection;
use \League\Fractal\Resource\Item;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Manager;
use App\Models\Student;
use App\Models\User;
use App\Models\Location;
use App\Models\Company;
use App\Models\SubDepartment;
use App\Models\CompanySubDepartment;
use App\Transformers\StudentTransformer;
use \Firebase\JWT\JWT;
use Tuupola\Base62;
use Faker\Factory as Faker;


final class TestController 
{


    public function distanceMatrix($request,$response,$args){

    }



    public function register1000Students($request, $response, $args)
    
    {

        $faker = Faker::create();


        for($i=0;$i<19;$i++){
            
            $index_number = mt_rand(9700000,9799999);

        $student = new Student();
        $user_controller = new UserController();
        $student->user_id = $user_controller->registerUserAndGetID(1,$index_number);//make index_number as password ;
        $student->index_number = $index_number;
        $student->surname = $faker->lastName;
        $student->other_names = $faker->firstName;
        $student->sub_department_id = rand(1,19);
        $student->phone = $faker->phoneNumber;
        $student->email = $faker->email;
        $student->location_id = $user_controller->registerLocationAndGetID($faker->streetName,$faker->streetAddress,$faker->latitude($min=6.628142, $max=6.964567),$faker->longitude($min=-1.722193,$max=-1.047821));
        $student->want_placement = rand(0,1);
        $student->foreign_student = rand(0,1);
        $student->registered_company = rand(0,1);
        $student->rejected_placement= rand(0,1);
        $student->company_id = null;
        $student->time_of_registration = $faker->dateTime($max='now');

        //send an email with the users credentials
        //to confirm registration.



        $student->save();

        }

        echo "done registering total number of students";
    }

    public function register1000Companies($request,$response,$args){
        $number_of_departments = SubDepartment::count();
        $faker = Faker::create();

        for($i=0;$i<3;$i++){
        $company= new Company();
        $user_controller = new UserController();
        $company_id= $user_controller->registerUserAndGetID(2,$i);//make $i as password ;
        $company->user_id =$company_id;
        $company->name= $faker->company;
        $company->email = $faker->email;
        $company->phone = $faker->tollFreePhoneNumber;
        $company->location_id = $user_controller->registerLocationAndGetID($faker->streetName,$faker->streetAddress,$faker->latitude($min=6.628142, $max=6.964567),$faker->longitude($min=-1.722193,$max=5.3901977999));
        $company->postal_address = $faker->postcode;
        $company->representative_name = $faker->name;
        $company->representative_email  = $faker->email;
        $company->representative_phone= $faker->phoneNumber;
        $company->order_made = true;
        $company->time_of_registration = $faker->dateTime($max='now');
        $company->save();

            for($j=0; $j<$number_of_departments;$j++){

            $company_department = new CompanySubDepartment();

            $company_department->sub_department_id = $j+1; //because first department id is 1 and i is 0, so 0+1 to get first department
            $company_department->company_id = $company_id;
            $company_department->number_needed = rand(0,10);

            $company_department->save();
            }
        }


        echo "done registering total number of companies";
    }

    public function fillNullLocations($request,$response,$args){
        
        $locations = Location::where('detailed_address', '=', null)->get();

        foreach($locations as $location){

        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$location->latitude.",".$location->longitude."&key=AIzaSyCIoWVrkxH9CYINjbUfGow81m2hZZgCsQY";


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response, true);


        $district = '';
        $detailed_address= '';
        $region = '';
        $country ='';
        

        foreach($response_a['results'][0]['address_components'] as $address_components){


            $detailed_address.=$address_components['long_name']."/";
        }    
        
        $district .=$response_a['results'][0]['address_components'][count($response_a['results'][0]['address_components'])-3]['long_name'];
            $region .=$response_a['results'][0]['address_components'][count($response_a['results'][0]['address_components'])-2]['long_name'];
            $country.=$response_a['results'][0]['address_components'][count($response_a['results'][0]['address_components'])-1]['long_name'];


            $location = Location::where('id',$location->id)->update(array('detailed_address'=>$detailed_address,'district'=>$district,'region'=>$region)) ;
        
        

    }

        echo "done";
            
        }

    public function chromePhp($request, $response)   {
        /* ChromePhp::log('hello'); */
        $path = "./../../../ChromePhp.php";
        echo "Path : $path";

        require $path;
        
    }
        
        
    }




