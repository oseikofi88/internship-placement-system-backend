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
use App\Models\Admin;
use App\Transformers\StudentTransformer;
use App\Transformers\CompanyTransformer;
use \Firebase\JWT\JWT;
use Tuupola\Base62;
use Illuminate\Database\Capsule\Manager as DB;

final class AdminController
{


    public function loginAdmin($request, $response, $args)
    {
        $inputs = $request->getParsedBody();
        $user_controller = new UserController();
        //the common variable called data has been
        //renamed input so that when returning the
        //json encode, the request inputs wouldn't
        //be added since the json encode variable
        //is also called $data

        $user_id = $user_controller->getAdminIDFromUsername($inputs['admin_username']);
        $password_matches_with_admin_id = $user_controller->checkIfPasswordMatchesUserID($user_id, $inputs['admin_password']);




        if ($password_matches_with_admin_id) {
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
            $data["admin_id"] = $user_id;


            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        } else {
            $data['data'] = array('login_credentials' => 'false');
            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data['data'], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }
    }


    public function registerCompany($request, $response, $args)
    {

        $form_input_data = $request->getParsedBody();
        $helper_controller = new HelperController();
        $company = new Company();
        $generated_password = $form_input_data['name'];
        $user_controller = new UserController();
        $user_id    = $user_controller->registerUserAndGetID(2, $generated_password);
        $company->user_id  = $user_id;
        if (isset($form_input_data['email'])) {
            $company->email = $form_input_data['email'];
        }

        $company->user_id  = $user_id;
        $company->name = $form_input_data['name'];
        if (isset($form_input_data['phone'])) {
            $company->phone = $form_input_data['phone'];
        }

        $name_of_company = strtolower($company->name);
        $company_name_at_location = strtolower(strtok($form_input_data['location']['name'], ','));

        if ($name_of_company == $company_name_at_location) {
            $updater = $user_id;
        } else {
            $updater= null;
        }


        $company->location_id = $user_controller->registerLocationAndGetID(
            $form_input_data['location']['name'],
            $form_input_data['location']['address'],
            $form_input_data['location']['detailed_address'],
            $form_input_data['location']['district'],
            $form_input_data['location']['region'],
            $form_input_data['location']['latitude'],
            $form_input_data['location']['longitude'],
            $updater
        );

        if (isset($form_input_data['postal_address'])) {
            $company->postal_address = $form_input_data['postal_address'];
        }

        if (isset($form_input_data['website'])) {
            $company->website = $form_input_data['website'];
        }
        if (isset($form_input_data['company_representative_name'])) {
            $company->representative_name = $form_input_data['company_representative_name'];
        }
        if (isset($form_input_data['company_representative_name'])) {
            $company->representative_phone= $form_input_data['company_representative_phone'];
        }
        if (isset($form_input_data['company_representative_email'])) {
            $company->representative_email= $form_input_data['company_representative_email'];
        }

        $company->time_of_registration = date("Y-m-d H:i:s");
        $company->order_made = true;
        $company->save();



        $saved_company = Company::where('user_id', $company->user_id)->get();

        $fractal = new Manager();
        $fractal->setSerializer(new ArraySerializer);
        $resource = new Collection($saved_company, new CompanyTransformer());
        $data = $fractal->createData($resource)->toArray();


        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }

