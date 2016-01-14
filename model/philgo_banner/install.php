<?php

/**
 * @return \of\Philgo_banner
 */
function banner() {
    return new \of\Philgo_banner();
}
function hook_philgo_banner_is_installed() {
    return banner()->exists();
}

function hook_philgo_banner_install() {
    banner()->install();
}

function hook_philgo_banner_uninstall() {
    banner()->uninstall();
}
