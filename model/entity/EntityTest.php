<?php
namespace of\entity;
use of\Entity;

class EntityTest extends Entity
{

    public function run() {
        $this->test_entity_table_name();
        $this->test_entity_init();
        $this->test_crud();
        $this->test_select();
        $this->test_select_fields();
        $this->test_search();
        $this->test_query();
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

        $entity
            ->create()
            ->save();

        test( $entity->count() == 2, 'OK', 'ERROR');

        $entity->addColumn('name', 'varchar', 32);

        test( $entity->columnExists('name'), 'OK - Entity column added', 'ERROR - failed on adding entity column');

        ///
        $entity->set('name', 'jaeho')->save();
        $new_entity = entity($name)->load("name='jaeho'");
        test( $new_entity instanceof Entity, 'OK ', 'ERROR - entity inheritance');
        test( $new_entity->get('name') == 'jaeho', 'OK', 'ERROR - entity update error');
        test( $new_entity->get('id') == $entity->get('id'), 'OK', 'ERROR - entities are not the same');

        /// entity count
        entity($name)->set('name', 'eunsu jung')->save();
        test( $entity->count() == 3 );
        test(count(entity($name)->loadAll()) == 3);

        $nobody = entity($name)->set('name', 'nobody')->save();
        test( entity($name)->count() == 4 );

        /// entity delete
        $nobody->delete();
        test( entity($name)->count() == 3 );



        // entity instance check
        $re = true;
        foreach ( entity($name)->loadAll() as $obj ) {
            if ( $obj instanceof Entity ) {
            }
            else {
                $re = false;
                break;
            }
        }
        test ( $re );

        $entity->uninit();
    }

    private function test_select()
    {
        $table = 'test_select';

        $entity = entity($table);
        if ( $entity->exists() ) $entity->uninit();


        $entity
            ->init()
            ->addColumn('name', 'varchar', 32)
            ->addUniqueKey('name');

        $entity->create()->set('name', 'JaeHo Song')->save();
        $entity->create()->set('name', 'Eunsu Jung')->save();
        $entity->create()->set('name', 'Nobody')->save();
        $thruthesky = $entity->create()->set('name', 'thruthesky')->save();

        test( $entity->count() == 4 );
        test( count($entity->loadAll()) == 4 );

        test( count( $entity->loadQuery("name like '%j%'") ) == 2 );
        test( count( $entity->loadQuery("name like '%jung'") ) == 1 );

        $entities = $entity->loads( array( $thruthesky->get('id') ) );
        $ne = array_shift($entities);
        test( $thruthesky->get('id') == $ne->get('id') );

        $entity->uninit();

    }



    private function createDefaultTable()
    {
        $table = 'test_table';

        $entity = entity($table);
        if ( $entity->exists() ) $entity->uninit();

        $entity
            ->init()
            ->addColumn('name', 'varchar', 32)
            ->addUniqueKey('name')
            ->addColumn('address', 'varchar')
            ->addIndex('address');

        $entity->create()->set('name', 'JaeHo Song')->set('address', 'KimHae')->save();
        $entity->create()->set('name', 'Eunsu Jung')->set('address', 'KangWonDo')->save();
        $entity->create()->set('name', 'Jack')->set('address', 'United State, Toronto.')->save();
        $entity->create()->set('name', 'Joshua')->set('address', 'United State, Toronto.')->save();
        $entity->create()->set('name', 'Jeimy')->set('address', 'United State, Toronto.')->save();
        $entity->create()->set('name', 'Nobody')->set('address', 'No Where')->save();
        $entity->create()->set('name', 'thruthesky')->set('address', 'Internet')->save();

        return $entity;
    }




    private function test_select_fields()
    {
        $entity = $this->createDefaultTable();

        //
        $entitiles = $entity->loadQuery("name like '%j%'", 'id,address');
        $first = array_shift($entitiles);
        test( isset($first->getRecord()['address']) );
        test( ! isset($first->getRecord()['name']) );

        $entity->uninit();
    }

    private function test_search( )
    {
        $entity = $this->createDefaultTable();
        for( $i = 0; $i < 22; $i ++ ) {
            $entity
                ->create()
                ->sets( array('name'=>"name $i", 'address' => "address $i"))
                ->save();
        }

        $entities = $entity->search();
        test( count($entities) >= 22 );

        //
        $entities = $entity->search( array(
            'page' => 2,
            'limit' => 5,
        ));
        test( count($entities) == 5 );

        //
        $page = $entity->page(2, 5);
        test( count($page) == 5 );

        //
        $items = $entity->search( array('offset'=>5, 'limit'=>5) );
        $re = true;
        for ( $i = 0; $i < 5; $i ++ ) {
            $a = $entities[$i];
            $b = $page[$i];
            $c = $items[$i];
            if ( $a->get('id') == $b->get('id') && $a->get('id') == $c->get('id') ) {

            }
            else {
                $re = false;
                break;
            }
        }
        test( $re );

        // count with condition
        $where = "name like '%j%'";
        $entities = $entity->search ( array(
            'where' => $where,
            'page' => 2,
            'limit' => 2,
        ) );
        $cnt = $entity->count( $where );
        test( $cnt > count( $entities) );

        $entities = $entity->loadQuery($where);
        test( $cnt == count( $entities) );

        //
        $count_total = $entity->count();
        $count_entities = count($entities);
        $entity->deleteEntities( $entities);
        test( $entity->count() == ( $count_total - $count_entities ) );

        $entity->uninit();
    }

    private function test_query()
    {
        $entity = $this->createDefaultTable();

        $row = $entity->row("name like '%jung%'", 'id,name');
        $re = $entity->result('name', "name like '%jung%'");
        test( $row['name'] == $re );
        $entity->uninit();
    }
}