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
use App\Models\CompanySubDepartment;
use App\Transformers\StudentTransformer;
use App\Transformers\CompaniesSuggestionTransformer;
use \Firebase\JWT\JWT;
use Tuupola\Base62;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



final class StudentController
{



    public function registerStudent($request, $response, $args)
    {
        $form_input_data = $request->getParsedBody();
        $helper_controller =  new HelperController();
        $student = new Student();
        $user_controller = new UserController();
        $student->user_id = $user_controller->registerUserAndGetID(1,$form_input_data['passwords']['password']);
        $student->index_number = $form_input_data['index_number'];
        $student->surname = $form_input_data['surname'];
        $student->other_names = $form_input_data['other_names'];
        $student->sub_department_id = $form_input_data['sub_department'];
        $student->phone = $form_input_data['phone'];
        $student->email = $form_input_data['email'];
        $student->location_id =  $user_controller->registerLocationAndGetID($form_input_data['locale']['name']
            ,$form_input_data['locale']['address'],$form_input_data['locale']['detailed_address'],$form_input_data['locale']['district']
            ,$form_input_data['locale']['region'],$form_input_data['locale']['latitude'],$form_input_data['locale']['longitude'],$student->user_id);

        $student->want_placement = $form_input_data['want_placement'];
        $student->registered_company = false;
        $student->foreign_student = $form_input_data['foreign_student'];
        $student->time_of_registration = date("Y-m-d H:i:s");

        $operation_successful =  $student->save();

        $helper_controller->sendStudentDetailsToEmail($student->surname, $student->email);


        if($operation_successful){


            //send an email with the users credentials
            //to confirm registration.
            $now = time();
            $future = time() + (60 * 60);
            $server = $request->getServerParams();
            $jti = (new Base62)->encode(random_bytes(16));


            $payload = array("user_id" => $student->user_id,
                "time_now" => $now,
                "expire_time" => $future,
                "jti" => $jti
            );


            $key = getenv('JWT_SECRET');
            $token = JWT::encode($payload, $key, "HS256");

            $data["token"] = $token;
            $data["index_number"] = $student->index_number;



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

        /* $data["operation_successful"] = $operation_successful; */ 

        /* return $response->withStatus(200) */
        /*     ->withHeader("Content-Type", "application/json") */
        /*     ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)); */


    }



    public function updateStudentDetails($request, $response, $args)
    {
        $form_input_data = $request->getParsedBody();
        $user_id = $form_input_data["user_id"];
        $student = new Student();
        $updater = Student::where('user_id',$user_id)->first();

        if(($form_input_data['want_placement'] != $updater->want_placement) || ($form_input_data['locale']['name'] != $updater->location->name )){
            $time_of_registration = date("Y-m-d H:i:s");
            $want_placement = $form_input_data['want_placement'];
            $company_id = null;
        }


        else{
            $time_of_registration = $updater->time_of_registration;
            $want_placement = $form_input_data['want_placement'];
            $company_id = $updater->company_id;
        }

        $user_controller = new UserController();
        $helper_controller = new HelperController();
        $index_number = $form_input_data['index_number'];
        $surname = $form_input_data['surname'];
        $other_names = $form_input_data['other_names'];
        $sub_department_name = $form_input_data['sub_department'];
        $sub_department_id = $helper_controller->getSubDepartmentIdFromSubDepartmentName($sub_department_name,$user_id);
        $phone = $form_input_data['phone'];
        $email = $form_input_data['email'];
        $student_location_id = $updater->location->id;
        $location = location::where('id',$student_location_id)->update(array(
            'name' => $form_input_data['locale']['name'],
            'address' => $form_input_data['locale']['address'],
            'detailed_address' => $form_input_data['locale']['detailed_address'],
            'district' => $form_input_data['locale']['district'],
            'region' => $form_input_data['locale']['region'],
            'latitude' => $form_input_data['locale']['latitude'],
            'longitude' => $form_input_data['locale']['longitude'],
        ));
        $foreign_student = $form_input_data['foreign_student'];


        $update_successful =  Student::where('user_id',$user_id)->update(array('index_number'=>$index_number,'surname'=>$surname,'other_names'=>$other_names ,'sub_department_id'=>$sub_department_id,'phone'=>$phone,'email'=>$email,'location_id'=>$student_location_id,'want_placement'=>$want_placement,'foreign_student'=>$foreign_student,'want_placement'=>$want_placement,'time_of_registration'=>$time_of_registration,'company_id'=>$company_id));


        if($update_successful === 1){

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

    public function loginStudent($request, $response, $args)
    {
        $inputs= $request->getParsedBody();
        $user_controller = new UserController();
        $user_id = $user_controller->getStudentIDFromIndexNumber($inputs['index_number']);

        //the common variable called data has been
        //renamed input so that when returning the
        //json encode, the request inputs wouldn't
        //be added since the json encode variable
        //is also called $data


        $password_matches_with_student_id = $user_controller->checkIfPasswordMatchesUserID($user_id,$inputs['password']);


        if($password_matches_with_student_id){
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
            $data["index_number"] = $inputs['index_number'];



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


    public function getAllStudents($request,$response,$args){
        $students = Student::all();

        $fractal = new Manager();
        $fractal->setSerializer(new ArraySerializer);
        $resource = new Collection($students,new StudentTransformer());
        $data = $fractal->createData($resource)->toArray();


        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));


    }


    public function getStudent($request,$response,$args){
        $index_number = $_GET['index_number'];
        $student = Student::where('index_number',$index_number)->get();

        $fractal = new Manager();
        $fractal->setSerializer(new ArraySerializer);
        $resource = new Collection($student,new StudentTransformer());
        $data = $fractal->createData($resource)->toArray();





        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

    }

    public function rejectPlacement($request, $response, $args){
        $form_input_data = $request->getParsedBody();
        $index_number = $form_input_data['index_number'];
        $reason_for_rejection = $form_input_data['reason_for_rejection'];
        $student = Student::where('index_number',$index_number)->update(array('want_placement'=>0, 'company_id'=> null,'reason_for_rejection'=>$reason_for_rejection, 'rejected_placement'=>1));
        //return value of an update is 1 if
        //successful and 0 if otherwise 
        //
        if ($student === 1){
            $data['data']  = array('operation_successful'=> true);

            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data['data'], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

        }

        else{

            $data['data']  = array('update_successful'=> false);

            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data['data'], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));


        }
    }


    public function updateSupervisorAndCompanyLocationDetails($request, $response, $args){

        $data = $request->getParsedBody();
        $index_number = $data['index_number'];
        if($data['company_location']['address']){
            $student = Student::where('index_number',$index_number)->update(array(
                'supervisor_name' => $data['supervisor_name'],
                'supervisor_contact'=> $data['supervisor_contact'],
                'supervisor_email'=> $data['supervisor_email'],
            ));

            $updater = Student::where('index_number',$index_number)->first();
            $company_location_id = $updater->company->location->id;
            $location = location::where('id',$company_location_id)->update(array(
                'address' => $data['company_location']['address'],
                'detailed_address' => $data['company_location']['detailed_address'],
                'district' => $data['company_location']['district'],
                'region' => $data['company_location']['region'],
                'latitude' => $data['company_location']['latitude'],
                'longitude' => $data['company_location']['longitude'],
                'updated_by'=> $updater->user_id
            ));


            if ($student === 1 && $location=== 1){
                $data['data']  = array('update_successful'=> 'true');

                return $response->withStatus(200)
                    ->withHeader("Content-Type", "application/json")
                    ->write(json_encode($data['data'], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));


            }

            else{
                $data['data']  = array('update_successful'=> 'false');

                return $response->withStatus(200)
                    ->withHeader("Content-Type", "application/json")
                    ->write(json_encode($data['data'], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

            }
        }

        else{
            $student = Student::where('index_number',$index_number)->update(array(
                'supervisor_name' => $data['supervisor_name'],
                'supervisor_contact'=> $data['supervisor_contact'],
                'supervisor_email'=> $data['supervisor_email'],
            ));


            if ($student === 1){ 
                $data['data']  = array('update_successful'=> 'true');

                return $response->withStatus(200)
                    ->withHeader("Content-Type", "application/json")
                    ->write(json_encode($data['data'], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));


            }

            else{
                $data['data']  = array('update_successful'=> 'false');

                return $response->withStatus(200)
                    ->withHeader("Content-Type", "application/json")
                    ->write(json_encode($data['data'], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

            }

        }
    }


    public function registerCompany($request, $response, $args){

        /* $upload = $request->getUploadedFiles(); */

        $user_controller = new UserController();
        $company =  new Company();
        $student = new Student();
        $helperController = new HelperController();



        $json_encoded_data = $request->getParsedBody();
        $decoded = json_decode($json_encoded_data["input"]); // we decode because we are recieving it as a jsonified string
        $index_number = $decoded->{'index_number'}; 


        $student= Student::where('index_number', $index_number)->first();
        $random_string = substr(md5(microtime()),rand(0,26),6);	  //this will be used as password for the company the student is registering
        $company_id = $user_controller->registerUserAndGetID(2,$random_string);
        $company->user_id = $company_id;
        $company->name = $decoded->{'name'};
        $company->email = $decoded->{'email'};
        $company->phone = $decoded->{'phone'};
        $company->website = $decoded->{'website'};
        $company->postal_address= $decoded->{'postal_address'};
        $company->location_id = $user_controller->registerLocationGetIDAndUpdateCompanyLocation($decoded->{'location'}->{'name'},$decoded->{'location'}->{'address'},$decoded->{'location'}->{'detailed_address'},$decoded->{'location'}->{'district'},$decoded->{'location'}->{'region'},$decoded->{'location'}->{'latitude'},$decoded->{'location'}->{'longitude'},$student->user_id);
        $company->representative_name = $decoded->{'company_representative_name'};
        $company->representative_phone= $decoded->{'company_representative_phone'};
        $company->representative_email= $decoded->{'company_representative_email'};
        $company->time_of_registration = date("Y-m-d H:i:s");

        $company->save();



        if(isset($_FILES["uploadFile"])){

            $target_directory = "file_uploads/";
            $uploaded_file = $target_directory.basename($index_number.'-'.$student->surname.'-'.$student->other_names.'-'.$student->sub_department->name.'-'.date("Y-m-d H:i:s"));
            move_uploaded_file($_FILES["uploadFile"]["tmp_name"], $uploaded_file);



            $student = Student::where('index_number',$index_number)->update(array('acceptance_letter_url' =>  $uploaded_file,'company_id'=> $company_id,'registered_company'=>true));

            if($student && $company ){

                $data["operation_successful"]= true;
                $data["company_id"]=  $company_id;

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




        else{

            $student = Student::where('index_number',$index_number)->update(array('company_id'=> $company_id,'registered_company'=>true));
            if($company && $student ){
                $data["operation_successful"]= true;
                $data["company_id"]=  $company_id;

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


    }


    public function orderForCompany($request, $response, $args){
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
    public function recordTimeStudentStartedInternship($request, $response, $args){

        $index_number = $_GET['index_number'];
        $current_time = date('Y-m-d H:i:s'); 

        $update_successful = Student::where('index_number',$index_number)->update(array('time_of_starting_internship'=> $current_time));

        if($update_successful === 1){

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


    public function uploadAcceptanceLetter($request, $response, $args){

        $json_encoded_data = $request->getParsedBody();
        $decoded = json_decode($json_encoded_data["input"]); // we decode because we are recieving it as a jsonified string
        $index_number = $decoded->{'index_number'}; 

        $student= Student::where('index_number', $index_number)->first();

        $target_directory = "file_uploads/";
        $uploaded_file = $target_directory.basename($index_number.'-'.$student->surname.'-'.$student->other_names.'-'.$student->sub_department->name.'-'.date("Y-m-d H:i:s"));
        move_uploaded_file($_FILES["uploadFile"]["tmp_name"], $uploaded_file);



        $update_successful = Student::where('index_number',$index_number)->update(array('acceptance_letter_url' =>  $uploaded_file));

        if($update_successful === 1){

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


    public function getListOfRegisteredCompanies($request,$response,$args){

        $parsed_data = $request->getParsedBody();
        $entered_value = $parsed_data['company_name'];
        $suggested_companies = Company::where('name','LIKE','%'.$entered_value.'%')->get();


        $fractal = new Manager();
        $fractal->setSerializer(new ArraySerializer);
        $resource = new Collection($suggested_companies,new CompaniesSuggestionTransformer());
        $returned_data = $fractal->createData($resource)->toArray();

        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($returned_data,JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));


    }

    public function sendPasswordResetLink($request, $response, $args){
        $form_input_data = $request->getParsedBody();
        $helper_controller = new HelperController();
        $email = $form_input_data['email'];
        $email_exist = $helper_controller->checkIfUserWithSuchEmailExist($email);
        if($email_exist){

            $student =  $helper_controller->getStudentUserFromEmail($email);
            $generated_string_based_on_user_id =  md5($student->user_id);

            //replace current_date_and_time and  generated_string_based_on_user_id variables to 
            //to to hide it's intent or something






            $mail = new PHPMailer();

            try {
                /* $mail->SMTPDebug = 2; */
                $mail->isSMTP();                                      // Set mailer to use SMTP
                $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                $mail->Username = getEnv('EMAIL_CLIENT_NAME');                 // SMTP username
                $mail->Password = getEnv('EMAIL_CLIENT_PASSWORD');                           // SMTP password
                $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 465;                                   // TCP port to connect to


                $mail->setFrom(getEnv('EMAIL_CLIENT_NAME'), 'Vacation Training-College of Engineering,KNUST');
                $mail->addAddress($student->email);     // Add a recipient

                $mail->isHTML(true);     
                $mail->Subject = 'Reset Your Password For The Internship Placement System.';
                $mail->Body    = ' <html>
                    <head>
                    </head>
                    <body>

                    <h4> You requested to reset your password , please click on the link below </h4>
                    <br>
                    <br>


                    <a href="'.getEnv('RESET_PASSWORD_URL').$generated_string_based_on_user_id.'/reset-password'.'">click here to reset your password</a><br/><br/>
                    </body>
                    </html>' ;
                $mail->send();


                $data["operation_successful"] = true; 

                return $response->withStatus(200)
                    ->withHeader("Content-Type", "application/json")
                    ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

            }

            catch (Exception $e) {
                echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;

                $data["operation_successful"] = false; 

                return $response->withStatus(200)
                    ->withHeader("Content-Type", "application/json")
                    ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

            }
        }

        else{

            $data["operation_successful"] = false; 

            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

        }
    }

    public function resetPassword($request, $response, $args){

        $form_input_data = $request->getParsedBody();

        $index_number = $form_input_data["index_number"];
        $encrypted_key = $form_input_data["encrypted_key"];
        $password = $form_input_data['passwords']['password'];


        $helper_controller = new HelperController();
        $index_number_matches_user_id = $helper_controller->checkIfIndexNumberMatchesEncryptedUserID($index_number, $encrypted_key);
        if($index_number_matches_user_id){
            $student = Student::where('index_number',$index_number)->first();
            $hashed_password = $helper_controller->hashGivenPassword($password);
            $password_update = User::where('id',$student->user_id)->update(array('password'=>$hashed_password));

            if($password_update == 1){
                $data["operation_successful"] = true; 

                return $response->withStatus(200)
                    ->withHeader("Content-Type", "application/json")
                    ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

            }



        }
        else{
            $data["operation_successful"] = false; 

            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

        }







    }
}






