<?php

use Phinx\Seed\AbstractSeed;

class PlacementStatusSeeder extends AbstractSeed
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
        $data = 
            array('placement_done' => 0 ,'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s"));

        $placement_status= $this->table('placement_status');
        $placement_status->insert($data)
            ->save();

    }
}
