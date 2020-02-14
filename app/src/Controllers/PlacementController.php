<?php

namespace App\Controllers;


use App\Models\Student;
use App\Models\User;
use App\Models\Location;
use App\Models\Company;
use App\Models\CompanyDepartment;
use App\Models\Department;
use Illuminate\Database\Capsule\Manager as DB;
/* use \DB; */



class PlacementController 
{



	public function getSelectedCompaniesWithMatchingCriteria($department_id){
		$helperFunction = new HelperController();



		/* set number of place origins to be sent as 0. */
		/*        Company Places will be sent as the origins. */
		$x = 0; 


		/* set number of place destinations to be sent as 0; */
		/* Student Places will be sent as destinations. */
		$y = 0; 


			/**set the average number of students that are needed by all companies from a specific department as 0;
	z = sum of students needed by all companies requesting from a particular department / sum of companies requesting from that particular department
			**/
		$z = 0; 
		$sum_of_students_needed_by_company_per_department = 0;
		$number_of_companies_requesting_for_students_per_department = 0;



		$number_of_companies_requesting_for_students_per_department= 0;
		$companies =  CompanyDepartment::where("department_id",$department_id)->get();
		foreach($companies as $company){
			$company_full =  $helperFunction->checkIfCompanyIsFullForSpecificDepartment($company->company_id,$department_id);
			if($company_full == false){
				$sum_of_students_needed_by_company_per_department += $helperFunction->getNumberOfStudentsNeededByCompanyPerDepartment($company->company_id, $department_id);
				$number_of_companies_requesting_for_students_per_department++;
			}
			else
			{
			}
		}



		//make sure to clear division by 0 error; by returning 0 as the comapny
		//list or something
		$z = ceil($sum_of_students_needed_by_company_per_department/$number_of_companies_requesting_for_students_per_department);

		echo "for each company needing ". $z. " students in that department";
		echo "<br>";
		echo "<br>";
		if(((sqrt(100*$z) <= 25)) && (100/(sqrt(100*$z)) <= 25)){ //making sure x<=25 and y<=25 for us not to hit above the threshold
		$y_1 = floor(sqrt(100*$z));
		echo "so the number of students to be sent is ".$y_1;
		echo "<br>";
		$x_1 = floor(100/$y_1);
		echo "so the number of companies to be sent is ".$x_1;
		echo "<br>";

		echo "total number of elements to be obtained is ". $y_1 * $x_1;

		echo "<br>";
		echo "<br>";
		echo "other method for improvement";
		$y_2 = ceil(sqrt(100*$z));
		echo "<br>";
		echo "so the number of studens to be sent is ".$y_2;
		echo "<br>";
		$x_2 = floor(100/$y_2);
		echo "so the number of companies to be sent is ".$x_2;
		echo "<br>";

		echo "total number of elements to be obtained is ". $y_2 * $x_2;

		if($y_1%$z < $y_2%$z){
		    $GLOBALS['x'] = $x_1;
		    $GLOBALS['y'] = $y_1;

		}

		else{
		    $GLOBALS['x'] = $x_2;
		    $GLOBALS['y'] = $y_2;

		}


		echo "<br>";
		echo "<br>";

		echo " so apparently, the combination that would be most efficient to be sent is companies with ".$GLOBALS['x']." and the number of students being ".$GLOBALS['y']." for a total of ".$GLOBALS['x'] * $GLOBALS['y']; 

		$companies_ordering_for_specific_department = CompanyDepartment::where("department_id",$department_id)->get();

		
		}
		else{
			$GLOBALS['x'] = 4;
			$GLOBALS['y'] = 25;
		echo " so apparently, the combination that would be most efficient to be sent is companies with ".$GLOBALS['x']." and the number of students being ".$GLOBALS['y']." for a total of ".$GLOBALS['x'] * $GLOBALS['y'];

		
		
		}
	

		
		$companies = CompanyDepartment::where("department_id",$department_id)->get();

				$GLOBALS['companies_ids'] = array();
		foreach($companies as $company){
			$company_full =  $helperFunction->checkIfCompanyIsFullForSpecificDepartment($company->company_id,$department_id);
			if($company_full == false){
				array_push($GLOBALS['companies_ids'],$company->company_id);
			}
		}

		echo "<br>";
		/* var_dump($companies_ids); */
		echo "<br>";


		$companies_list = Company::whereIn('user_id',$GLOBALS['companies_ids']) 
			->limit($GLOBALS['x'])
            ->orderBy('time_of_registration', 'asc')							  
			->get();
        
        /* $GLOBALS['company_ids'] = array(); */
        /* foreach($company_list as $company){ */
        /*     array_push($GLOBALS['company_ids'],$company->user_id); */
        /* } */

        $GLOBALS['companies_to_be_sent'] = $companies_list;
        $GLOBALS['last_company'] = $companies_list[count($companies_list)-1];

		return $companies_list;













	}


