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
    }


    /**
     * This method executes a query which is 'write' type - create, insert, update, delete
     *
     * @attention this method return TRUE or FALSE depending on the query execution.
     *
     *      - if you need to get records, use query()
     *
     * @param $q
     * @return bool
     */
    public function exec($q) {
        sys()->log($q);
        if ( sys()->isCodeIgniter3() ) {
            return $this->db->query($q);
        }
        return FALSE;
    }


    /**
     * @param $q
     * @return bool|array
     *
     *      - it returns FALSE if it does not support the parent framework database.
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
        return FALSE;
    }

    /**
     * @param $str
     * @return mixed
     */
    public function quote($str) {
        if ( sys()->isCodeIgniter3() ) {
            return $this->db->escape($str);
        }
        else {
            return $str;
        }
    }

    public function insert_id() {
        if ( sys()->isCodeIgniter3() ) {
            return $this->db->insert_id();
        }
        else return FALSE;
    }
}
