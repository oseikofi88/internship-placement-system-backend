<?php

use Phinx\Seed\AbstractSeed;

class AdminSeeder extends AbstractSeed
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

        $username = 'vacationtraining.coe'; //short for vacation training officer
        $data = 
            array('user_id' => '1','username'=>$username);

        $user_types = $this->table('admin');
        $user_types->insert($data)
            ->save();

    }
}
