<?php

use Phinx\Seed\AbstractSeed;

class UserTypeSeeder extends AbstractSeed
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
                    array('name' => 'student','created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
                    array('name' => 'company','created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
                    array('name' => 'coordinator','created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
                    array('name' => 'admin','created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s"))
        );

        $user_types = $this->table('user_type');
        $user_types->insert($data)
                    ->save();

    }
}
