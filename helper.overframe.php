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


$global_test_error = array();
function runTest() {
    foreach ( sys()->getModels() as $model ) {
        $name = pathinfo($model, PATHINFO_BASENAME);
        $files = glob( $model . '/*est.php' );

        foreach( $files as $file ) {
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $path = "of\\$name\\$filename";
            $obj = new $path();
            if ( method_exists( $obj, 'run') ) $obj->run();
        }
    }
    show_test_result();
}
function test( $code, $good=null, $bad=null ) {
    static $_count_test = 0;
    global $global_test_error;
    $_count_test ++;
    $tree = ___generateCallTrace();
    if ( $code ) {

        $func = debug_backtrace()[1]['function'];
        $cls = debug_backtrace()[1]['class'];
        $file = debug_backtrace()[1]['file'];
        $line = debug_backtrace()[1]['line'];

        $path = "$file line $line - $cls::$func";

        echo "<span style='font-size:100%;color:#7f7c85;' title='$good $path'>$_count_test</span> ";
    }
    else {
        echo "<span style='font-size:160%;color:red;' title='$bad'>$_count_test</span> ";
        $global_test_error[] = $bad . $tree;
    }
}
function show_test_result() {
    global $global_test_error;
    echo "<hr>Test ended.";
    foreach ( $global_test_error as $e ) {
        echo '<hr>';
        $e = nl2br($e);
        echo $e;
    }
}
function ___generateCallTrace()
{
    $e = new Exception();
    $trace = explode("\n", $e->getTraceAsString());
    // reverse array to make steps line up chronologically
    $trace = array_reverse($trace);
    array_shift($trace); // remove {main}
    array_pop($trace); // remove call to this method
    $length = count($trace);
    $result = array();

    for ($i = 0; $i < $length; $i++)
    {
        $result[] = ($i + 1)  . ')' . substr($trace[$i], strpos($trace[$i], ' ')); // replace '#someNum' with '$i)', set the right ordering
    }

    return "\n\t" . implode("\n\t", $result);
}


/**
 * Returns page no.
 * @note page no. begins with 1.
 *  if the input is not a number or less than 1, then it returns 1.
 *
 * @attention Since the return 'page no' is 1 on first page, you must do ('page_no' -1 ) for retrieving data form table.
 *      page_no 가 1 부터 시작하므로, 테이블에서 데이터를 처음 부터 추출하기 위해서는 offset 이 '0' 이어야 하므로 'page_no' - 1 을 해야 한다.
 *
 * @param $no
 * @return int|string
 */
function page_no($no) {
    if ( ! is_numeric($no) ) return 1;
    else if ( $no < 1 ) return 1;
    else return $no;
}