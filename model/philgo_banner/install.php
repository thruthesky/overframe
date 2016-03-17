<?php

function hook_philgo_banner_is_installed() {
    return banner()->exists();
}

function hook_philgo_banner_install() {
    banner()->install();
}

function hook_philgo_banner_uninstall() {
    banner()->uninstall();
}
