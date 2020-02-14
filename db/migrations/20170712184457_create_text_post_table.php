<?php

use Phinx\Migration\AbstractMigration;

class CreateTextPostTable extends AbstractMigration
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
        $table = $this->table('text_post',array('id'=> false,'primary_key'=> array('post_id','post_type_id')));
        $table->addColumn('post_id','integer',array('null'=> false))
            ->addColumn('post_type_id','integer',array('null'=> false))
            ->addColumn('content','string')
            ->addColumn('created_at','datetime')
            ->addColumn('updated_at','datetime')
            ->addForeignKey('post_type_id','post_type','id',array('delete' => 'NO_ACTION','update'=>'NO_ACTION'))
            ->addForeignKey('post_id','post','id',array('delete' => 'NO_ACTION','update'=>'NO_ACTION'))

            ->create();

    }
}
