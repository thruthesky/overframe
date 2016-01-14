<?php

use of\Ajax;
use of\Data;
use of\Database;
use of\Entity;
use of\System;


$global_sys = null;
/**
 * @note This method creates only one instance of System class.
 * @return System
 */
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

/**
 * @param int $id
 * @return Data
 */
function data( $id = 0 ) {
    if ( empty($id) ) return new Data();
    else {
        $data = new Data();
        return $data->load($id);
    }
}


function ajax() {
    return new Ajax();
}

function database() {
    return new Database();
}


if ( ! function_exists('di') ) {
    function di($obj) {
        if ( empty($obj) ) return;
        echo "<pre>";
        print_r($obj);
        echo "</pre>";
    }
}



$global_test_error = array();
function run_test() {
    echo "<h2>Begin test</h2>";
    echo "<hr>";
    foreach ( sys()->getModelPaths() as $model ) {
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
    $tree = get_backtrace();
    if ( $code ) {
        $path = path_run(2);
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
function get_backtrace()
{
    ob_start();
    $arr = debug_print_backtrace();
    $str = ob_get_clean();
    return $str;
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


function path_run($n = 1) {

    $func = debug_backtrace()[$n]['function'];
    $cls = debug_backtrace()[$n]['class'];
    $file = debug_backtrace()[$n]['file'];
    $line = debug_backtrace()[$n]['line'];

    return "$file at line $line - $cls::$func";
}



/**
 * @short returns a UNIQUE ID
 *
 */
function unique_id()
{
    return md5(uniqid(rand(), true) . time());
}


$_http_input = array();
/**
 * @param null $name
 * @return array
 * @code
 *  $in = http_input();
 * @endcode
 */
function http_input($name = null) {
    global $_http_input;
    if ( empty($_http_input) )$_http_input = array_merge($_GET, $_POST);
    if ( $name ) {
        if ( isset( $_http_input[$name] ) ) return $_http_input[$name];
        else return null;
    }
    return $_http_input;
}


function json_error($code, $message) {
    $in = http_input();
    echo json_encode( array('code'=>$code, 'message'=>$message, 'system'=>sys()->find(), 'do'=>$in['do']) );
    exit;
}

function json_success($data) {
    $in = http_input();
    echo json_encode( array('code'=>0, 'system'=>sys()->find(), 'do'=>$in['do'], 'data'=>$data) );
    exit;
}


function dir_overframe() {
    return DIR_OVERFRAME;
}


function dir_overframe_data()
{
    if ( sys()->isSapcms1() ) {
        global $sys;
        return $sys->config->dir_data . '/overframe';
    }
    else if ( sys()->isCodeIgniter3() ) {
        return DIR_OVERFRAME . '/data';
    }
    else return null;
}



function url_overframe() {
    if ( sys()->isSapcms1() ) {
        $domain = etc::domain_name();
        $url_overframe = "http://$domain/overframe";
    }
    else if ( sys()->isCodeIgniter3() ) {
        $ci = & get_instance();
        $ci->load->helper('url');
        $url_overframe = base_url() . '/overframe';
    }
    else $url_overframe = null;
    return $url_overframe;
}


function url_root() {
    if ( sys()->isSapcms1() ) return '/';
    else if ( sys()->isCodeIgniter3() ) {
        $ci = & get_instance();
        $ci->load->helper('url');
        return base_url();
    }
    else return null;
}

/**
 *
 * Url for form submission and data retribution.
 */
function url_action() {

    if ( sys()->isSapcms1() ) {
        $domain = etc::domain_name();
        return "http://$domain/?module=overframe&action=index";
    }
    if ( sys()->isCodeIgniter3() ) {
        return url_root() . '?action=index';
    }
    else return null;

}

function url_overframe_data($filename=null) {

    if ( sys()->isSapcms1() ) {
        $domain = etc::domain_name();
        return "http://$domain/data/overframe/$filename";
    }
    else if ( sys()->isCodeIgniter3() ) {
        $ci = & get_instance();
        $ci->load->helper('url');
        return base_url('overframe/data') . '/' . $filename;
    }
    else return null;
}


function ajax_endpoint() {
    if ( sys()->isSapcms1() ) {
        $domain = etc::domain_name();
        return "http://$domain/?module=overframe&action=index&mode=ajax&submit=1";
    }
    if ( sys()->isCodeIgniter3() ) {
        return url_root() . '?action=ajax';
    }
    else return null;
}



function path_debug_log()
{
    return dir_overframe_data() . '/overframe.debug.log';
}
