<?php

use Phinx\Seed\AbstractSeed;

class MainDepartmentSeeder extends AbstractSeed
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
            array('name' => 'Agricultural and Biosystems','created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
            array('name' => 'Chemical','created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
            array('name' => 'Computer','created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
            array('name' => 'Civil','created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
            array('name' => 'Electrical/Electronics','created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
            array('name' => 'Geological','created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
            array('name' => 'Geomatic','created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
            array('name' => 'Materials','created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
            array('name' => 'Mechanical','created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
            array('name' => 'Petroleum','created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
        );

        $user_types = $this->table('main_department');
        $user_types->insert($data)
            ->save();

    }
}
