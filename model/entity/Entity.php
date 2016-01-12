<?php

namespace of;


class Entity {
    private $table;
    private $db = null;
    protected $record = [];

    public function __construct()
    {
        sys()->find();
        $this->db = database();
    }

    /**
     * @param $name
     * @return $this|Entity
     */
    public function setTableName($name) {
        $this->table = $this->adjustTableName($name);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTableName() {
        return $this->table;
    }

    final protected function adjustTableName($name) {
        return $name . '_entity';
    }


    /**
     * @return Entity|$this
     */
    public function init() {
        $name = $this->getTableName();
        $db = $this->db->createTable( $name );
        $db->addColumn($name, 'created', 'int unsigned');
        $db->addIndex($name, 'created');
        $db->addColumn($name, 'updated', 'int unsigned');
        $db->addIndex($name, 'updated');
        return $this;
    }

    public function uninit() {
        return $this->db->dropTable($this->getTableName());
    }

    public function exists() {
        return $this->db->tableExists( $this->getTableName() );
    }



    /**
     *
     *
     *
     * @note Use this method when you cannot use $this->set() directly for some inheritance reason.
     *
     * @param $field
     * @param $value
     * @return $this|Entity
     */
    public function set($field, $value) {
        $this->record[$field] = $value;
        return $this;
    }

    /**
     *
     * It gets assoc-array to set $this->record
     *
     * @param $fields
     * @return $this
     *
     * @code
        return data()->create()
            ->sets($record)
            ->set('name', $info['client_name'])
            ->save();
     * @endcode
     */
    public function sets($fields) {
        $this->record = array_merge($this->record, $fields);
        return $this;
    }


    /**
     * Returns the value of the field in the item record.
     *
     *
     * @param $field
     * @return mixed|bool
     *      - returns FALSE if the field is not set.
     */
    public function get($field) {
        if ( isset($this->record[$field]) ) {
            return $this->record[$field];
        }
        else {
            return FALSE;
        }
    }


    /**
     * @return Entity|$this
     */
    public function create() {
        return $this->reset();
    }

    /**
     * Resets the entity
     *
     * @return $this
     */
    private function reset() {
        $this->record = [];
        self::set('created', time());
        self::set('updated', 0);
        return $this;
    }


    /**
     *
     * Saves or Updates an item.
     *
     * @note if it has value in $this->get('id'), then it updates.
     * @note when it updates, it updates 'updated' field.
     *
     * @return Entity|boolean
     *
     *      - FALSE if $this->record is empty.
     *
     */
    public function save() {
        if ( empty($this->record) ) return FALSE;
        if ( $id = self::get('id') ) {
            self::set('updated', time());
            $this->db->update(
                $this->getTableName(),
                $this->record,
                "id=$id"
            );
        }
        else {
            $this->db->insert(
                $this->getTableName(),
                $this->record
            );
            $this->record['id'] = $this->db->insert_id();
        }
        return $this;
    }

    /**
     * @param null $cond - same as database->count()
     * @return mixed
     */
    public function count($cond=null)
    {
        return $this->db->count($this->getTableName(), $cond);
    }



    /**
     * Returns a new object of the Entity of input - $id
     *
     * @note it loads a record into $this->record and return the clone.
     *
     * @attention The calling object's $record is changed.
     *
     * @note if the input $id is 0, then it loads the entity of id with 0.
     *
     * @param $id
     * @return Entity|FALSE
     *      - if there is no record, then it returns FALSE
     * @code
     *      $this->load(1)                                 // load by id
     * @endcode
     */
    public function load($id) {
        $row = $this->db->row('SELECT * FROM ' . $this->getTableName() . " WHERE id=$id");
        $this->record = $row;
        if ( $this->record ) return clone $this;
        else return FALSE;
    }


    /**
     *
     * Returns an array of Entity object.
     *
     * @param $ids
     * @return array
     */
    public function loads($ids) {
        $these = array();
        foreach ($ids as $id) {
            $entity = clone $this;
            $entity->load($id);
            $these[] = $entity;
        }
        return $these;
    }


    public function addColumn( $field, $type, $size=0) {
        $this->db->addColumn( $this->getTableName(), $field, $type, $size);
    }

    public function columnExists($field_name)
    {
        return $this->db->columnExists($this->getTableName(), $field_name);
    }


}


