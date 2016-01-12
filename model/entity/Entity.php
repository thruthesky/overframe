<?php

namespace of;


class Entity {
    private $table;
    protected $record = [];

    public function __construct()
    {
        system()->find();
    }

    /**
     * @param $name
     * @return $this|Entity
     */
    public function setTable($name) {
        $this->table = $name . '_entity';
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTable() {
        return $this->table;
    }

}


