<?php

namespace App\Transformers;

use App\Models\Student;
use League\Fractal\TransformerAbstract;

class StudentTransformer extends TransformerAbstract{

    public function transform(Student $student){

            /**
             * Civil Department has 4 sub divisions not 4 sub departments
             * In specifying the deparment of the student in the letters,
             * we use the sub department names
             * For civil we use the sub divisions as sub departments
             * for the student to specify when registering
             * Therefore in the letters, divisions such as Highways and Transport
             * will be used as the student department which should not be the case 
             * Because they are not really sub department under civil but divisions.
             * Therefore we hard check to see if the main_department_name is civil,
             * Then we just  return civil as the instead of returning any of civil's 
             * sub departments which are actually sub divisions.

             * If you are the maintainer of this project, I'll advise you add a
             * divisions table to the database and link it to the sub
             * department  
             * ie one sub department can have many divisions.
             * With this you can just return the sub department name without
             * worrying if sub divisions have  been used for sub departments
             * I cant do it now because I'm time pressed.
             **/

            if(strtolower($student->sub_department->main_department->name) === "civil"){
			$department= 'Civil';
            }
            else{
			$department = $student->sub_department->name;
            }


        /** We are using the isset because
         * If we dont use isset on the company location
         * It is not retrievable  ie $student->company->location is always
         * null
         *But using an isset makes it retrievable
         *And I have no idea why
         **/

        if (isset($student->company->location)){

            return[
            'user_id' => $student->user_id,
            'index_number' => $student->index_number,
            'surname' => $student->surname,
            'other_names' => $student->other_names,
            'department' => $department,
            'phone'=>$student->phone,
            'email'=>$student->email,
            'want_placement'=>$student->want_placement,
            'foreign_student' =>$student->foreign_student,
            'time_of_registration' => $student->time_of_registration,
            'location' => $student->location,
            'company' => $student->company, 
            'registered_company'=>$student->registered_company,
            'supervisor_name' => $student->supervisor_name,
            'supervisor_contact' => $student->supervisor_contact,
            'supervisor_email' => $student->supervisor_email,
            'time_of_starting_internship'=>$student->time_of_starting_internship,
            'acceptance_letter_url'=> $student->acceptance_letter_url,
            'picture_url'=> $student->picture_url,
            'company_id' => $student->company_id,
            'rejected_placement' => $student->rejected_placement,
            'reason_for_rejection' => $student->reason_for_rejection,
            'created_at' => $student->created_at,
            'updated_at' => $student->updated_at
            ];


		}

		else{

            return[
            'user_id' => $student->user_id,
            'index_number' => $student->index_number,
            'surname' => $student->surname,
            'other_names' => $student->other_names,
			'department' => $department,
            'email'=>$student->email,
            'phone'=>$student->phone,
            'foreign_student' =>$student->foreign_student,
            'time_of_registration' => $student->time_of_registration,
            'location' => $student->location,
            'want_placement'=>$student->want_placement,
			'company' => $student->company,
            'supervisor_name' => $student->supervisor_name,
            'supervisor_contact' => $student->supervisor_contact,
            'supervisor_email' => $student->supervisor_email,
            'time_of_starting_internship'=>$student->time_of_starting_internship,
           'acceptance_letter_url'=> $student->acceptance_letter_url,
            'picture_url'=> $student->picture_url,
            'company_id' => $student->company_id,
            'registered_company'=>$student->registered_company,
            'rejected_placement' => $student->rejected_placement,
            'reason_for_rejection' => $student->reason_for_rejection,
            'created_at' => $student->created_at,
            'updated_at' => $student->updated_at
            ];


    }
	}
}
