<?php
namespace of\meta;
use of\Meta;

class MetaTest extends Meta {
    public function run() {
        $this->test_init();
        $this->test_get_set();
    }

    private function test_init()
    {
        $this->setTableName('abc');
        if ( $this->exists() ) $this->uninit();
        $this->init();
        test( $this->exists(), 'OK', 'ERROR');
        $this->uninit();
    }

    private function test_get_set()
    {
        $meta = meta('def');
        if ( $meta->exists() ) $meta->uninit();
        $meta->init();


        $meta->set('a', 'b');
        test( $meta->get('a') == 'b', 'OK', 'ERROR');
        $meta->set('a', 'c');
        test( $meta->get('a') == 'c', 'OK', 'ERROR');

        $meta->set('name', 'jaeho');
        test( $meta->get('name') == 'jaeho', 'OK', 'ERROR');

        $meta->set('first name of jung', 'eunsu');
        test( $meta->get('first name of jung') == 'eunsu', 'OK', 'ERROR');


        test( $meta->get('a') == 'c', 'OK', 'ERROR');

        $meta->uninit();
    }


}