<?php

use Phinx\Migration\AbstractMigration;

class CreateCompanySubDepartmentTable extends AbstractMigration
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
        $table = $this->table('company_sub_department');
        $table->addColumn('company_id','integer',array('null'=> false))
        ->addColumn('sub_department_id','integer',array('null'=> false))
        ->addColumn('number_needed','integer',['default' => 0])
            ->addColumn('created_at','datetime')
            ->addColumn('updated_at','datetime')
            ->addForeignKey('sub_department_id','sub_department','id',array('delete' => 'NO_ACTION','update'=>'NO_ACTION'))
            ->addForeignKey('company_id','company','user_id',array('delete' => 'NO_ACTION','update'=>'NO_ACTION')) 
        
            ->create();

    }
}
