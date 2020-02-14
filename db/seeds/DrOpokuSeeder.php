<?php

use Phinx\Seed\AbstractSeed;

class DrOpokuSeeder extends AbstractSeed
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


        /* $new_data = array('user_type_id' => '3','password' => password_hash('dopoku.coe@',PASSWORD_DEFAULT) ,'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")); */

        /* $user_types = $this->table('user'); */
        /* $user_types->insert($new_data) */
        /*     ->save(); */



        $data = array(
            array('user_id' => '821','email' =>'dopoku.coe@knust.edu.gh'));

        $coordinator = $this->table('coordinator');
        $coordinator->insert($data)
            ->save();

        /*/1*petroleum coordinator detail */
        /* * user_id = 1053 */
        /* * name : Dr Opoku */
        /* * email :   dopoku.coe@knust.edu.gh */
        /* * password: dopoku.coe@ */


    }
}
