<?php

use Phinx\Migration\AbstractMigration;

class CreateCompanyTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('company',array('id'=>false,'primary_key'=>array('user_id')));
        $table->addColumn('user_id','integer',array('null'=>false))
            ->addColumn('name','string',array('null'=>false,'limit'=> 255))
            ->addColumn('email','string',array('null'=>true,'limit'=> 255))
            ->addColumn('phone','string',array('null'=>true,'limit'=> 255))
            ->addColumn('location_id','integer',array('null'=>false))
            ->addColumn('postal_address','string',array('null'=>true,'limit'=> 255))
            ->addColumn('website','string',array('null'=>true,'limit'=> 255))
            ->addColumn('representative_name','string',array('null'=>true,'limit'=> 255))
            ->addColumn('representative_phone','string',array('null'=>true,'limit'=> 255))
            ->addColumn('representative_email','string',array('null'=>true,'limit'=> 255))
            ->addColumn('order_made','boolean',array('null'=>false,'default'=> false))
            ->addColumn('time_of_registration', 'datetime',['null'=>false])
         
            ->addForeignKey('location_id','location','id',array('delete' => 'NO_ACTION','update'=>'NO_ACTION'))
            ->addForeignKey('user_id','user','id',array('delete' => 'NO_ACTION','update'=>'NO_ACTION'))
            ->create();



    }
}
