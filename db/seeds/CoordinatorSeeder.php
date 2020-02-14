<?php

use Phinx\Seed\AbstractSeed;

class CoordinatorSeeder extends AbstractSeed
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
            array('user_id' => '2','email' =>'akowuahjoe@yahoo.co.uk'), 
            array('user_id' => '3','email' =>'afotey_benjamin@hotmail.com'), 
            array('user_id' => '4','email' =>'dayackom.coe@knust.edu.gh'), 
            array('user_id' => '5','email' =>'obengatuah@yahoo.co.uk'), 
            array('user_id' => '6','email' =>'kwame.agyekum@ymail.com'), 
            array('user_id' => '7','email' =>'adamsglobal@gmail.com'), 
            array('user_id' => '8','email' =>'ekarthur.coe@knust.edu.gh'), 
            array('user_id' => '9','email' =>'aaacheampong.coe@knust.edu.gh'), 
            array('user_id' => '10','email' =>'ssrgidigasu@yahoo.com'), 
            array('user_id' => '11','email' =>'eaowusu328@gmail.com'), 

        );

        $coordinator = $this->table('coordinator');
        $coordinator->insert($data)
            ->save();

    }
}
