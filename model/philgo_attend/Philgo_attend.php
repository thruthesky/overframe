<?php
namespace of\philgo_attend;

use of\Node;


class Philgo_attend extends Node
{

    public function __construct()
    {
        parent::__construct();
        $this->setTableName('philgo_attend');
    }


    /**
     *
     */
    public function install() {
        $this->init();
        $this->addColumn('user_id', 'varchar', 64);
        $this->addColumn('date', 'int');
        $this->addUniqueKey('user_id,date');
    }

    public function uninstall()
    {
        $this->uninit();
    }


    // create attend record.
    public function attend() {

        $id = user()->getID();
        $date = date('Ymd');
        $entity = $this->load("user_id=$id AND date=$date");
        if ( $entity ) {
            json_error(-40445, "You have attended already on $date");
        }

        $entity = $this
            ->create()
            ->sets( array(
                'user_id' => $id,
                'date' => $date
            ) )
            ->save();
        if ( $entity ) {
            json_success( $entity->getRecord() );
        }
        else {
            json_error(-40041, "Failed to create entity");
        }




        // json_success(array());


    }


}

