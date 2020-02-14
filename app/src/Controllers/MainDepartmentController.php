<?php
namespace App\Controllers;

use \League\Fractal\Resource\Collection as Collection;
use \League\Fractal\Resource\Item;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Manager;
use App\Models\MainDepartment;
use App\Transformers\MainDepartmentTransformer;


final class MainDepartmentController{


    public function getAllMainDepartments($request, $response, $args){
        $main_departments = MainDepartment::all();
       

     $fractal = new Manager();
        $fractal->setSerializer(new ArraySerializer);
        $resource = new Collection($main_departments,new MainDepartmentTransformer());
        $data = $fractal->createData($resource)->toArray();
        
        
        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));


            
    }
}

