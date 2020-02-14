<?php

use Phinx\Migration\AbstractMigration;

class CreateLocationTable extends AbstractMigration
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
        $table = $this->table('location');
        $table->addColumn('name','string',array('limit'=> 255,'null'=>false))
            ->addColumn('address','string',array('limit'=> 255,'null'=>false))
            ->addColumn('latitude','decimal',array('precision'=> 30,'scale'=> 28,'null'=>false))
            /* precision key is  for total number of values, scale key for decimal part of latitude */
            /* precision is 30 because of my  preference */ 
            /* ie, whole number part is 2 decimal part is 28 because +90 > latitude < -90 */
            ->addColumn('longitude','decimal',array('precision'=> 30,'scale'=> 27, 'null'=>false))
            /* precision key is  for total number of values, scale key for decimal part of longitude*/
            /* precision is 30 because of my  preference */ 
            /* ie, whole number part is 3 decimal part is 27 because +180 > longitude < -180 */
            ->addColumn('updated_by','integer',array('null'=> true))
            ->addColumn('created_at','datetime')
            ->addColumn('updated_at','datetime')

            ->addForeignKey('updated_by','user','id',array('delete' => 'NO_ACTION','update'=>'NO_ACTION'))
            ->create();



    }
}