	public function getSelectedStudentsWithMatchingCriteria($department_id){

	
		$students_list = Student::where([["department_id",$department_id],
											["want_placement",true],
											["foreign_student",false],
											["company_id",null]])
                                            ->orderBy('time_of_registration', 'asc')							  
											->limit($GLOBALS['y'])
                                            ->get();
        $GLOBALS['student_ids'] = array();
        foreach($students_list as $student){
            array_push($GLOBALS['student_ids'],$student->index_number);
        }
            
        $GLOBALS['last_student_time_of_registration'] = $students_list[count($students_list)-1]->time_of_registration;
        echo "<br>";
        echo "last student name is " . $students_list[count($students_list)-1]->surname . " and time of time_of_registration " .$GLOBALS['last_student_time_of_registration'];
        echo "<br>";

	return $students_list;


    }

    public function getCompanyOffset(){
        return $GLOBALS['x'];
    }


    public function getStudentOffset(){
        return $GLOBALS['y'];
    }

	/* public function putSelectedStudentsIndexNumbersInArray($students){ */
	/* 	$students_ids = array(); */
	/* 	foreach($students as $student){ */
	/* 		array_push($students_ids,$student->index_number); */
	/* 	} */
	/* 	return $students_ids; */
	/* } */	

	/* public function putSelectedCompaniesIdsInArray($companies){ */
	/* 	$companies_ids = array(); */
	/* 	foreach($companies as $company){ */
	/* 		array_push($companies_ids, $company->user_id); */
	/* 	} */
	/* 	return $companies_ids; */

	/* } */
    
    
    
    public function getNextBatchOfStudentWithMatchingCriteria($department_id){

        $students_list = Student::where([["department_id",$department_id],
                                            ["time_of_registration",">",$GLOBALS['last_student_time_of_registration']],
											["want_placement",true],
											["foreign_student",false],
											["company_id",null]])
                                            ->orderBy("time_of_registration", "asc")							  
											->limit($GLOBALS['y'])
                                            ->get();
        $GLOBALS['student_ids'] = array();
        foreach($students_list as $student){
            array_push($GLOBALS['student_ids'],$student->index_number);
        }
            
        $GLOBALS['last_student_time_of_registration'] = $students_list[count($students_list)-1]->time_of_registration;
        echo "<br>";
        echo "last student name is " . $students_list[count($students_list)-1]->surname . " and that student time of registration is " .$GLOBALS['last_student_time_of_registration'] ;
        echo "<br>";

        return $students_list;

    }



    public function getLastStudentUserId(){
        return $GLOBALS['last_student_time_of_registration'] ;

    }


    public function putCordinatesOfSelectedCompaniesInArray($companies){
        $origins="";
        foreach($companies as $company){
            $origins .= $company->location->latitude.",".$company->location->longitude."|";

		}
		return substr($origins,0,-1);
	}

	public function putCordinatesOfSelectedStudentsInArray($students){
		$destinations="";
		foreach($students as $student){
			$destinations .=$student->location->latitude.",".$student->location->longitude."|";

		}
		return substr($destinations,0,-1);

	}


