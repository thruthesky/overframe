<?php
namespace of\philgo;
use of\Meta;

class PhilgoMeta extends Meta {

    public function __construct()
    {
        parent::__construct();
        $this->setTableName('philgo');
    }


}