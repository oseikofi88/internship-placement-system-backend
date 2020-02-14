<?php

namespace App\Controllers;




use App\Models\Student;
use App\Models\User;
use App\Models\Location;
use App\Models\Company;
use App\Models\SubDepartment;
use App\Models\MainDepartment;
use App\Models\CompanySubDepartment;
use App\Models\Coordinator;
use Illuminate\Database\Capsule\Manager as DB;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use RFHaversini\Distance;

class HelperController
{

    public function hashGivenPassword($given_password){

        $hashed_password = password_hash($given_password, PASSWORD_DEFAULT);
        return $hashed_password;

    }

    public function verifyPassword($given_password,$hash_value){
        return password_verify($given_password,$hash_value);
    }


    public function getSubDepartmentIdFromSubDepartmentName($sub_department_name,$user_id){

        /**
         * We need this if statement because 
         * If the student doesn't change his sub department and
         * his/her main department was Civil,
         * Because we are only getting sub_department names and civil is not
         * part of the sub department names therefore we can't get its id
         * So we just retrieve the student's old sub department id and return
         * it
         **/

        if($sub_department_name == "Civil"){
            $student = Student::where('user_id',$user_id)->first();
            return $student->sub_department_id;


        }
        else{

            $sub_department = SubDepartment::where('name',$sub_department_name)->first();
            return $sub_department->id;
        }
    }


    public function getTotalNumberOfCompaniesRegistered(){
                            

        $number_of_companies= Company::count();

        return $number_of_companies;
    }

    public function getCurrentCompanyWhichIsNotFull($company_offset){
        $company = Company::offset($company_offset)->limit(1)->first();

        echo"<br>";
        echo "company currently selected is " . $company->name ;
        echo"<br>";


        $total_number_of_students_ordered_by_company = CompanySubDepartment::where('company_id', $company->user_id)->sum('number_needed'); 
        //this means no company can be registered wihtout ordering for at least one student from at least one department .  

        if($total_number_of_students_ordered_by_company == null){
            $total_number_of_students_ordered_by_company = 0;
        }
        echo "<br>"; 
        echo " total_number_of_students_ordered_by_company " . $total_number_of_students_ordered_by_company;
        echo "<br>"; 
        $total_number_of_students_currently_placed_in_company = Student::where('company_id',$company->user_id)->count();
        echo "total_number_of_students_currently_placed_in_company ".$total_number_of_students_currently_placed_in_company;
        echo "<br>"; 

        $number_currently_needed = $total_number_of_students_ordered_by_company - $total_number_of_students_currently_placed_in_company;
        echo "number currently needed by company " .$number_currently_needed ;
        echo "<br>"; 

        if($number_currently_needed > 0){

            return $company;

        }
        else{
            return null;
        }
    }








    public function getTotalNumberOfDepartmentsInCompany($company_id){
        $total_number_of_depts_per_company = CompanySubDepartment::where('company_id', $company_id)->count();
        if($total_number_of_depts_per_company !== null){
            return $total_number_of_depts_per_company;
        }
        else{
            return 0;
        }
    }

    public function getCurrentDepartmentOfCurrentCompany($company_id, $department_offset){
        $current_department= CompanySubDepartment::where('company_id',$company_id)->offset($department_offset)->limit(1)->first();
        return $current_department;
    }


    public function getStudentWithMatchingDepartment($sub_department_id,$student_offset){
        $current_student = Student::where([['sub_department_id',$sub_department_id],['want_placement',true],['company_id', null],['rejected_placement', null]])->orderBy('time_of_registration', 'asc')->offset($student_offset)->limit(1)->first(); 
        return $current_student;
    }


    public function getTotalNumberOfStudentsCurrentlyPlacedPerDepartmentPerCompany($sub_department_id, $company_id){
        $number_currently_placed = Student::where([['sub_department_id', $sub_department_id],['company_id',$company_id]])->count();
        return $number_currently_placed; 
    }



    public function getCurrentStudent($student_offset){


        $student = Student::offset($student_offset)->limit(1)->first();

        return $student;



    }

    //placement helper functions

    public function getDistanceBetweenCoordinates($student, $company){

$distance_between_coordinates = Distance::toKilometers($student->location->latitude, $student->location->longitude, $company->location->latitude, $company->location->longitude);


        return $distance_between_coordinates;

    } 

    public function checkIfCompanyIsFullForSpecificDepartment($company_id, $department_id){
        $company = CompanySubDepartment::where([["company_id", $company_id],["department_id",$department_id]])->first();
        $number_company_ordered_for = $company->number_needed;
        $number_currently_placed_in_company = Student::where([["company_id", $company_id],["department_id",$department_id]])->count();

        $number_currently_needed = $number_company_ordered_for-$number_currently_placed_in_company;

        if( $number_currently_needed > 0 ){
            return false;
        }
        else{
            return true;
        }

    } public function getNumberOfStudentsNeededByCompanyPerDepartment($company_id,$department_id){ $company = CompanySubDepartment::where([["company_id", $company_id],["department_id",$department_id]])->first();
    $number_company_ordered_for= $company->number_needed;
    $number_currently_placed_in_company = Student::where([["company_id", $company_id],["department_id",$department_id]])->count();
    $number_needed_in_company = $number_company_ordered_for - $number_currently_placed_in_company;
    return $number_needed_in_company;

    }

    public function checkIfStudentHasPlacementAlready($index_number){
        $student = Student::where("index_number", $index_number)->first();
        if($student->company_id == null){
            return false;
        }
        else{
            return true;
        }
    }

