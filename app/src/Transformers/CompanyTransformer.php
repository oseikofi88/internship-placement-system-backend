<?php

namespace App\Transformers;

use App\Models\Company;
use App\Models\PlacementStatus;
use League\Fractal\TransformerAbstract;

class CompanyTransformer extends TransformerAbstract{

    public function transform(Company $company){
            $placement_status = PlacementStatus::first();
        return[
            'user_id'=>$company->user_id,
            'name'=> $company->name,
            'email'=> $company->email,
            'location'=> $company->location,
            'postal_address'=> $company->postal_address,
            'phone'=> $company->phone,
            'representative_name'=> $company->representative_name,
            'representative_phone'=> $company->representative_phone,
            'representative_email'=> $company->representative_email,
            'order_made'=>$company->order_made,
            'time_of_registration' => $company->time_of_registration
            ];

    }
}
