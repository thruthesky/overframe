<?php

function hook_user_is_installed() {
    return false;
}

function hook_user_install() {
    return true;
}


function hook_user_uninstall() {
    return true;
}
