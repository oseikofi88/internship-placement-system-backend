<?php

namespace App\Transformers;

use App\Models\Company;
use League\Fractal\TransformerAbstract;

class CompaniesSuggestionTransformer extends TransformerAbstract{

    public function transform(Company $company){
        return[
            'name'=> $company->name,
            'location'=> $company->location,
            'students'=>$company->students,
            'phone'=> $company->phone,
            ];

    }
}
