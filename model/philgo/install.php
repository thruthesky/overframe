<?php

function hook_philgo_is_installed() {
    return philgo()->exists();
}

function hook_philgo_install() {
    philgo()->install();
}

function hook_philgo_uninstall() {
    philgo()->uninstall();
}
