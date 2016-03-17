<?php



/**
 * @return \of\Philgo_banner
 */
function banner($id=0) {
    $b = new \of\Philgo_banner();
    if ( $id ) {
        $b->load($id);
    }
    return $b;
}

