<?php
namespace App\Transformers;

use App\Models\MainDepartment;
use League\Fractal\TransformerAbstract;

class MainDepartmentTransformer extends TransformerAbstract{

    public function transform(MainDepartment $main_department){
        return[
            'id' => $main_department->id,
            'name' => $main_department->name,
            'sub_departments' => $main_department->sub_departments
        ];
    }

}
