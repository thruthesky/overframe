<?php



spl_autoload_register( function( $class ) {
    if ( strpos( $class, 'of\\') !== false ) {
        $class_name = str_replace('of\\', '', $class);
        $lower = strtolower($class_name);
        include "model\\$lower\\$class_name.php";
    }
} );



