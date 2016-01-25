<?php
namespace of\user;
use of\System;

class UserLayer
{



    public function getLogin() {
        if ( sys()->isSapcms1() ) {
            return login();
        }
        else return false;
    }


    public function isAdmin() {
        if ( sys()->isSapcms1() ) {
            return admin();
        }
        else return null;
    }

    public function getUsername() {
        if ( sys()->isSapcms1() ) {
            return login('id');
        }
        else return null;
    }

    public function getID() {
        if ( sys()->isSapcms1() ) {
            return login('idx');
        }
        else return null;
    }

    public function getName() {
        if ( sys()->isSapcms1() ) {
            return login('name');
        }
        else return null;
    }

    public function getEmail() {
        if ( sys()->isSapcms1() ) {
            return login('email');
        }
        else return null;
    }
}
