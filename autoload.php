<?php

spl_autoload_register( function( $class ) {
    if ( strpos( $class, 'of\\') !== false ) {
        $class_name = str_replace('of\\', '', $class);

        if ( strpos( $class_name, '\\') !== false ) {
            $arr = explode('\\', $class_name);
            $model_name = strtolower($arr[0]);
            $class_name = $arr[1];
        }
        else {
            $model_name = strtolower($class_name);
        }
        include "model\\$model_name\\$class_name.php";
    }
} );

include 'helper.overframe.php';
include 'config.overframe.php';

sys()->log("++ ------------- New connection begins ...");
sys()->setModelPaths( glob(DIR_OVERFRAME . "/model/*", GLOB_ONLYDIR) );
