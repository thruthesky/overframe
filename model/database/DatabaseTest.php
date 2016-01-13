<?php

namespace of\database;
class DatabaseTest {

    public function run() {
        $this->test_database_connection();
        $this->crudTable();
        $this->editTable();
        $this->crudRecord();
    }

    private function crudTable()
    {
        $name = 'test_table_crud';

        $db = $this->createTable($name);

        $re = $db->tableExists($name);
        test($re, "OK - $name exists.", "FAILURE - $name was not created.");
        $db->dropTable($name);
        $re = $db->tableExists($name);
        test($re == FALSE, "OK - $name does not exists", "ERROR - $name exists.");

    }

    private function editTable()
    {
        $name = 'test_edit_table';
        $db = $this->createTable($name);

        $db->addColumn($name, 'created', 'int unsigned');
        test( $db->columnExists($name, 'created'), 'OK - column exists.', 'ERROR - column does not exist.' );

        $db->addIndex($name, 'created');
        $db->addColumn($name, 'updated', 'int unsigned');
        $db->addUniqueKey($name, 'updated');
        $db->addColumn($name, 'name', 'varchar', 255);

        $db->dropTable($name);
    }

    private function crudRecord()
    {
        $name = 'test_record_crud';
        $db = $this->createTable($name);

        $db->insert($name, array('id' => 100));
        $id = $db->insert_id();
        test( $id == 100, "OK", "ERROR - id is not 100");


        $db->insert($name, array('id' => 200));
        $cnt = $db->count($name);
        test( $cnt == 2, "OK", "ERROR - no. of record is not 1. it's $cnt");

        //
        $cnt = $db->count($name, 'id=100');
        test( $cnt == 1, "OK", 'ERROR - no id=100');
        $db->update($name, array('id'=>111), 'id=100');

        $cnt = $db->count($name, 'id=100');
        test( $cnt == 0, "OK", 'ERROR - there is id=100');

        $cnt = $db->count($name, 'id=111');
        test( $cnt, "OK", 'ERROR - there is no id=111');

        //
        $db->insert($name, array('id'=>300));
        test($db->count($name) == 3, "OK", 'ERROR - the no of record is not 3');

        $db->delete($name, 'id=200');
        test($db->count($name) == 2, "OK", 'ERROR - the no of record is not 3');

        $db->dropTable($name);
    }

    private function createTable($name)
    {
        $db = database();
        $re = $db->tableExists($name);
        if ( $re ) $db->dropTable($name);
        $db->createTable($name);
        return $db;
    }

    private function test_database_connection()
    {
        $name = "test_database_connection";
        $db = database();
        test($db->getDatabaseObject());

        if ( $db->tableExists($name) ) $db->dropTable($name);
        $db->createTable($name);

        $ret_str = $db->quote('str');
        test( $ret_str == "'str'" );

        $db->dropTable($name);




    }

}
