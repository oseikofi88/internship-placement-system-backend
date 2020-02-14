<?php

use Phinx\Migration\AbstractMigration;

class CreateSubDepartmentTable extends AbstractMigration
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
        $table = $this->table('sub_department');
        $table->addColumn('name','string',array('null'=> false,'limit'=>255))
            ->addColumn('coordinator_id','integer',array('null'=>true))
            ->addColumn('main_department_id','integer',array('null'=>false))
            ->addColumn('created_at','datetime')
            ->addColumn('updated_at','datetime')
            ->addIndex(array('name'), array('unique' => true))
            ->addForeignKey('coordinator_id','coordinator','user_id',array('delete' => 'NO_ACTION','update'=>'NO_ACTION'))
            ->addForeignKey('main_department_id','main_department','id',array('delete' => 'NO_ACTION','update'=>'NO_ACTION'))

            ->create();

    }

    }