    public function registerCompanyAndMakeOrder($request, $response, $args)
    {

        $form_input_data = $request->getParsedBody();
        $helper_controller = new HelperController();
        $company = new Company();
        $generated_password = rand(1000, 9999);
        $user_controller = new UserController();
        $user_id    = $user_controller->registerUserAndGetID(2, $generated_password);
        $company->user_id  = $user_id;
        if (isset($form_input_data['email'])) {
            $company->email = $form_input_data['email'];
        }

        $company->user_id  = $user_id;
        $company->name = $form_input_data['name'];
        if (isset($form_input_data['phone'])) {
            $company->phone = $form_input_data['phone'];
        }


        $name_of_company = strtolower($company->name);
        $company_name_at_location = strtolower(strtok($form_input_data['location']['name'], ','));

        if ($name_of_company == $company_name_at_location) {
            $updater = $user_id;
        } else {
            $updater= null;
        }


        $company->location_id = $user_controller->registerLocationAndGetID(
            $form_input_data['location']['name'],
            $form_input_data['location']['address'],
            $form_input_data['location']['detailed_address'],
            $form_input_data['location']['district'],
            $form_input_data['location']['region'],
            $form_input_data['location']['latitude'],
            $form_input_data['location']['longitude'],
            $updater
        );
        if (isset($form_input_data['postal_address'])) {
            $company->postal_address = $form_input_data['postal_address'];
        }
        if (isset($form_input_data['website'])) {
            $company->website= $form_input_data['website'];
        }
        if (isset($form_input_data['company_representative_name'])) {
            $company->representative_name = $form_input_data['company_representative_name'];
        }
        if (isset($form_input_data['company_representative_name'])) {
            $company->representative_phone= $form_input_data['company_representative_phone'];
        }
        if (isset($form_input_data['company_representative_email'])) {
            $company->representative_email= $form_input_data['company_representative_email'];
        }

        $company->time_of_registration = date("Y-m-d H:i:s");
        $company->order_made = false;
        $company->save();

        
                $data["operation_successful"]= true;
                $data["company_id"]=  $company->user_id;




        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }






    public function placeStudentsInCompanyManually($request, $response, $args)
    {
        $form_input_data = $request->getParsedBody();
        $company_id = $form_input_data['company_id'];
        $students = array();
        $students = $form_input_data['students'];

        $helper_controller = new HelperController();

        foreach ($students as $student) {
            foreach ($student['index_numberData'] as $id) {
                $student_exist = $helper_controller->checkIfStudentExist($id['index_number']);

                if ($student_exist) {
                    $student =   Student::where('index_number', $id['index_number'])->update(array('company_id'=>$company_id));
                    // to do check if all of them were updated.
                }
            }
        }
            $data['operation_successful']= true;
            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data['operation_successful'], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }


    public function rejectStudentsPlacement($request, $response, $args)
    {

        $form_input_data = $request->getParsedBody();
        $students = array();
        $students = $form_input_data['students'];

        $helper_controller = new HelperController();

        foreach ($students as $student) {
            foreach ($student['index_numberData'] as $student_details) {
                $student_exist = $helper_controller->checkIfStudentExist($student_details['index_number']);

                if ($student_exist) {
                    $student =   Student::where('index_number', $student_details['index_number'])->update(array('want_placement'=>false,'rejected_placement'=>true,'reason_for_rejection'=>$student_details['reason_for_rejection'],'company_id'=> null));
                }
            }
        }
            $data['operation_successful']= true;
            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data['operation_successful'], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }

    public function orderForCompany($request, $response, $args)
    {
        
        $form_input_data = $request->getParsedBody();
        $company_id = $form_input_data['company_id'];
        $main_departments  =  array();
        $main_departments =  $form_input_data['mainDepartmentData'];

        foreach ($main_departments as $main_department) {
            foreach ($main_department['subDepartmentData'] as $sub_department) {
                $company_sub_department = new CompanySubDepartment();
                $company_sub_department->company_id = $company_id;
                $company_sub_department->sub_department_id = $sub_department['sub_department_id'];
                $company_sub_department->number_needed = $sub_department['number_needed'];
                $company_sub_department->save();
            }
        }

        $company = Company::where('user_id', $company_id)->update(array("order_made"=>true));


        if ($company_sub_department && $company) {
            $data["operation_successful"]= true;

            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        } else {
            $data["operation_successful"]= false;

            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }
    }
    
    public function registerCoordinator($request, $response, $args)
    {
        $form_input_data = $request->getParsedBody();
        $coordinator_email =   strtolower($form_input_data['coordinator_email']);
        $password = strtok($coordinator_email, '@')."@";
        $user_controller = new UserController();
        $user_id    = $user_controller->registerUserAndGetID(3, $password);
        $coordinator = new Coordinator();
        $coordinator->email = $coordinator_email;
        $coordinator->user_id = $user_id;
        $coordinator->save();

        if ($coordinator != null) {
            $data["operation_successful"]= true;

            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        } else {
            $data["operation_successful"]= false;

            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }
    }


