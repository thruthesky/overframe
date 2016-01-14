<?php
namespace of\database;
use of\System;

class DatabaseLayer
{

    private $db = null;

    public function __construct()
    {
        if ( sys()->isCodeIgniter3() ) {
            $ci = &get_instance();
            $ci->load->database();
            $this->db = & $ci->db;
        }
        else if ( sys()->isSapcms1() ) {
            global $sys;
            $this->db = $sys->db;
        }
    }

    public function getDatabaseObject() {
        return $this->db;
    }

    /**
     * This method executes a query which is 'write' type - create, insert, update, delete
     *
     * @attention this method return TRUE or FALSE depending on the query execution.
     *
     *      - if you need to get records, use query()
     *
     * @attention Do not use this methods on
     *
     *      SELECT, SHOW, DESCRIBE or EXPLAIN queries
     *
     * @param $q
     * @return boolean
     *
     *      - Returns FALSE on failure.
     *
     *      - Return TRUE on success.
     *
     */
    public function exec($q) {
        sys()->log($q);
        if ( sys()->isCodeIgniter3() ) {
            return $this->db->query($q);
        }
        else if ( sys()->isSapcms1() ) {
            return $this->db->query($q);
        }

        $path = path_run(2);
        die("<hr>DatabaseLayer::exec()<hr>No framework support. No database connection.<hr>$path");
    }


    /**
     * @param $q
     * @return bool | array
     *
     *      - if it has empty table, it returns empty array()
     *      - if it has records, it returns in assoc-array.
     *
     * @code
    $rows = $db->query("SELECT id FROM abc");
    print_r($rows);
     * @endcode
     *
     */
    public function query($q) {
        sys()->log($q);
        if ( sys()->isCodeIgniter3() ) {
            $query = $this->db->query($q);
            return $query->result_array();
        }
        else if ( sys()->isSapcms1() ) {
            return $this->db->rows($q);
        }
        $path = path_run(2);
        die("DatabaseLayer::query()<hr>No framework support. No database connection.<hr>$path");
    }

    /**
     * @param $str
     * @return mixed
     */
    public function quote($str) {
        if ( sys()->isCodeIgniter3() ) {
            return $this->db->escape($str);
        }
        else if ( sys()->isSapcms1() ) {
            return "'" . $this->db->addquotes($str) . "'";
        }
        $path = path_run(2);
        die("DatabaseLayer::quote()<hr>No framework support. No database connection.<hr>$path");

    }

    public function insert_id() {
        if ( sys()->isCodeIgniter3() ) {
            return $this->db->insert_id();
        }
        else if ( sys()->isSapcms1() ) {
            return $this->db->insert_id;
        }
        $path = path_run(2);
        die("DatabaseLayer::insert_id()<hr>No framework support. No database connection.<hr>$path");
    }


    public function getErrorString()
    {
        if ( sys()->isSapcms1() ) {
            $str = $this->db->error;
            $str .= "<hr>";
            $str .= get_backtrace();
            return $str;
        }
        else {
            return $this->db->error;
        }
    }


}
