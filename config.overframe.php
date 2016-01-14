<?php
define('DIR_OVERFRAME', __DIR__);

/*


if ( sys()->isSapcms1() ) {
    define('DIR_OVERFRAME_DATA', $sys->config->dir_data . '/overframe' );

    $domain = etc::domain_name();
    $url_overframe = "http://$domain/overframe";
    $url_overframe_data = "http://$domain/data/overframe";
    $url_overframe_ajax_endpoint = "http://$domain/?module=overframe&action=ajax&submit=1";
}
else if ( sys()->isCodeIgniter3() ) {
    $ci = & get_instance();
    $ci->load->library('url_helper');
    define('DIR_OVERFRAME_DATA', DIR_OVERFRAME . '/data');
    $url_overframe_data = '/data';
    $url_overframe = base_url('overframe');
}
else {
    echo "<h1>No framework</h1>";
    exit;
}
define('PATH_DEBUG_LOG', DIR_OVERFRAME_DATA. '/overframe.debug.log');

function url_overframe_data($path) {
    global $url_overframe_data;
    $url = $url_overframe_data . '/' . $path;
    //sys()->log("url_overframe_data($path) : $url");
    return $url;
}
function url_overframe() {
    global $url_overframe;
    return $url_overframe;
}

*/