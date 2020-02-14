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
use App\Transformers\CompanyTransformer;
use App\Transformers\StudentTransformer;
use App\Transformers\CompaniesSuggestionTransformer;
use \Firebase\JWT\JWT;
use Tuupola\Base62;
use Illuminate\Database\Capsule\Manager as DB;

final class CompanyStatisticsController 
{

    public function getGeneralCompanyStatistics($request, $response, $args){
        $general_company_statistics = DB::select("
SELECT sub_department.name as sub_department,main_department.name as main_department,count(*) as companies_registered,sum(company_sub_department.number_needed)  as total_number_needed
FROM company 
join company_sub_department
on company_sub_department.company_id = company.user_id
join sub_department
on company_sub_department.sub_department_id = sub_department.id
join main_department
on sub_department.main_department_id = main_department.id
WHERE company_sub_department.number_needed > 0
group by sub_department.name
ORDER BY `main_department` ASC
");

        $data["data"] = $general_company_statistics;

        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }

    
    public function getGeneralCompanyGraphStatistics($request, $response, $args){
        $general_company_graph_statistics = DB::select("
SELECT company.name as company_name,location.district as company_district,location.region as company_region,sum(company_sub_department.number_needed) as number_ordered_for ,
sum(company_sub_department.number_needed)*(100/(SELECT sum(company_sub_department.number_needed) from company_sub_department)) as percentage_taken
from company
join company_sub_department
on company.user_id = company_sub_department.company_id
join location
on company.location_id = location.id
WHERE company_sub_department.number_needed > 0
group by company.name  
ORDER BY number_ordered_for DESC
LIMIT 20");

        $data["data"] = $general_company_graph_statistics;

        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }

    public function getDepartmentalCompanyGraphStatistics($request, $response, $args){

        $coordinator_id =  $_GET["coordinator_id"];
        $general_company_graph_statistics = DB::select( DB::raw(" 
SELECT company.name as company_name,location.district as company_district,location.region as company_region,sum(company_sub_department.number_needed) as number_ordered_for ,
sum(company_sub_department.number_needed)*(100/(SELECT sum(company_sub_department.number_needed) from company_sub_department)) as percentage_taken
from company
join company_sub_department
on company.user_id = company_sub_department.company_id
join sub_department
on sub_department.id = company_sub_department.sub_department_id
join coordinator
on sub_department.coordinator_id = coordinator.user_id
join location 
on location.id = company.location_id
WHERE company_sub_department.number_needed > 0 and coordinator.user_id= :coordinator_id
group by company.name  
ORDER BY number_ordered_for DESC
LIMIT 10"),array("coordinator_id"=>$coordinator_id));

        $data["data"] = $general_company_graph_statistics;

        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }




    public function getDepartmentalCompanyStatistics($request, $response, $args){
        $form_input_data = $request->getParsedBody();

        if(isset($form_input_data["coordinator_id"])){
            

        $coordinator_id =  $form_input_data["coordinator_id"];
        $helperController = new HelperController(); 
        $coordinator_email = $helperController->getCoordinatorEmailFromId($coordinator_id);

        $coordinator_sub_departments = $helperController->getSubDepartmentsOfCoordinator($coordinator_email);

        $sub_departments = '';
        forEach($coordinator_sub_departments as $sub_department){
        $sub_departments.=$sub_department['id'];
        $sub_departments.=",";
        
        }
        $coordinator_sub_departments = substr($sub_departments,0,-1);

        /*what a strange looking query this is , isn't it?
         * don't worry i'll explain
         */ 


        $departmental_company_statistics  = DB::select( DB::raw("
SELECT company.name as company_name,location.district as company_district,location.region as company_region,company_sub_department.company_id as selected_company_id, SUM(company_sub_department.number_needed) as number_of_students_requested,(SELECT COUNT(student.index_number) from student WHERE student.company_id = selected_company_id and student.sub_department_id in (".$coordinator_sub_departments.")) as students_placed_in_company
from company_sub_department
join company
on company_sub_department.company_id = company.user_id
join sub_department
on sub_department.id = company_sub_department.sub_department_id
join coordinator
on coordinator.user_id = sub_department.coordinator_id
join location
on location.id = company.location_id
WHERE company_sub_department.number_needed > 0 and coordinator.email = :coordinator_email
group by company_sub_department.company_id
order by company.name"),array('coordinator_email' => $coordinator_email));
        

        $data["data"] = $departmental_company_statistics;

        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }
        else{
            
        $departmental_company_statistics  = DB::select( DB::raw("
SELECT company.name as company_name,location.district as company_district,location.region as company_region,company_sub_department.company_id as selected_company_id, SUM(company_sub_department.number_needed) as number_of_students_requested,(SELECT COUNT(student.index_number) from student WHERE student.company_id = selected_company_id) as students_placed_in_company
from company_sub_department
join company
on company_sub_department.company_id = company.user_id
join sub_department
on sub_department.id = company_sub_department.sub_department_id
join coordinator
on coordinator.user_id = sub_department.coordinator_id
join location
on location.id = company.location_id
WHERE company_sub_department.number_needed > 0 
group by company_sub_department.company_id
order by location.district,location.region,company.name"));
        

        $data["data"] = $departmental_company_statistics;

        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }

        }

public function    getDepartmentalCompanyDetailsStatitistics($request,$response, $args){
    
    $form_input_data = $request->getParsedBody();
    if(isset($form_input_data["coordinator_id"])){
        
        $coordinator_id = $form_input_data["coordinator_id"];
        $details_type = $form_input_data["details_type"];
        $company_id= $form_input_data["company_id"];
            

        $helperController = new HelperController(); 
        $coordinator_email = $helperController->getCoordinatorEmailFromId($coordinator_id);

        switch($details_type){
        case 'company_details':

        $company= Company::where('user_id',$company_id)->get();

        $fractal = new Manager();
        $fractal->setSerializer(new ArraySerializer);
        $resource = new Collection($company,new CompanyTransformer());
        $data = $fractal->createData($resource)->toArray();

        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

        break;

        case 'slots_given':
            $number_of_slots =  DB::select( DB::raw("
SELECT sub_department.name,company_sub_department.number_needed as number_needed
from company_sub_department
join company
on company_sub_department.company_id = company.user_id
join sub_department
on sub_department.id = company_sub_department.sub_department_id
join coordinator
on coordinator.user_id = sub_department.coordinator_id
WHERE coordinator.email =:coordinator_email  and company.user_id =:company_id 
group by sub_department.name")
,array('coordinator_email' =>$coordinator_email,'company_id' =>$company_id));


        $data["data"] = $number_of_slots;

        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

        break;

case 'students_placed_in_company':

    $helperController = new HelperController(); 
    $coordinator_sub_departments = $helperController->getSubDepartmentsOfCoordinator($coordinator_email);


   

    $students = Student::where('company_id',$company_id)
        ->whereIn('sub_department_id',$coordinator_sub_departments)
        ->orderBy('sub_department_id')
        ->get();


        $fractal = new Manager();
        $fractal->setSerializer(new ArraySerializer);
        $resource = new Collection($students,new StudentTransformer());
        $data = $fractal->createData($resource)->toArray();


        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));




    }
    }
    else{


        $details_type = $form_input_data["details_type"];
        $company_id= $form_input_data["company_id"];
     
        switch($details_type){
        case 'company_details':

        $company= Company::where('user_id',$company_id)->get();

        $fractal = new Manager();
        $fractal->setSerializer(new ArraySerializer);
        $resource = new Collection($company,new CompanyTransformer());
        $data = $fractal->createData($resource)->toArray();

        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

        break;

        case 'slots_given':
            $number_of_slots =  DB::select( DB::raw("
SELECT sub_department.name,company_sub_department.number_needed as number_needed
from company_sub_department
join company
on company_sub_department.company_id = company.user_id
join sub_department
on sub_department.id = company_sub_department.sub_department_id
WHERE company.user_id =:company_id 
group by sub_department.name")
,array('company_id' =>$company_id));


        $data["data"] = $number_of_slots;

        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

        break;

case 'students_placed_in_company':

    $students = Student::where('company_id',$company_id)
        ->orderBy('sub_department_id')
        ->get();


        $fractal = new Manager();
        $fractal->setSerializer(new ArraySerializer);
        $resource = new Collection($students,new StudentTransformer());
        $data = $fractal->createData($resource)->toArray();


        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));




    }
    
    }
}
}

