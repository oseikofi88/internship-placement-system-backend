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
use Illuminate\Database\Capsule\Manager as DB;

final class StudentStatisticsController 
{



    public function getGeneralStudentStatistics($request, $response, $args){
        $general_student_statistics = DB::select("
SELECT sub_department.name sub_department ,main_department.name main_department,
count(*) registered,
sum(case when student.want_placement = 1 then 1 else 0 end) want_placement,
sum(case when student.company_id is not null and student.registered_company = 0 and student.want_placement = 1 then 1 else 0 end ) placed_by_college,
sum(case when student.company_id is null and student.want_placement = 1 then 1 else 0 end ) not_placed_by_college,
sum(case when student.rejected_placement = 1  then 1 else 0 end ) rejected_college_placement,
sum(case when student.registered_company = 1  then 1 else 0 end ) searched_for_own_company
from student
join sub_department
on student.sub_department_id = sub_department.id
join main_department
on main_department.id = sub_department.main_department_id
group by sub_department.name  
ORDER BY `main_department` ASC

");

        $data["data"] = $general_student_statistics;

        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));


    }


    public function getGeneralStudentGraphStatistics($request, $response, $args){
        $general_student_graph_statistics = DB::select("
SELECT main_department.name as main_department,
sum(case when student.want_placement = 1 then 1 else 0 end) want_placement,
sum(case when student.company_id is not null and student.registered_company = 0 and student.want_placement = 1 then 1 else 0 end ) placed_by_college,
sum(case when student.company_id is null and student.want_placement = 1  then 1 else 0 end ) not_placed_by_college
from student
join sub_department
on student.sub_department_id = sub_department.id
join main_department
on main_department.id = sub_department.main_department_id
group by main_department.name");

        $data["data"] = $general_student_graph_statistics;

        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }


    public function getDepartmentalStudentGraphStatistics($request, $response, $args){

        /*changing the sub_department.name to sub_department means i have to
         * create new interfaces for them at the front end, 
         * time too no dey
         */ 

        $coordinator_id =  $_GET["coordinator_id"];
        $departmental_student_graph_statistics = DB::select( DB::raw("
SELECT sub_department.name as main_department,
sum(case when student.want_placement = 1 then 1 else 0 end) want_placement,
sum(case when student.company_id is not null and student.registered_company = 0 and student.want_placement = 1 then 1 else 0 end ) placed_by_college,
sum(case when student.company_id is null and student.want_placement = 1  then 1 else 0 end ) not_placed_by_college
from student
join sub_department
on student.sub_department_id = sub_department.id
join coordinator
on coordinator.user_id = sub_department.coordinator_id
join main_department
on main_department.id = sub_department.main_department_id
WHERE coordinator.user_id= :coordinator_id
group by sub_department.name"),array("coordinator_id"=>$coordinator_id));

        $data["data"] = $departmental_student_graph_statistics;

        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }


    public function getDepartmentalStudentStatistics($request, $response, $args){
        $form_input_data = $request->getParsedBody();
        if(isset($form_input_data["coordinator_id"])){
            
        $coordinator_id =  $form_input_data["coordinator_id"];

        $deparmental_student_statistics  = DB::select( DB::raw("
            SELECT sub_department.name sub_department ,sub_department.id as sub_department_id, main_department.name main_department,
count(*) registered,
sum(case when student.want_placement = 1 then 1 else 0 end) want_placement,
sum(case when student.company_id is not null and student.registered_company = 0 and student.want_placement = 1 then 1 else 0 end ) placed_by_college, 
sum(case when student.company_id is null and student.want_placement = 1 then 1 else 0 end ) not_placed_by_college, 
sum(case when student.rejected_placement = 1 then 1 else 0 end ) rejected_college_placement, 
sum(case when student.registered_company = 1 then 1 else 0 end ) searched_for_own_company 
from student 
join sub_department on student.sub_department_id = sub_department.id 
join main_department on main_department.id = sub_department.main_department_id join 
coordinator on coordinator.user_id = sub_department.coordinator_id
WHERE coordinator.user_id= :coordinator_id
group by sub_department.name"),array('coordinator_id' => $coordinator_id));

        $data["data"] = $deparmental_student_statistics;

        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

        }
        else{

        $deparmental_student_statistics  = DB::select( DB::raw("
            SELECT sub_department.name sub_department ,sub_department.id as sub_department_id, main_department.name main_department,
count(*) registered,
sum(case when student.want_placement = 1 then 1 else 0 end) want_placement,
sum(case when student.company_id is not null and student.registered_company = 0 and student.want_placement = 1 then 1 else 0 end ) placed_by_college, 
sum(case when student.company_id is null and student.want_placement = 1 then 1 else 0 end ) not_placed_by_college, 
sum(case when student.rejected_placement = 1  then 1 else 0 end ) rejected_college_placement, 
sum(case when student.registered_company = 1 then 1 else 0 end ) searched_for_own_company 
from student 
join sub_department on student.sub_department_id = sub_department.id 
join main_department on main_department.id = sub_department.main_department_id join 
coordinator on coordinator.user_id = sub_department.coordinator_id
group by sub_department.name
ORDER BY main_department.name"));

        $data["data"] = $deparmental_student_statistics;

        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

            
        }
        }


    public function getDepartmentalStudentsDetailsStatistics($request,$response,$args){
        $form_input_data = $request->getParsedBody();
        $coordinator_id = $form_input_data["coordinator_id"];
        $details_type = $form_input_data["details_type"];
        $sub_department_id = $form_input_data["sub_department_id"];

        switch($details_type){
        case 'registered':

            $requested_student_details = Student::where('sub_department_id', $sub_department_id)->orderBy('surname')->get();


        $fractal = new Manager();
        $fractal->setSerializer(new ArraySerializer);
        $resource = new Collection($requested_student_details ,new StudentTransformer());
        $data = $fractal->createData($resource)->toArray();


        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

        break;


        
        case 'want_placement':

            $requested_student_details = Student::where('sub_department_id', $sub_department_id)

                ->where('want_placement', '=', '1')
                ->orderBy('surname')->get();


        $fractal = new Manager();
        $fractal->setSerializer(new ArraySerializer);
        $resource = new Collection($requested_student_details ,new StudentTransformer());
        $data = $fractal->createData($resource)->toArray();


        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));


        
        break;


        case 'placed_by_college':

            $requested_student_details = Student::where('sub_department_id', $sub_department_id)

                ->where([['want_placement', '=', '1'],['company_id','<>',''],
                    ['registered_company','=','0']
                ])
                ->orderBy('surname')->get();



        $fractal = new Manager();
        $fractal->setSerializer(new ArraySerializer);
        $resource = new Collection($requested_student_details ,new StudentTransformer());
        $data = $fractal->createData($resource)->toArray();


        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));


        
        break;


        case 'not_placed_by_college':

            $requested_student_details = Student::where('sub_department_id', $sub_department_id)

                ->where([['want_placement', '=', '1']])
                ->whereNull('company_id')
                ->orderBy('surname')->get();



        $fractal = new Manager();
        $fractal->setSerializer(new ArraySerializer);
        $resource = new Collection($requested_student_details ,new StudentTransformer());
        $data = $fractal->createData($resource)->toArray();


        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));


        
        break;

        case 'rejected_college_placement':

            $requested_student_details = Student::where('sub_department_id', $sub_department_id)

                ->where([['rejected_placement','=','1']]) ->orderBy('surname')->get();



        $fractal = new Manager();
        $fractal->setSerializer(new ArraySerializer);
        $resource = new Collection($requested_student_details ,new StudentTransformer());
        $data = $fractal->createData($resource)->toArray();


        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));


        
        break;


        case 'searched_for_own_company':

            $requested_student_details = Student::where('sub_department_id', $sub_department_id)

                ->where('registered_company','=','1')->orderBy('surname')->get();


        $fractal = new Manager();
        $fractal->setSerializer(new ArraySerializer);
        $resource = new Collection($requested_student_details ,new StudentTransformer());
        $data = $fractal->createData($resource)->toArray();


        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));


        
        break;


        }
        
        
    }

    /* public function getGeneralRegionalStudentGraphStatistics($request, $response, $args){ */
        /* $regions = array("Ashanti Region,Brong Ahafo Region,Central,Eastern Region,Greater Accra Region,Northern Region,Upper East Region,Upper West Region,Volta Region,Western Region"); */

        /* forEach($regions as $region){ */
        
        /* } */
        


    /* } */
    }

