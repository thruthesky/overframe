<?php
namespace of;

class User extends Node {

    public function __construct()
    {
        parent::__construct();
        $this->setTable('user');
    }





}