<?php


namespace App\Controllers;

use \League\Fractal\Resource\Collection as Collection;
use \League\Fractal\Resource\Item;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Manager;
use App\Models\Student;
use App\Models\User;
use App\Models\Location;
use App\Models\Company;
use App\Models\Coordinator;
use App\Models\CompanySubDepartment;
use App\Transformers\StudentTransformer;
use App\Transformers\CompaniesSuggestionTransformer;
use \Firebase\JWT\JWT;
use Tuupola\Base62;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


use Illuminate\Database\Capsule\Manager as DB;


final class CoordinatorController 
{



    public function loginCoordinator($request, $response, $args)

    {
        $form_input_data = $request->getParsedBody();
        $email = $form_input_data['email'];
        $password = $form_input_data['password'];
        $helper_controller =  new HelperController();


        $email_matches_password = $helper_controller->checkIfCoordinatorEmailMatchesPassword($email, $password);

        If($email_matches_password) {
        $coordinator_id = $helper_controller->getCoordinatorIdFromEmail($email);
            $now = time();
            $future = time() + (60 * 60);
            $server = $request->getServerParams();
            $jti = (new Base62)->encode(random_bytes(16));

            $payload = array("user_email" => $email,
                "time_now" => $now,
                "expire_time" => $future,
                "jti" => $jti
            );


            $key = getenv('JWT_SECRET');
            $token = JWT::encode($payload, $key, "HS256");

            $data["operation_successful"]  = true;
            $data["token"] = $token;
            $data["coordinator_id"] = $coordinator_id;

            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }

        else{

            $data['operation_successful']  = array('login_credentials'=> 'false');
            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

        }
        
        }


    public function getCoordinator($request, $response, $args){
        $email = $_GET['email'];

        $coordinator  = Coordinator::where('email',$email)->get();
        $data = $coordinator;


        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }

    public function getAllCoordinators($request, $response, $args){
        $coordinators = DB::select("
SELECT DISTINCT(coordinator.user_id) ,coordinator.email ,main_department.name as main_department_name
FROM coordinator
join sub_department
on sub_department.coordinator_id = coordinator.user_id
join main_department
on main_department.id = sub_department.main_department_id  
ORDER BY `main_department`.`name` ASC
");

$data = $coordinators;



        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));



    
    }

    public function replaceCoordinator($request, $response, $args){


        $helperController = new HelperController();


        $form_input_data = $request->getParsedBody();
        $old_coordinator_id= $form_input_data['coordinator_id'];
        $new_coordinator_email = $form_input_data['coordinator_email'];
        $new_coordinator_password = $form_input_data['coordinator_password'];
        $hashed_password = $helperController->hashGivenPassword($new_coordinator_password);

        $user = User::where('id',$old_coordinator_id)->update(array('password' => $hashed_password));
        $coordinator = Coordinator::where('user_id',$old_coordinator_id)->update(array('email' => $new_coordinator_email));


        if($user == 1 && $coordinator == 1){

            $data['operation_successful'] = true;


        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            
        }
        else{

            $data['operation_successful'] = false;


        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }






    }
    }

