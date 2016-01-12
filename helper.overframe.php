<?php

use of\Database;
use of\Entity;
use of\System;

/**
 * @note This method creates only one instance of System class.
 * @return System
 */
$global_sys = null;
function sys() {
    global $global_sys ;
    if ( $global_sys  === null ) $global_sys = new System();
    return $global_sys ;
}


/**
 * @param null $name - table name of the entity
 * @return Entity
 */
function entity($name=null) {
    if ( $name ) {
        $entity = new Entity();
        $entity->setTableName($name);
        return $entity;
    }
    else return new Entity();
}


function database() {
    return new Database();
}


function di($obj) {
    if ( empty($obj) ) return;
    echo "<pre>";
    print_r($obj);
    echo "</pre>";
}


function test( $code, $good=null, $bad=null ) {
    static $_count_test = 0;
    static $_error_test = array();
    $_count_test ++;
    if ( $code ) {
        echo "<span style='font-size:100%;color:#7f7c85;' title='$good'>$_count_test</span> ";
    }
    else {
        echo "<span style='font-size:160%;color:red;' title='$bad'>$_count_test</span> ";
        $_error_test[] = $bad;
    }

}