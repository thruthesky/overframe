<?php

function hook_philgo_attend_is_installed() {
    return attend()->exists();
}

function hook_philgo_attend_install() {
    attend()->install();
}

function hook_philgo_attend_uninstall() {
    attend()->uninstall();
}
