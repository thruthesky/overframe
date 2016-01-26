<?php
namespace of;
class Philgo extends Node
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     *
     */
    public function install() {
        $attend = node('philgo_attend');
        $attend->init();
        $attend->addColumn('user_id', 'int');
        $attend->addColumn('date', 'int');
        $attend->addUniqueKey('user_id,date');

        $meta = meta('philgo');
        $meta->init();

    }

    public function uninstall()
    {
        $attend = node('philgo_attend');
        if ( $attend->exists() ) $attend->uninit();

        $meta = meta('philgo');
        if ( $meta->exists() ) $meta->uninit();
    }

    public function exists( $tablename = null )
    {
        $attend = node('philgo_attend');
        $tablename = $attend->getTableName();
        return parent::exists($tablename);
    }
}