    public function addNewAdmin($request, $response, $args)
    {
        $helperController = new HelperController();

        $form_input_data = $request->getParsedBody();
        $admin_username =  $form_input_data['admin_username'];
        $admin_password=  $form_input_data['admin_password'];
        $user_controller = new UserController();
        $user_id    = $user_controller->registerUserAndGetID(4, $admin_password);

        $admin = new Admin();
        $admin->username = $admin_username;
        $admin->user_id = $user_id;
        $admin->save();

        if ($admin == true) {
            $data["operation_successful"]= true;

            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        } else {
            $data["operation_successful"]= false;

            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }
    }


    public function checkIfAdminUsernameAlreadyExist($request, $response, $args)
    {
        
        $username = $_GET['username'];
        $admin= Admin::where('username', $username)->get();

        $data = $admin;

            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }


    public function getAllStudentsAndCompanyDetails($request, $response, $args)
    {


        $students_and_companies= DB::select("
SELECT company.name as company_name, student.surname,student.other_names,student.phone,main_department.name as main_department,sub_department.name as sub_department,location.name,location.address,location.district,location.region FROM student join company
on student.company_id = company.user_id
join location
on company.location_id = location.id
join sub_department
on student.sub_department_id  =sub_department.id
join main_department
on sub_department.main_department_id = main_department.id
ORDER by location.region,location.district,location.name
");

        $data["data"] = $students_and_companies;


        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }


    public function undoAllPlacement($request, $response, $args)
    {

        $student = Student::where('company_id', '<>', 'null')->update(array('company_id' => null));

        if ($student === 1) {
            $data["operation_successful"]= true;


            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        } else {
            $data["operation_successful"]= false;

            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }
    }



/*     public function betterPlacement() */
/*     { */
/*         $helperFunctions = new HelperController(); */
/*         $placementController = new PlacementController(); */
/*         /1* $company_offset = 0; *1/ */
/*         $department_offset = 0; */
/*      $distance_range = 1000; */
/*         /1* $companies_ids = $placementController->putSelectedCompaniesIdsInArray($companies_list); *1/ */
/*         /1* $students_index_numbers = $placementController->putSelectedStudentsIndexNumbersInArray($students_list); *1/ */

/*      /1* $last_student_id  = $placementController->getNextBatchOfStudentWithMatchingCriteria($department_id); *1/ */
/*      $tnod = $placementController->getTotalNumberOfDepartments(); */







/*         /1* for($i=0;$i<count($response['rows']);$i++){ *1/ */
/*         /1* $results = count($response[$i]); *1/ */
/*      /1* for($j=0;$j<count($results['elements']);$j++){ *1/ */

/*      while($tnod > 0){ */
/*          $department_id = $placementController->getCurrentDepartmentId($department_offset); */
/*                 echo "<br>"; */
/*          echo "yayyy so department_id ".$department_id; */
/*          echo "<br>"; */

/*          echo "also the total number of departments are ".$tnod; */


/*         $companies_list = $placementController->getSelectedCompaniesWithMatchingCriteria($department_id); */
/*         $students_list = $placementController->getSelectedStudentsWithMatchingCriteria($department_id); */
/*         $company_offset= $placementController->getCompanyOffset(); */
/*         $number_of_students = count($students_list); */
/*         $number_of_companies = count($companies_list); */

/*      while($number_of_companies > 0){ */

/*          //this is for the next batch of companies */


/*         while($number_of_students > 0 && $number_of_companies > 0){ */
/*         echo "the number of students being sent is ". $number_of_students. " and the number of companies being sent is ".$number_of_companies . " giving a toal of total number of results expecting ".$number_of_students * $number_of_companies; */
/*                 echo "<br>"; */
        
/*      foreach($companies_list as $company){ */
/*          echo $company->name . " , "; */
/*         } */

/*                 echo "<br>"; */
/*         $origins = $placementController->putCordinatesOfSelectedCompaniesInArray($companies_list); */
/*         $destinations = $placementController->putCordinatesOfSelectedStudentsInArray($students_list); */

/*                 echo "<br>"; */
/*      echo date('h:i:s'); */
/*         sleep(15); */
/*                 echo "<br>"; */
/*      echo date('h:i:s'); */
/*                 echo "<br>"; */

/*      $response = $placementController->getDistancesBetweenOriginsAndDestinations($origins, $destinations); */
/*      /1* var_dump($response); *1/ */


/*             $i = -1; */
/*             $j = -1; */
/*             foreach ($response['rows'] as $results) { */
/*                 $i++; */
/*              echo "<br>"; */
/*                 $company = $companies_list[$i]; */
/*                 echo "company selected is " . $company->name; */
/*                 foreach ($results['elements'] as $result) { */
/*                     $j++; */
/*                     echo "<br>"; */
/*                     $student = $students_list[$j]; */
/*                     echo "student selected is " . $student->surname . " " . $student->other_names; */
/*                     echo "<br>"; */
/*                     if($result['status'] === "OK"){ */
/*                     $distance_obtained = $result['distance']['text']; */
/*                         echo " distance between " . $student->surname . " " . $student->other_names . " and the company is  " . $distance_obtained; */
/*                         $number_of_students_needed_in_specific_company_department = $helperFunctions->getNumberOfStudentsNeededByCompanyPerDepartment($company->user_id, $department_id); */
/*                         if($number_of_students_needed_in_specific_company_department > 0){ */
/*                             if ($distance_obtained <= $distance_range)  { */
/*                                 $student_has_placement_already = $helperFunctions->checkIfStudentHasPlacementAlready($student->index_number); */
/*                                 if ($student_has_placement_already == false) { */
/*                                     $helperFunctions->placeStudentInCompany($student->index_number, $company->user_id); */
/*                                         /1* $number_of_students_placed_for_this_session +=1; *1/ */


/*                                     echo "<br>"; */
/*                                     echo "yayyy student " . $student->surname . " be placed in company " . $company->name; */
/*                                     echo "<br>"; */

/*                                 } */
/*                                 else { */
/*                                     echo "<br>"; */
/*                                     echo "student already has a company"; */
/*                                     echo "<br>"; */

/*             //this was already commented out. */

/*                                     /1* $company_id = $placementController->getIDOfCompanyStudentIsPlacedIn($student->index_number); *1/ */
/*                                     /1* $company_id_index = $placementController->getIndexOfCompanyInArrayWithCompanyID($company_id); *1/ */
/*                                     /1* $student_id_index = $placementController->getIndexOfStudentWithStudentIndexNumber($student->index_number); *1/ */
/*                                     /1* $distance_between_student_and_old_company = $response['rows'][$company_id_index]['elements'][$student_id_index]['distance']['text']; *1/ */
/*                                     /1* $distance_between_student_and_new_company = $response['rows'][$i]['elements'][$j]['distance']['text']; *1/ */
/*                                     /1* echo "<br>"; *1/ */
/*                                     /1* echo "new distance between student " .$student->other_names ." and new company which is " . $company->name . " is " . $distance_between_student_and_new_company . " and distance_between_student_and_old_company was " . $distance_between_student_and_old_company; *1/ */
/*                                 /1* echo "<br>"; *1/ */

/*                                 /1* if ($distance_between_student_and_new_company < $distance_between_student_and_old_company) { *1/ */
/*                                  /1* $helperFunctions->placeStudentInCompany($student->index_number, $company->user_id); *1/ */
/*                                 /1*     echo "<br>"; *1/ */
/*                                 /1*     echo "yayyy student " . $student->surname . " has been changed from his previous company to a new company called " . $company->name; *1/ */
/*                                 /1*     echo "<br>"; *1/ */


/*                                 /1* } *1/ */


/*                                 //check to see if the */
/*                                 //current distance between */
/*                                 //the student and the */
/*                                 //current company is */
/*                                 //shorter than where the */
/*                                 //student was previously */
/*                                 //placed, */
/*                                 //place student in new */
/*                                 // */
/*                                 // */
/*                                 /1* else { *1/ */
/*                                 /1*     echo "<br>"; *1/ */
/*                                 /1*     echo "apparently , distance is farther than where the student was initially placed or the distances are the same  "; *1/ */
/*                                 /1*     echo "<br>"; *1/ */
/*                                 /1* }; *1/ */

/*                             } */
/*                         } */

/*                         else{ */
/*                             echo "<br>"; */
/*                             echo "apparently hmmm, the distance between the student and the company is quite much"; */
/*                             echo "<br>"; */
/*                         } */

/*                     } */
/*                     else{ */
/*                         echo "<br>"; */
/*                         echo "awww the company is full "; */
/*                         echo "<br>"; */
/*                     } */
/*                     } */
/*                     else{ */
/*                     echo "hmmm no routable distance could be found between the student and the company"; */
/*                     } */


/*                 } */

/*                 $j = -1; */

/*             } */


/*             echo "done with current placement on to the next next set of students "; */
/*                         echo "<br>"; */

/*                         echo "good, now remove all companies that are full"; */
/*                 $new_companies_list_without_companies_which_are_full = $placementController->removeCompanyWhichIsFullFromCompanyList($department_id); */
/*                         $companies_list = $new_companies_list_without_companies_which_are_full; */
/*                         $number_of_companies = count($companies_list); */
/*                         echo "<br>"; */
/*                 echo "get next batch batch of students and run them with the remaining companies"; */
/*                 $students_with_matching_criteria_remaining = $placementController->getNumberOfStudentsWithMatchingCriteriaRemaining($department_id); */
/*                 if($students_with_matching_criteria_remaining > 0){ */
/*                     $students_list = $placementController->getNextBatchOfStudentWithMatchingCriteria($department_id); */
/*                     $number_of_students = count($students_list); */
/*             echo "<br>"; */
/*             echo " the number of students to be sent is now ".$number_of_students ." and the number of comanies to be sent is now ". $number_of_companies; */
/*             echo "<br>"; */
/*                 } */
/*                 else{ */
/*                     echo "<br>"; */
/*                     echo "no student found and hence number of students is 0"; */
/*                     echo "<br>"; */
/*                     $number_of_students = 0; */
/*                 } */

/*         } */
/*                     echo "<br>"; */
/*         echo "done with all students, time to move on to the next batch of companies"; */
/*         echo "<br>"; */


/*         echo "<br>"; */
/*         echo "get next batch of companies with matching criteria"; */
/*         echo "<br>"; */
/*         echo "<br>"; */
/*         echo "<br>"; */


/*         $companies_with_matching_criteria_remaining = $placementController->getNumberOfNextBatchOfCompaniesWithMatchingCriteria($department_id); */
/*         echo "<br>"; */
/*         echo "next batch of companies has a headcount of ".$companies_with_matching_criteria_remaining; */
/*         echo "<br>"; */
/*         $students_with_matching_criteria_remaining = $placementController->getNumberOfStudentsNotPlacedPerDepartment($department_id); */
/*         echo "<br>"; */
/*         echo "and the headcount of  students with matching criteria is " .$students_with_matching_criteria_remaining; */
/*         echo "<br>"; */

/*         if($companies_with_matching_criteria_remaining > 0 && $students_with_matching_criteria_remaining > 0){ */
/*             $companies_list =  $placementController->getNextBatchOfCompaniesWithMatchingCriteria($department_id); */
/*             $count_of_next_batch_of_companies = count($companies_list); */
/*             echo "<br>"; */
/*             echo "we will be sending a total of " . $count_of_next_batch_of_companies . "companies and "; */
            
/*             $number_of_companies = $count_of_next_batch_of_companies; */
/*             $students_list = $placementController->getSelectedStudentsWithMatchingCriteria($department_id); */
/*             $number_of_students = count($students_list); */
/*             echo $number_of_students ." number of students for a total of " . $number_of_companies * $number_of_students ; */
/*             echo "<br>"; */
        
/*         } */
/*         else{ */

/*             echo */

/*             $number_of_companies = 0; */
/*         } */

/*         echo "done with all the companies for a specific Department. on to the next Department "; */

/*      } */

/*      $department_offset++; */
/*      $tnod--; */

/*      } */


/*      echo "<br>"; */
/*      echo "no of departments done" ; */
/*      echo "<br>"; */






/*     } */

/* } */






































/* $orgin_latitude =  6.6788887000000; */
/* $origin_longitude = -1.509970500000; */

/* $destination_latitude = 5.5944916000000000; */
/* $destination_longitude =  -0.2678727999999600; */

/*     $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$orgin_latitude.",".$origin_longitude."&destinations=".$destination_latitude.",".$destination_longitude."&mode=driving&key=AIzaSyCIoWVrkxH9CYINjbUfGow81m2hZZgCsQY"; */
/*     $ch = curl_init(); */
/*     curl_setopt($ch, CURLOPT_URL, $url); */
/*     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); */
/*     curl_setopt($ch, CURLOPT_PROXYPORT, 3128); */
/*     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); */
/*     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); */
/*     $response = curl_exec($ch); */
/*     curl_close($ch); */
/* $response_a = json_decode($response, true); */
/* var_dump($response_a); */
/* $distance = $response_a['rows'][0]['elements'][0]['distance']['text']; */

/* return (float)$distance; //casted as value returned from google maps api is string */

/* $origins= [["lat"=>6.6788887000000,"long"=>-1.509970500000],["lat"=>6.6776931867338000,"long"=>-1.5846490742188000]]; */

/* $destinations= [["lat"=>5.5944916000000000,"long"=>-0.2678727999999600],["lat"=>6.6710437653718000000,"long"=>-1.5719461323242000]]; */


/* $first_origin_latitude =  6.6788887000000; */
/* $first_origin_longitude = -1.509970500000; */
/* $second_origin_latitude = 6.6776931867338000; */
/* $second_origin_longitude =-1.5846490742188000; */

/* $first_destination_latitude = 5.5944916000000000; */
/* $first_destination_longitude =  -0.2678727999999600; */
/* $second_desitnation_latitude = 6.6710437653718000000; */
/* $second_desitnation_longitude = -1.5719461323242000; */




/*     $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$first_origin_latitude.",".$first_origin_longitude."|".$second_origin_latitude.",".$second_origin_longitude."&destinations=".$first_destination_latitude.",".$first_destination_longitude."|".$second_desitnation_latitude.",".$second_desitnation_longitude."&&mode=driving&key=AIzaSyCIoWVrkxH9CYINjbUfGow81m2hZZgCsQY"; */
/*     $ch = curl_init(); */
/*     curl_setopt($ch, CURLOPT_URL, $url); */
/*     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); */
/*     curl_setopt($ch, CURLOPT_PROXYPORT, 3128); */
/*     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); */
/*     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); */
/*     $response = curl_exec($ch); */
/*     curl_close($ch); */
/* $response = json_decode($response, true); */
/*      $distance = $response['rows'][0]['elements'][0]['distance']['text']; *1/ */



/*  } */









    public function placeStudents($request, $response, $args)
    {

        $helperFunction = new HelperController();

        $distance_range = 1;// value at which distance will be incrementing
        $company_offset = 0; //get first company
        $department_offset = 0; // get first department
        $student_offset = 0; //get first student

        $tnoc = 0; //set total number of companies to 0;;
        $tnodpc = 0; // set total number of departments per company to 0;;
        $tnosnpdpc = 0; // set total number of students needed per department per company to 0;
        $tnoscppdpc = 0 ;//set total number of students currenyly placed per department per company;
        $nsdpcn = 0; //set number of students department per company needs  to 0;

        $current_student = 0; // set current student to 0;
        $current_department = 0;
        $current_company = 0;

        $number_looped = 0;




        while ($distance_range <= 20) { // 50 will be the maximum distance a student can be placed away from his residence
                    echo "<br>";
                    echo "<br>";
                    echo "<br>";
                    echo "range is " .$distance_range;
                    echo "<br>";
                    echo "<br>";
                    echo "<br>";
                    echo "<br>";
                    echo "<br>";

            $tnoc = $helperFunction->getTotalNumberOfCompaniesRegistered();


                    echo "<br>";
                    echo "total_number_companies are " .$tnoc;
                    echo "<br>";



            while ($tnoc > 0) {
                $current_company =  $helperFunction->getCurrentCompanyWhichIsNotFull($company_offset);
                    echo "<br>";
                    echo "currently selected company is before if statement is " . $current_company->name;
                    echo "<br>";
                if ($current_company) {
                    $tnodpc = $helperFunction->getTotalNumberOfDepartmentsInCompany($current_company->user_id);



                    echo "<br>";
                    echo "currently selected company is " . $current_company->name;
                    echo "<br>";
                    echo "total_number_of_depts_per_company for " . $current_company->name ." is ". $tnodpc;
                    echo "<br>";




                    while ($tnodpc > 0 && $current_department !== null) {
                        $current_department = $helperFunction->getCurrentDepartmentOfCurrentCompany($current_company->user_id, $department_offset);

                        if ($current_department) {
                            echo "<br>";
                            echo "current department selected is ". $current_department->sub_department->name;


                            $tnosnpdpc = $current_department->number_needed;
                            $tnoscppdpc = $helperFunction->getTotalNumberOfStudentsCurrentlyPlacedPerDepartmentPerCompany($current_department->sub_department->id, $current_company->user_id);
                            $nsdpcn = $tnosnpdpc - $tnoscppdpc;

                            echo "<br>";
                            echo "first number_needed in that department is " .$nsdpcn;
                            echo "<br>";

                            while ($nsdpcn > 0  && $current_student !== null) {
                                echo "<br>";
                                echo "now total number_needed in that department is " . $nsdpcn;
                                echo "<br>";



                                $current_student = $helperFunction->getStudentWithMatchingDepartment($current_department->sub_department_id, $student_offset);


                                echo "<br>";


                                if ($current_student) {// if $current_student is not null
                                    echo "student selected is ". $current_student->other_names;
                                    echo "<br>";


                                    $distance_between_student_residence_and_company_location = $helperFunction->getDistanceBetweenCoordinates($current_company, $current_student);
                                    echo "<br>";
                                    echo "the distance between student and the company is ". $distance_between_student_residence_and_company_location;
                                    echo "<br>";



                                    if ($distance_between_student_residence_and_company_location <= $distance_range) {
                                        Student::where('index_number', $current_student->index_number)->update(array('company_id'=> $current_company->user_id));
                                        $nsdpcn--;

                                        echo "<br>";
                                           echo $current_student->other_names ." has been  placed in ". $current_company->name;
                                        echo "<br>";
                                    } else {
                                        echo "<br>";
                                        echo "hmmm the distance is too far";


                                        $student_offset++; //if the distance between student residence and company is greater than set range
                                            // move on to the next student
                                    }
                                } else {
                                    echo "<br>";
                                    echo "no student available for " . $current_company->name . " in department " .  $current_department->sub_department->name ." in the range being looked for";
                                    echo "<br>";
                                }
                            }

                            echo "<br>";
                            echo $current_department->sub_department->name . " placement of " . $current_company->name . " has been done ";
                            echo "<br>";

                            $department_offset++; //placement for a specific department for a specific company has been done
                                            //move on to the next department

                            $current_student = 0;// set current_student variable to zero if not, the last student details of last
                                        // departmental placement will be used;
                                        // and if it was null, the  while($nsdpcn > 0  && $current_student !== null) will
                                        // which shouldn't be the case;
                        } else {
                            echo "<br>";
                            "current department does not exist";
                            echo "<br>";
                        }
                        $student_offset = 0;

                        $tnodpc--;
                    }


                    echo "<br>";
                    echo" total number of placements for ".$current_company->name . " has being done ";
                    echo "<br>";
                } else {
                    echo "<br>";
                    echo" current company ain't didn't take any students";
                    echo "<br>";
                }
                $company_offset++;
                $department_offset = 0;
                $tnoc--;
            }

                echo "<br>";
                    echo" all companies in range placements has been done";
                echo "<br>";
                    $distance_range +=1;
                    $company_offset = 0;
                    $current_company = 0;
                    echo" new distance range is ". $distance_range;
                echo "<br>";
        }

        echo "placements in all specified range done";
        echo "<br>";
        echo $number_looped;

        $data["operation_successful"] = true;


        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
}
