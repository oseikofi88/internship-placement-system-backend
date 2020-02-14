<?php

use Phinx\Migration\AbstractMigration;

class CreateRecipientTable extends AbstractMigration
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
        $table = $this->table('recipient');
            $table->addColumn('time_of_recieve','datetime')
            ->addColumn('message_id','integer',array('null'=>false))
        ->addColumn('group_id','integer',array('null'=>true))
        ->addColumn('user_id','integer',array('null'=>true))
        ->addColumn('read','boolean')
        ->addColumn('time_read','datetime',array('null'=>true))
        ->addColumn('delivered','boolean')
        ->addColumn('time_delivered','datetime',array('null'=>true))
        ->addForeignKey('message_id','message','id',array('delete' => 'NO_ACTION','update'=>'NO_ACTION'))
        ->addForeignKey('user_id','user','id',array('delete' => 'NO_ACTION','update'=>'NO_ACTION'))
        ->addForeignKey('group_id','group','id',array('delete' => 'NO_ACTION','update'=>'NO_ACTION'))

        ->addColumn('created_at','datetime')
        ->addColumn('updated_at','datetime')

        ->create();

    }
}
