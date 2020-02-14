<?php

use Phinx\Migration\AbstractMigration;

class CreateStudentTable extends AbstractMigration
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
		$table = $this->table('student',array('id'=>false,'primary_key'=>array('user_id')));
	   	$table->addColumn('user_id','integer',array('null'=>false))
            ->addColumn('index_number','integer',array('null'=>false))
            ->addColumn('surname','string', array('null'=>false,'limit'=> 255))
            ->addColumn('other_names','string', array('null'=>false,'limit'=> 255))
            ->addColumn('sub_department_id','integer',array('null'=>false))
            ->addColumn('phone','string', array('null'=>false,'limit'=> 255))
            ->addColumn('email','string', array('null'=>false,'limit'=> 255))
            ->addColumn('foreign_student','boolean',array('null'=>true))
            ->addColumn('location_id','integer',array('null'=>false))
            ->addColumn('want_placement','boolean',array('null'=>true))
            ->addColumn('time_of_registration','datetime',array('null'=>false,)) //lol i know i can use the created at field but i want onw that i can have my own date format any change it anytime anyday
            ->addColumn('acceptance_letter_url','string',array('null'=>true))
            ->addColumn('picture_url','string',array('null'=>true))
            ->addColumn('time_of_starting_internship','datetime', array('null'=>true))
            ->addColumn('supervisor_name', 'string', array('null'=>true))
            ->addColumn('supervisor_contact','string',array('null'=>true))
            ->addColumn('supervisor_email','string',array('null'=>true))
            ->addColumn('registered_company','boolean',array('null'=>true)) //field will be true if the Student registered the company he was placed in himself
            ->addColumn('company_id','integer',array('null'=>true))
            ->addColumn('rejected_placement','boolean',array('null'=>true))
            ->addColumn('reason_for_rejection','string',array('null'=>true,'limit'=>2550))
            ->addColumn('created_at','datetime')
            ->addColumn('updated_at','datetime')
            ->addForeignKey('user_id','user','id',array('delete' => 'NO_ACTION','update'=>'NO_ACTION'))
            ->addForeignKey('company_id','company','user_id',array('delete' => 'NO_ACTION','update'=>'NO_ACTION'))
            ->addForeignKey('sub_department_id','sub_department','id',array('delete' => 'NO_ACTION','update'=>'NO_ACTION'))
            ->addForeignKey('location_id','location','id',array('delete' => 'NO_ACTION','update'=>'NO_ACTION'))
            ->addIndex('index_number', [ 'unique' => true,]) 
            ->create();



    }
}
