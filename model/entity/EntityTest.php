<?php
namespace of\entity;
use of\Entity;

class EntityTest extends Entity
{

    public function run() {
        $this->test_entity_table_name();
        $this->test_entity_init();
        $this->test_crud();
    }

    public function test_entity_table_name()
    {

        $name = 'abc';

        //
        $entity = new \of\Entity();
        test( $entity instanceof Entity, 'OK', 'ERROR');
        test( entity() instanceof Entity, 'OK', 'ERROR');

        //
        $entity->setTableName($name);
        test($entity->getTableName() == entity()->adjustTableName($name), 'OK', 'ERROR - table name adjusting failed');
        test( $entity->getTableName() == $this->adjustTableName($name), 'OK', 'ERROR');

        //
    }

    private function test_entity_init()
    {
        $name = 'test_entity_init';
        $entity = entity($name);
        if ( $entity->exists() ) $entity->uninit();
        $entity->init();
        test( $entity->exists(), 'OK', 'ERROR');
        $entity->uninit();

        test( $entity->exists() == FALSE, 'OK', 'ERROR');
    }

    private function test_crud()
    {
        $name = 'test_crud';
        $entity = entity($name);
        if ( $entity->exists() ) $entity->uninit();
        $entity
            ->init()
            ->create()
            ->save();

        test( $entity->count() == 1, 'OK', 'ERROR');

        $id = $entity->get('id');

        $new_entity = entity($name)->load($id);

        test( $new_entity->get('id') == $id, "OK", "ERROR - test_crud() - load failed");

        // from here... update / delete

    }


}