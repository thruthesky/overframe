<?php

function hook_data_is_installed() {
    return data()->exists();
}

function hook_data_install() {
    data()->install();
}

function hook_data_uninstall() {
    data()->uninstall();
}
