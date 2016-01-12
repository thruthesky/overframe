<?php
include 'autoload.php';
class SystemTest extends PHPUnit_Framework_TestCase
{


    public function test_entity()
    {
        $abc = new \of\Entity();

        $abc->setTable('abc');

        $this->assertTrue($abc->getTable() == 'abc_entity');

    }
}