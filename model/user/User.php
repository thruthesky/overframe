<?php
namespace of;

use of\user\UserLayer;

class User extends UserLayer {


    public function hasAccess()
    {
        if ( $this->isAdmin() ) return true;
        else return false;
    }
}