    public function placeStudentInCompany($index_number,$company_id){
        Student::where("index_number", $index_number)->update(array("company_id"=>$company_id));

    }

    public function getIDOfCompanyStudentIsPlacedIn($index_number){
        $student = Student::where('index_number', $index_number)->first();
        return $student->company_id;

    }


    public function getStudentUserFromEmail($email){

        $student = Student::where('email', $email)->first();
        return $student;
    }

    public function checkIfUserWithSuchEmailExist($email){
        if (Student::where('email', $email)->count() > 0){
            return true; 
        }
        else{
            return false;
        }

    }

    public function checkIfStudentExist($index_number){
        if (Student::where('index_number', $index_number)->count() > 0){
            return true; 
        }
        else{
            return false;
        }

    }

    public function sendStudentDetailsToEmail($surname, $email){

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
            $mail->addAddress($email);     // Add a recipient

            $mail->isHTML(true);     
            $mail->Subject = 'Registration For Vacation Training Successful';
            $mail->Body    = ' <html>
                <head>
                </head>
                <body>

                <h4> Hello '.$surname.', </h4>
                <br>
                <p>You have recieved this mail to confirm that your registration was successful.Please do well to check your mail from time to time as your internship coordinators will be sending you information via email concerning anything they would like you to know.</p>


                Have a nice day '.$surname.'.
                <br>
                <br>
                Vacation Training Team,
                <br> College Of Engineering-KNUST.


                </body>
                </html>' ;
            $mail->send();



        }

        catch (Exception $e) {
            /* echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo; */


            $data["operation_successful"] = false; 

            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

        }
    }









    public function checkIfIndexNumberMatchesEncryptedUserID($index_number,$recieved_encrypted_key){
        $student = Student::where('index_number', $index_number)->first(); 
        $encrypted_key_from_database = md5($student->user_id);
        if($recieved_encrypted_key == $encrypted_key_from_database){
            return true;
        }
        else{
            return false;
        }


    }


    public function getStudentCoordinatorEmail($student){
        $sub_department_id = $student->sub_department_id;
        $sub_department = SubDepartment::where('id', $sub_department_id)->first();
        $coordinator_email = $sub_department->coordinator->email;
        return $coordinator_email;

    }

    public function sendCompanyGeneratedUserIDAndPasswordToCompanyEmail($company_user_id,$generated_password,$company_email){

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
            $mail->addAddress($company_email);     // Add a recipient

            $mail->isHTML(true);     
            $mail->Subject = 'Your Registration Details';
            $mail->Body    = ' <html>
                <head>
                <title>Login Credentials For KNUST Internship Placement System</title>
                </head>
                <body>
                <h4> Hello we would like to thank you for registering with us.</h4>
                You can use these credentials to login with the url "'.getEnv('GENERAL_URL').'/company/login"in order to know the students who
                will be coming to intern in your company </h4>

                <p> Company Id: '.$company_user_id .' <br>
                Password: '.$generated_password.'
                <br>
                <br>
                We will send you an email to notify you as to when you can use the credentials to login 
                in order to see the students placed in your company 
                <p>

                Have a nice day.
                <br>
                <br>
                The Vacation Training Team,
                <br> College Of Engineering-KNUST




                </body>
                </html>' ;
            $mail->send();

        }

        catch (Exception $e) {
            /* echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo; */
        }

    }

    public function getCoordinatorsWhoseDepartmentCompanyOrdererdFrom($company_id){

        $company_sub_departments =  CompanySubDepartment::where([['company_id',$company_id],['number_needed','>','0']])->get(['sub_department_id']);

        $coordinators_ids = SubDepartment::whereIn('id', $company_sub_departments)->get(['coordinator_id']);

        $coordinators= Coordinator::whereIn('user_id',$coordinators_ids)->get(['email']);
        return $coordinators;


    }

    public function checkIfCoordinatorEmailMatchesPassword($email, $entered_password){
        $coordinator = Coordinator::where('email', $email)->first();
        if($coordinator  == null ){
            return false;
        }
        else{
            $user = User::where('id', $coordinator->user_id)->first();

            return password_verify($entered_password,$user->password);
        }
    }

    public function getSubDepartmentsOfCoordinator($coordinator_email){
        $coordinator = Coordinator::where('email', $coordinator_email)->first();
        $sub_department_ids = SubDepartment::where('coordinator_id', $coordinator->user_id)->get(['id']);
        

        return $sub_department_ids;
    }
        
    public function getIdsOfCompaniesFromCoordinatorDepartment($coordinator_email){
        $companies_ids = DB::select( DB::raw("
           SELECT DISTINCT(company.user_id) as id
FROM company
join company_sub_department
on company_sub_department.company_id =  company.user_id
join sub_department
on sub_department.id = company_sub_department.sub_department_id
join coordinator
on coordinator.user_id = sub_department.coordinator_id
WHERE coordinator.email =:coordinator_email and company_sub_department.number_needed>0") 
,array('coordinator_email' =>$coordinator_email));





        //id's need to be formatted well before we can use it in eloquent 
        $new_ids_list = array();  
        forEach($companies_ids as $company_id){
        array_push($new_ids_list,$company_id->id);

        }

        return $new_ids_list;
    }

    public function getCoordinatorIdFromEmail($email){
        $coordinator = Coordinator::where('email', $email)->first();
        return $coordinator->user_id;
        
    }

    public function getCoordinatorEmailFromId($id){
        $coordinator = Coordinator::where('user_id', $id)->first();
        return $coordinator->email;
        
    }

}
