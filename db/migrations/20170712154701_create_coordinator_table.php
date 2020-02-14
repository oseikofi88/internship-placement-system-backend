<?php

use Phinx\Migration\AbstractMigration;

class CreateCoordinatorTable extends AbstractMigration
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
        $table = $this->table('coordinator',array('id'=>false,'primary_key'=>array('user_id')));
        $table->addColumn('user_id','integer',array('null'=>false))
            ->addColumn('email','string',array('limit' => 255,'null'=>false))
            ->addForeignKey('user_id','user','id',array('delete' => 'NO_ACTION','update'=>'NO_ACTION'))
           ->create();


    }
}
