<?php

use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
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
        $main_admin_password = password_hash('vto2017', PASSWORD_DEFAULT);
        //user id = 1;
        $data = 
            array(
            //main admin details;
            array('user_type_id' => '4','password' => $main_admin_password,'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),

            /**agric coordinator details
         * user_id = 2
             * name: Dr Akowuah 
             * email: akowuahjoe@yahoo.co.uk
             * password:akowuahjoe@
             */
            
        array('user_type_id' => '3','password' => password_hash('akowuahjoe@',PASSWORD_DEFAULT) ,'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),

        /**chemical/petrochem coordinator details
         * user_id = 3
             * name: Dr Affotey 
             * email: afotey_benjamin@hotmail.com
             * password:afotey_benjamin@
             */
        array('user_type_id' => '3','password' => password_hash('afotey_benjamin@',PASSWORD_DEFAULT) ,'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),

        /**computer/biomed coordinator details
         * user_id = 4
             * name: Dr Dorothy 
             * email:dayackom.coe@knust.edu.gh 
             * password:dayackom.coe@
             */
        array('user_type_id' => '3','password' => password_hash('dayackom.coe@',PASSWORD_DEFAULT) ,'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),


            /**civil (geotech,highways & transport, structures,water supply and
             * drainage)coordinator details
         * user_id = 5
             * name: Dr Obeng 
             * email:obengatuah@yahoo.co.uk 
             * password:obengatuah@
             */
        array('user_type_id' => '3','password' => password_hash('obengatuah@',PASSWORD_DEFAULT) ,'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),

            /**electrical/telecom coordinator details
         * user_id = 6
             * name: Mr Agyekum
             * email: kwame.agyekum@ymail.com
             *password:kwame.agyekum@
             */
            
        array('user_type_id' => '3','password' => password_hash('kwame.agyekum@',PASSWORD_DEFAULT) ,'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),

        /**aerospace/mechanical coordinator details
         * user_id = 7
             * name: Mr Adams 
             * email: adamsglobal@gmail.com
             *password:adamsglobal@
             */
            
        array('user_type_id' => '3','password' => password_hash('adamsglobal@',PASSWORD_DEFAULT) ,'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),

        /**materials/metallurgical coordinator details
         * user_id = 8
             * name: Dr Arthur 
             * email: ekarthur.coe@knust.edu.gh
             *password:ekarthur.coe@
             */
            
        array('user_type_id' => '3','password' => password_hash('ekarthur.coe@',PASSWORD_DEFAULT) ,'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),

        /**geomatic coordinator details
         * user_id = 9
             * name: Dr Afrifa 
             * email:aaacheampong.coe@knust.edu.gh 
             *password:aaacheampong.coe@
             */
            
        array('user_type_id' => '3','password' => password_hash('aaacheampong.coe@',PASSWORD_DEFAULT) ,'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),

            /**geological coordinator details
         * user_id = 10
             * name: Dr Gidigasu
             * email:ssrgidigasu@yahoo.com 
             *password:ssrgidigasu@
             */
            
        array('user_type_id' => '3','password' => password_hash('ssrgidigasu@',PASSWORD_DEFAULT) ,'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s")),

        /*petroleum coordinator detail
         * user_id = 11
         * name : EA Owusu
         * email : eaowusu328@gmail.com
         * password: eaowusu328@
         **/

        array('user_type_id' => '3','password' => password_hash('eaowusu328@',PASSWORD_DEFAULT) ,'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("d-m-Y H:i:s"))






            
        );
        $user_types = $this->table('user');
        $user_types->insert($data)
            ->save();

    }
}
