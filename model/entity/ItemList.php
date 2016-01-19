<?php

namespace of\entity;



class ItemList {


    private $db = null;

    public function __construct()
    {
        sys()->find();
        $this->db = database();
    }

    public function runAjax() {
        $in = http_input();
        $table = $in['table'];
        $in['rows'] = $this->db->rows("SELECT * FROM  $table");
        json_success($in);
    }

}