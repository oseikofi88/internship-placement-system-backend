<?php
namespace App\Controllers;


use App\Models\PlacementStatus;
use App\Models\User;
use App\Models\Student;
use App\Models\Location;
use App\Models\Admin;
use App\Models\Concern;
use App\Models\Company;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class UserController{



    public function registerUserAndGetID($user_type_id,$password){
        $helper_controller= new HelperController();
        $password = $helper_controller->hashGivenPassword($password);
        $user = User::create(array('user_type_id' => $user_type_id,'password'=>$password));
        return $user->id;

    }

    public function getStudentIDFromIndexNumber($index_number){
        $student = Student::where('index_number',(int)$index_number)->first();
        return $student->user_id;
    }
    public function getAdminIDFromUsername($username){
        $admin= Admin::where('username',$username)->first();
        if ($admin != null){
            
        return $admin->user_id;
        }
        else{
            return 0;
        }
    }


    public function checkIfPasswordMatchesUserID($user_id, $password){
        $helperFunction = new HelperController();
        $user = User::find((int)$user_id);


        if(User::where('id',(int)$user_id)->count() > 0){
        $user = User::find((int)$user_id);
        $stored_password = $user->password;
        $entered_password = $password;
        $user_id_matches_password = $helperFunction->verifyPassword($entered_password,$stored_password);

        return $user_id_matches_password;
            
        }
        else{
            return false;
        }
        /**
         *the return value of the verify password is boolean
         *$user_id_matches_password returns true if passwords matches
         **/

    }

    public function registerLocationAndGetID($name,$address,$detailed_address,$district,$region,$latitude,$longitude,$company_id){
        $location = Location::create(array('name'=>$name,'address'=>$address,
            'detailed_address'=>$detailed_address,'district' =>$district, 
            'region'=>$region,  'latitude'=>$latitude,'longitude'=>$longitude,'updated_by'=>$company_id));
        return $location->id;
    }


    public function registerLocationGetIDAndUpdateCompanyLocation($name,$address,$detailed_address,$district,$region,$latitude,$longitude,$user_id){
        $location = Location::create(array('name'=>$name,'address'=>$address,
            'detailed_address'=>$detailed_address,'district' =>$district, 
            'region'=>$region,  'latitude'=>$latitude,'longitude'=>$longitude,'updated_by'=>$user_id));
        return $location->id;
    }



    public function getStudentDetailsFromIndexNumber($index_number){
        $student = Student::where('index_number',(int)$index_number)->first();
        return $student;
    }

    public function getStudentUserIdFromIndexNumber($index_number) {

        $student = Student::where('index_number', $index_number)->first();

        return $student->user_id;
    }

    public function forwardConcernToCoordinator($request,$response,$args){
        $helper_controller = new HelperController();
        $form_input_data = $request->getParsedBody();
        $concern =  new Concern();

        if(isset($form_input_data["index_number"])){
            $index_number = $form_input_data["index_number"];
            $concern->user_id = $this->getStudentUserIdFromIndexNumber($index_number);

        $concern->title = $form_input_data["concern_title"];
        $concern->message = $form_input_data["concern_message"];

        $operation_successful = $concern->save();

        $student = Student::where('index_number', $index_number)->first();
        $coordinator_email = $helper_controller->getStudentCoordinatorEmail($student);


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


            $mail->setFrom(getEnv('EMAIL_CLIENT_NAME'), 'Vacation Training-College Of Engineering,KNUST');
            $mail->addAddress($coordinator_email);     // Add a recipient

            $mail->isHTML(true);     
            $mail->Subject = 'Concern Message From '.$student->surname.' '.$student->other_names;
            $mail->Body    = ' <html>
                <head>
                </head>
                <body>

                <h4> This is a concern filed by '.$student->surname.' '.$student->other_names.' from the '.$student->sub_department->main_department->name.'-'.$student->sub_department->name.' Engineering Department  with index number-'.$index_number. ' ,email-'.$student->email.' and phone-'.$student->phone.'</h4>

                        <h4> Title Of Concern: '. $concern->title .'</h4>
                     <h4>Message Of Concern:</h4>

                        <p>'. $concern->message.' </p>

                </body>
                </html>' ;
            $mail->send();


            $data["operation_successful"] = $operation_successful; 

            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

        }

catch (Exception $e) {
            $data["operation_successful"] = false; 

            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;


            }
        }


        else{
        $concern->user_id =  $form_input_data["company_user_id"];
        $concern->title = $form_input_data["concern_title"];
        $concern->message = $form_input_data["concern_message"];

         

        $operation_successful = $concern->save();
        $company = Company::where('user_id',$concern->user_id)->first();

        $coordinators = $helper_controller->getCoordinatorsWhoseDepartmentCompanyOrdererdFrom($concern->user_id);

        forEach($coordinators as $coordinator){


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


            $mail->setFrom(getEnv('EMAIL_CLIENT_NAME'), 'Vacation Training-College Of Engineering,KNUST');
            $mail->addAddress($coordinator->email);     // Add a recipient

            $mail->isHTML(true);     
            $mail->Subject = 'Concern Message From '.$company->name.' located in  '.$company->location->name;
            $mail->Body    = ' <html>
                <head>
                </head>
                <body>

                    <h4> This is a concern filed by '.$company->name.' located in  '.$company->location->name. ' </h4> 
                        <br>
                        <h4> Title Of Concern: '. $concern->title .'</h4>
                     <h4>Message Of Concern:</h4>

                        <p>'. $concern->message.' </p>

                </body>
                </html>' ;
            $mail->send();



        }

catch (Exception $e) {
            $data["operation_successful"] = false; 

            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));


    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;


            }


        }

            $data["operation_successful"] = $operation_successful; 

            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

        }




    }

 
    public function getPlacementStatus($request, $response, $args){
            
            $placement_status = PlacementStatus::first();
            $data["placement_done"] = $placement_status->placement_done;



        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));






    }


    /* public function sendStudentPasswordResetLink($request, $response, $args){ */
    /*     $generated_string_based_on_user_id =  password_hash(1 , PASSWORD_DEFAULT); */

    /*     //replace current_date_and_time and  generated_string_based_on_user_id variables to */ 
    /*     //to to hide it's intent or something */
        
    /*     $q = $generated_string_based_on_user_id; */
        


        

    /*     $mail = new PHPMailer(); */

    /*     try { */
    /*         $mail->SMTPDebug = 2; */
    /*         $mail->isSMTP();                                      // Set mailer to use SMTP */
    /*         $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers */
    /*         $mail->SMTPAuth = true;                               // Enable SMTP authentication */
    /*         $mail->Username = getEnv('EMAIL_CLIENT_NAME');                 // SMTP username */
    /*         $mail->Password = getEnv('EMAIL_CLIENT_PASSWORD');                           // SMTP password */
    /*         $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted */
    /*         $mail->Port = 465;                                   // TCP port to connect to */


    /*         $mail->setFrom(getEnv('EMAIL_CLIENT_NAME'), 'Mailer'); */
    /*         $mail->addAddress('oseikofi88@yahoo.com');     // Add a recipient */

    /*         $mail->isHTML(true); */     
    /*         $mail->Subject = 'Your Registration Details'; */
    /*         $mail->Body    = ' <html> */
    /*             <head> */
    /*             <title>My first PHP website</title> */
    /*             </head> */
    /*             <body> */


    /*             <a href="'.getEnv('RESET_PASSWORD_URL').$q.'">click here to reset your password</a><br/><br/> */
    /*             </body> */
    /*             </html>' ; */
    /*         $mail->send(); */

    /*     } */

/* catch (Exception $e) { */
    /* echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo; */
    /*         } */
/* } */



}