    public function getDistancesBetweenOriginsAndDestinations($origins,$destinations){


        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$origins."&destinations=".$destinations."&mode=driving&key=AIzaSyCIoWVrkxH9CYINjbUfGow81m2hZZgCsQY";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($ch);
            curl_close($ch);
			$response_a = json_decode($response, true);
			return $response_a;
}

public function removeCompanyWhichIsFullFromCompanyList($department_id){
		$helperFunction = new HelperController();
        $companies = $GLOBALS['companies_to_be_sent'];





				$companies_ids= array();
		foreach($companies as $company){
			$company_full =  $helperFunction->checkIfCompanyIsFullForSpecificDepartment($company->user_id,$department_id);
			if($company_full === false){
                array_push($companies_ids,$company->user_id);
            }

        }


        $company_list = Company::whereIn('user_id',$companies_ids)
            ->get();
            
        /* $GLOBALS['company_ids'] = array(); */
        /* foreach($company_list as $company){ */
        /*     array_push($GLOBALS['company_ids'],$company->user_id); */
        /* } */
                echo "<br>";
                echo "after the companies are checked if they are full or not, this is the total number of companies";
                echo "<br>";
        foreach($company_list as $company){

			echo $company->name . " , ";
        }

            return $company_list;

}

/* public function getIDOfCompanyStudentIsPlacedIn($index_number){ */
/*     $student = Student::select('company_id') */
/*         ->where('index_number',$index_number) */
/*         ->first(); */
/*     return $student->company_id; */
/* } */

/* public function getIndexOfCompanyInArrayWithCompanyID($company_id){ */
/*     $key = array_search($company_id,$GLOBALS['company_ids']); */
/*     return $key; */
/* } */

/* public function getIndexOfStudentWithStudentIndexNumber($index_number){ */
/*     $key = array_search($index_number, $GLOBALS['student_ids']); */
/*     return $key; */
/* } */

public function getNumberOfStudentsWithMatchingCriteriaRemaining($department_id){
    
        $students_count= Student::where([["department_id",$department_id],
                                            ["time_of_registration",">",$GLOBALS['last_student_time_of_registration']],
											["want_placement",true],
											["foreign_student",false],
											["company_id",null]])
                                            ->orderBy("time_of_registration", "asc")							  
                                            ->count();

    return $students_count;

    
}
public function getNumberOfStudentsNotPlacedPerDepartment($department_id){
    
        $students_count= Student::where([["department_id",$department_id],
											["want_placement",true],
											["foreign_student",false],
											["company_id",null]])
                                            ->orderBy("time_of_registration", "asc")							  
                                            ->count();

    return $students_count;

    
}

public function getNextBatchOfCompaniesWithMatchingCriteria($department_id){
    


        $companies_list = Company::whereIn('user_id',$GLOBALS['companies_ids'])
            ->where('time_of_registration','>',$GLOBALS['last_company']->time_of_registration)
            ->orderBy('time_of_registration', 'asc')							  
			->limit($GLOBALS['x'])
            ->get();

        
        /* $GLOBALS['company_ids'] = array(); */
        /* foreach($company_list as $company){ */
        /*     array_push($GLOBALS['company_ids'],$company->user_id); */
        /* } */
            
        $GLOBALS['companies_to_be_sent'] = $companies_list;
        $GLOBALS['last_company'] = $companies_list[count($companies_list)-1];

		return $companies_list;




} 
public function getNumberOfNextBatchOfCompaniesWithMatchingCriteria($department_id){
        $companies_count = Company::whereIn('user_id',$GLOBALS['companies_ids'])
            ->where('time_of_registration','>',$GLOBALS['last_company']->time_of_registration)
            ->orderBy('time_of_registration', 'asc')							  
            ->count();

        return $companies_count;

    
} 
public function getTotalNumberOfDepartments(){
    $number_of_departments = Department::count();

    return $number_of_departments;
}


public function getCurrentDepartmentId($department_offset){
        $department = Department::offset($department_offset)
                        ->limit(1)
                        ->first();
            return $department->id;
        

}

}
