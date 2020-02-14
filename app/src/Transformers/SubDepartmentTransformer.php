<?php
namespace App\Transformers;

use App\Models\SubDepartment;
use League\Fractal\TransformerAbstract;

class SubDepartmentTransformer extends TransformerAbstract{

    public function transform(SubDepartment $sub_department){
        return[
            'id' => $sub_department->id,
            'name' => $sub_department->name,
            'coordinator' => $sub_department->coordinator,
            'main_department' => $sub_department->main_department //this will display the main department that the sub department belongs to 
        ];
    }

}
