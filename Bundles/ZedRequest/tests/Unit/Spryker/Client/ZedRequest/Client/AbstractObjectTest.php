<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Client\ZedRequest\Client;

use Unit\Spryker\Client\ZedRequest\Client\Fixture\CommunicationObject;

/**
 * @group Communication
 */
class AbstractObjectTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testConstructorWithoutParameters()
    {
        $object = new CommunicationObject();

        $this->assertEquals([], $object->toArray());
    }

    /**
     * @return void
     */
    public function testConstructorWithParameters()
    {
        $data = ['test1' => 1];

        $object = new CommunicationObject($data);

        $this->assertEquals($data, $object->toArray());
    }

    /**
     * @return void
     */
    public function testFromArray()
    {
        $data = ['test1' => 1, 'test2' => 'hund'];

        $object = new CommunicationObject();
        $object->fromArray($data);

        $this->assertEquals($data, $object->toArray());
    }

    /**
     * @return void
     */
    public function testInvalidData()
    {
        $data = ['test6' => 4, 'test5' => 'data'];

        $object = new CommunicationObject();
        $object->fromArray($data);

        $this->assertEquals([], $object->toArray());
    }

    /**
     * @return void
     */
    public function testInvalidAndValidData()
    {
        $data = ['test6' => 4, 'test5' => 'data', 'test2' => 3];

        $object = new CommunicationObject();
        $object->fromArray($data);

        $this->assertEquals(['test2' => 3], $object->toArray());
    }

}
