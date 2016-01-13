<?php
namespace of;
use of\database\DatabaseLayer;

/**
 *
 *
 * @attention This class can instantiate the class as much as it can be.
 *
 *      - so the code must be prepared for multiple instantiation.
 *
 *
 */
class Database extends DatabaseLayer {


    public function __construct()
    {
        parent::__construct();
    }

    public function createTable($table_name) {
        $q = "CREATE TABLE `$table_name` ( `id` INT UNSIGNED NULL DEFAULT NULL AUTO_INCREMENT , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
        $re = $this->exec($q);
        if ( ! $re ) {
            $path = path_run();
            die("<hr>Database::createTable() : failed on create table $table_name. $path");
        }
        return $this;
    }
    public function dropTable($table_name) {
        $q = "DROP TABLE $table_name;";
        $re = $this->exec($q);
        if ( ! $re ) {
            $path = path_run();
            die("<hr>Database::createTable() : failed on create table $table_name. $path");
        }
        return $this;
    }



    /**
     * @param $table_name
     * @return bool
     *
     */
    public function tableExists($table_name) {
        $rows = $this->query("SHOW TABLES LIKE '$table_name'");
        return count($rows) > 0;
    }


    /**
     * @param $table_name
     * @param null $field_name
     * @return bool - TRUE if the field exists on the table
     *
     * @code
     *      Database::load()->columnExists('temp', 'idx')
     * @endcode
     */
    public function columnExists($table_name, $field_name=null) {
        $rows = $this->query("SHOW COLUMNS FROM `$table_name` LIKE '$field_name';");
        return count($rows) > 0;
    }


    /**
     * @param $table
     * @param array $keys_and_values
     * @return string
     *
     *
     */
    public function insert($table, array $keys_and_values)
    {
        $key_list = [];
        $value_list = [];
        foreach($keys_and_values as $k => $v ) {
            $key_list[] = "`$k`";

            if ( $v === NULL ) {
                $value_list[] = "NULL";
            }
            else {
                $value_list[] = $this->quote($v);
            }
        }
        $keys = implode(",", $key_list);
        $values = implode(",", $value_list);
        $q = "INSERT INTO `{$table}` ({$keys}) VALUES ({$values})";
        $re = $this->exec($q);
        if ( $re ) {
            $insert_id = $this->insert_id();
            return $insert_id;
        }
        return FALSE;
    }


    /**
     * @param $table_name
     * @param $fields
     * @param int $cond
     * @return bool
     */
    public function update($table_name, $fields, $cond=null)
    {
        $sets = [];
        foreach($fields as $k => $v) {
            $sets[] = "`$k`=" . $this->quote($v);
        }
        $set = implode(", ", $sets);
        $where = null;
        if ( $cond ) $where = "WHERE $cond";
        $q = "UPDATE $table_name SET $set $where";

        return $this->exec($q);
    }


    /**
     * @param $table_name
     * @param $cond
     * @return int
     */
    public function delete($table_name, $cond)
    {
        $q = "DELETE FROM $table_name WHERE $cond";
        return $this->exec($q);
    }







    public function count($table_name, $cond=null)
    {
        if ( $cond ) $where = "WHERE $cond";
        else $where = null;
        $q = "SELECT COUNT(*) AS cnt FROM $table_name $where";
        return $this->result($q);
    }

    public function rows($q) {
        return $this->query($q);
    }
    public function row($q) {
        $rows = $this->rows($q);
        if ( $rows ) return $rows[0];
        else return array();
    }
    public function result($q) {
        $row = $this->row($q);
        if ( $row ) {
            foreach( $row as $k=>$v ) {
                return $v;
            }
        }
        return FALSE;
    }

    /**
     * Adds a field to the table
     * @param $table_name
     * @param $field
     * @param $type
     * @param int $size
     * @return bool
     */
    public function addColumn( $table_name, $field, $type, $size=0) {

        if ( empty($size) ) {
            if ( $type == 'varchar' ) $size = 255;
            else if ( $type == 'char' ) $size = 1;
        }

        if ( $size ) $type = "$type($size)";
        $q = "ALTER TABLE `$table_name` ADD COLUMN `$field` $type";
        return $this->exec($q);

    }

    /**
     * @param $table_name
     * @param $field
     * @return $this|bool
     */
    public function deleteColumn($table_name, $field) {
        $q = "ALTER TABLE $table_name DROP $field;";
        return $this->exec($q);
    }


    public function addUniqueKey($table_name, $fields)
    {
        $q = "ALTER TABLE $table_name ADD UNIQUE KEY ($fields);";
        return $this->exec($q);
    }



    /**
     *
     *
     * @param $table_name
     * @param $fields
     * @return bool
     * @code
            $db->addColumn($name, 'created', 'int unsigned');
            $db->addIndex($name, 'created');
     * @endcode
     * @code indexing on two column
            $db->addIndex($name, 'created,updated');
     * @endcode
     *
     */
    public function addIndex($table_name, $fields) {
        $q = "ALTER TABLE $table_name ADD INDEX ($fields);";
        return $this->exec($q);
    }






}