<?php

use Phinx\Seed\AbstractSeed;

class LocationSeeder extends AbstractSeed
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
            array('name' => 'Kronom','address'=> '221b Bakers Street', 'longitude'=> '1530','latitude'=>'574554','created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),
        );

        $user_types = $this->table('location');
        $user_types->insert($data)
            ->save();
    }
}
