<?php

namespace App\Controllers;
use \League\Fractal\Resource\Collection as Collection;
use \League\Fractal\Resource\Item;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Manager;
use App\Models\Company;
use App\Models\User;
use App\Models\CompanySubDepartment;
use App\Models\Student;
use App\Models\PlacementStatus;
use App\Transformers\DepartmentTransformer;
use \Firebase\JWT\JWT;
use Tuupola\Base62;
use App\Transformers\CompanyTransformer;
use App\Transformers\StudentsPlacedInCompanyTransformer;

final class CompanyController
{


    public function registerCompany($request, $response,$args){

        $form_input_data = $request->getParsedBody();
        $helper_controller = new HelperController();
        $company = new Company();
            $generated_password = rand(1000,9999);
        $user_controller = new UserController();
        $user_id    = $user_controller->registerUserAndGetID(2,$generated_password);
        $company->user_id  = $user_id;
        if(isset($form_input_data['email'])){
            $company->email = $form_input_data['email'];
        $helper_controller->sendCompanyGeneratedUserIDAndPasswordToCompanyEmail($company->user_id,$generated_password,$company->email);
        }

        $company->user_id  = $user_id;
        $company->name = $form_input_data['name'];
        if(isset($form_input_data['phone'])){
            $company->phone = $form_input_data['phone'];
        }
            
        $name_of_company = strtolower($company->name);
        $company_name_at_location = strtolower(strtok($form_input_data['location']['name'],','));

        if($name_of_company == $company_name_at_location){
            $updater = $user_id;
        }

        else{
           $updater= null; 
        }
        

        $company->location_id = $user_controller->registerLocationAndGetID($form_input_data['location']['name']
            ,$form_input_data['location']['address'],$form_input_data['location']['detailed_address'],$form_input_data['location']['district']
            ,$form_input_data['location']['region'],$form_input_data['location']['latitude'],$form_input_data['location']['longitude'],$updater);

        if(isset($form_input_data['postal_address'])){

            $company->postal_address = $form_input_data['postal_address'];
        }
        if(isset($form_input_data['website'])){

            $company->website = $form_input_data['website'];
        }
        $company->representative_name = $form_input_data['company_representative_name'];
        $company->representative_phone= $form_input_data['company_representative_phone'];
        if(isset($form_input_data['company_representative_email'])){
            $company->representative_email= $form_input_data['company_representative_email'];
        $helper_controller->sendCompanyGeneratedUserIDAndPasswordToCompanyEmail($company->user_id,$generated_password,$company->representative_email);
        }
        $company->time_of_registration = date("Y-m-d H:i:s");
        $company->save();



        //email company id to them along with some
        //other credentials they may find
        //necessary

        if($company){ //if company has been saved;

        $now = time();
        $future = time() + (60 * 60);
        $server = $request->getServerParams();
        $jti = (new Base62)->encode(random_bytes(16));


        $payload = array("user_id" => $user_id,
            "time_now" => $now,
            "expire_time" => $future,
            "jti" => $jti
        );


        $key = getenv('JWT_SECRET');
        $token = JWT::encode($payload, $key, "HS256");

        $data["token"] = $token;
        $data["expires"] = $future;
        $data["user"] = $company;
        $data["operation_successful"] = true;


        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

        }

        else{
            $data["operation_successful"] = false;
            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

        }

    }


    public function loginCompany($request, $response, $args)
    {
        $inputs= $request->getParsedBody();
        $user_controller = new UserController();
        $user_id = $inputs['company_id']; 
        //the common variable called data has been
        //renamed input so that when returning the
        //json encode, the request inputs wouldn't
        //be added since the json encode variable
        //is also called $data



        $password_matches_with_company_id= $user_controller->checkIfPasswordMatchesUserID($user_id,$inputs['password']);


        if($password_matches_with_company_id){
            $now = time();
            $future = time() + (60 * 60);
            $server = $request->getServerParams();
            $jti = (new Base62)->encode(random_bytes(16));


            $payload = array("user_id" => $user_id,
                "time_now" => $now,
                "expire_time" => $future,
                "jti" => $jti
            );


            $key = getenv('JWT_SECRET');
            $token = JWT::encode($payload, $key, "HS256");

            $data["token"] = $token;
            $data["company_id"] = $user_id;



            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }


        else{

            $data['data']  = array('login_credentials'=> 'false');
            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data['data'], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

        }

    }



    public function getCompanyDetails($request, $response, $args){
        $user_id= $_GET['company_id'];
        $company= Company::where('user_id',$user_id)->get();

        $fractal = new Manager();
        $fractal->setSerializer(new ArraySerializer);
        $resource = new Collection($company,new CompanyTransformer());
        $data = $fractal->createData($resource)->toArray();




        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }

    public function makeStudentOrder($request,$response,$args){

        $form_input_data = $request->getParsedBody();
        $company_id = $form_input_data['company_id'];
        $main_departments  =  array();
        $main_departments =  $form_input_data['mainDepartmentData'];

        forEach($main_departments as $main_department){

        forEach($main_department['subDepartmentData'] as $sub_department){
        $company_sub_department = new CompanySubDepartment();
        $company_sub_department->company_id = $company_id;
        $company_sub_department->sub_department_id = $sub_department['sub_department_id'];
        $company_sub_department->number_needed = $sub_department['number_needed'];
        $company_sub_department->save();

        }

        }

        $company = Company::where('user_id', $company_id)->update(array("order_made"=>true));


        if($company_sub_department && $company){


            $data["operation_successful"]= true; 

            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data,JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

        }

        else{
            $data["operation_successful"]= false; 

            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data,JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));




    }
    }

    public function  studentsPlacedInCompanyDetails($request,$response,$args){
        $company_id = $_GET["company_id"];
        $students =  Student::where('company_id',$company_id)->get();

        $fractal = new Manager();
        $fractal->setSerializer(new ArraySerializer);
        $resource = new Collection($students,new StudentsPlacedInCompanyTransformer());
        $data = $fractal->createData($resource)->toArray();

        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"],JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));


    }


    public function getAllCompaniesPerDepartment($request, $response, $args){
         
        $coordinator_id = $_GET['coordinator_id'];

        $helperController = new HelperController(); 
        $coordinator_email = $helperController->getCoordinatorEmailFromId($coordinator_id);
        $coordinator_companies_ids = $helperController->getIdsOfCompaniesFromCoordinatorDepartment($coordinator_email);

        $companies = Company::whereIn('user_id',$coordinator_companies_ids)->orderBy('name')->get();



        $fractal = new Manager();
        $fractal->setSerializer(new ArraySerializer);
        $resource = new Collection($companies,new CompanyTransformer());
        $data = $fractal->createData($resource)->toArray();

        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

    }

    public function getAllCompaniesRegistered($request, $response, $args){
        $companies = Company::orderBy('name')->get();



        $fractal = new Manager();
        $fractal->setSerializer(new ArraySerializer);
        $resource = new Collection($companies,new CompanyTransformer());
        $data = $fractal->createData($resource)->toArray();

        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

    }

    }

