<?php
namespace App\Controllers;

use \League\Fractal\Resource\Collection as Collection;
use \League\Fractal\Resource\Item;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Manager;
use App\Models\SubDepartment;
use App\Transformers\SubDepartmentTransformer;

final class SubDepartmentController{
    public function getAllSubDepartments($request, $response, $args){
        $sub_departments = SubDepartment::all();


     $fractal = new Manager();
        $fractal->setSerializer(new ArraySerializer);
        $resource = new Collection($sub_departments,new SubDepartmentTransformer());
        $data = $fractal->createData($resource)->toArray();
        
        
        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data["data"], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));




}
}


