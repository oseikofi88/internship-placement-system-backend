<?php

use Phinx\Seed\AbstractSeed;

class SubDepartmentSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = array(
            array('name' => 'Agricultural','coordinator_id'=>'2','main_department_id' =>'1','created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
            array('name' => 'Chemical','coordinator_id'=>'3','main_department_id' =>'2', 'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
            array('name' => 'Petrochemical','coordinator_id'=>'3','main_department_id' =>'2', 'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
            array('name' => 'Biomedical','coordinator_id'=>'4','main_department_id' =>'3', 'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
            array('name' => 'Computer','coordinator_id'=>'4','main_department_id' =>'3', 'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
            array('name' => 'Geotech','coordinator_id'=>'5','main_department_id' =>'4', 'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
            array('name' => 'Highways & Transport','coordinator_id'=>'5','main_department_id' =>'4', 'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
            array('name' => 'Structures','coordinator_id'=>'5','main_department_id' =>'4', 'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
            array('name' => 'Water Supply & Drainage','coordinator_id'=>'5','main_department_id' =>'4', 'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
            array('name' => 'Waste Management','coordinator_id'=>'5','main_department_id' =>'4', 'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
            array('name' => 'Electrical','coordinator_id'=>'6','main_department_id' =>'5', 'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
            array('name' => 'Telecom','coordinator_id'=>'6','main_department_id' =>'5', 'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
            array('name' => 'Geological','coordinator_id'=>'10','main_department_id' =>'6', 'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
            array('name' => 'Geomatic','coordinator_id'=>'9','main_department_id' =>'7', 'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
            array('name' => 'Materials','coordinator_id'=>'8','main_department_id' =>'8', 'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
            array('name' => 'Metallurgical','coordinator_id'=>'8','main_department_id' =>'8', 'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
            array('name' => 'Aerospace','coordinator_id'=>'7','main_department_id' =>'9', 'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
            array('name' => 'Mechanical','coordinator_id'=>'7','main_department_id' =>'9', 'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
            array('name' => 'Petroleum','main_department_id' =>'10', 'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
        );
        $sub_department= $this->table('sub_department');
        $sub_department->insert($data)
            ->save();
    }
}
