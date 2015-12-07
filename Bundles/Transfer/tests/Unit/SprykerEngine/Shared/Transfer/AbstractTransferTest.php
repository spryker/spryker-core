<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Shared\Transfer;

use Unit\SprykerEngine\Shared\Transfer\Fixtures\AbstractTransfer;

/**
 * @group SprykerEngine
 * @group Shared
 * @group Transfer
 */
class AbstractTransferTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testFromArrayShouldReturnInstanceWithSetDefaultTypes()
    {
        $data = [
            'string' => 'string',
            'int' => 1,
            'bool' => true,
            'array' => [],
        ];

        $transfer = new AbstractTransfer();
        $transfer->fromArray($data);

        $this->assertSame('string', $transfer->getString());
        $this->assertSame(1, $transfer->getInt());
        $this->assertTrue($transfer->getBool());
        $this->assertInternalType('array', $transfer->getArray());
    }

    /**
     * @return void
     */
    public function testFromArrayShouldReturnInstanceWithSetTransferObject()
    {
        $data = [
            'transfer' => new AbstractTransfer(),
            'transferCollection' => [
                new AbstractTransfer(),
            ],
        ];

        $transfer = new AbstractTransfer();
        $transfer->fromArray($data);

        $this->assertInstanceOf('SprykerEngine\Shared\Transfer\TransferInterface', $transfer->getTransfer());
        $this->assertInstanceOf('\ArrayObject', $transfer->getTransferCollection());
        $this->assertCount(1, $transfer->getTransferCollection());
    }

    /**
     * @return void
     */
    public function testFromArrayShouldWorkForGivenTransferAndInnerTransfers()
    {
        $data = [
            'string' => 'foo',
            'int' => 1,
            'transfer' => [
                'string' => 'foo',
                'int' => 1,
            ],
        ];

        $transfer = new AbstractTransfer();
        $transfer->fromArray($data);

        $this->assertInstanceOf('SprykerEngine\Shared\Transfer\TransferInterface', $transfer->getTransfer());
    }

    /**
     * @return void
     */
    public function testFromArrayWithIgnoreMissingPropertyFalseShouldThrowExceptionIfPropertyIsInArrayButNotInObject()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $data = [
            'not existing property key' => '',
        ];

        $transfer = new AbstractTransfer();
        $transfer->fromArray($data);
    }

    /**
     * @return void
     */
    public function testFromArrayWithIgnoreMissingPropertyTrueShouldNotThrowExceptionIfPropertyIsInArrayButNotInObject()
    {
        $data = [
            'not existing property key' => '',
        ];

        $transfer = new AbstractTransfer();
        $transfer->fromArray($data, true);
    }

    /**
     * @return void
     */
    public function testFromArrayWithNestedTransferCollectionShouldReturnValidDataFromEmbeddedTransferObjects()
    {
        $data = [
            'string' => 'level1',
            'int' => 1,
            'transfer_collection' => [
                [
                    'string' => 'level2',
                    'int' => 1,
                ], [
                    'string' => 'level2',
                    'int' => 2,
                    'transfer_collection' => [
                        [
                            'string' => 'level3',
                            'int' => 1,
                        ], [
                            'string' => 'level3',
                            'int' => 2,
                        ],
                    ],
                ],
            ],
        ];

        $transfer = new AbstractTransfer();
        $transfer->fromArray($data);

        $this->assertEquals('level1', $transfer->getString());
        $this->assertEquals('level2', $transfer->getTransferCollection()[0]->getString());
        $this->assertEquals('level3', $transfer->getTransferCollection()[1]->getTransferCollection()[0]->getString());
    }

    /**
     * @return void
     */
    public function testToArrayShouldReturnArrayWithAllPropertyNamesAsKeysAndNullValuesWhenNoPropertyWasSet()
    {
        $transfer = new AbstractTransfer();
        $given = $transfer->toArray();
        $expected = [
            'string' => null,
            'int' => null,
            'bool' => null,
            'array' => new \ArrayObject(),
            'transfer' => null,
            'transfer_collection' => new \ArrayObject(),
        ];

        $this->assertEquals($expected, $given);
    }

    /**
     * @return void
     */
    public function testToArrayShouldReturnArrayWithAllPropertyNamesAsKeysAndFilledValues()
    {
        $transfer = new AbstractTransfer();
        $transfer->setString('foo');
        $transfer->setInt(2);

        $given = $transfer->toArray();
        $expected = [
            'string' => 'foo',
            'int' => 2,
            'bool' => null,
            'array' => new \ArrayObject(),
            'transfer' => null,
            'transfer_collection' => new \ArrayObject(),
        ];

        $this->assertEquals($expected, $given);
    }

    /**
     * @return void
     */
    public function testToArrayWithRecursiveTrueShouldReturnArrayWithAllPropertyNamesAsKeysAndFilledValuesAndRecursiveFilledInnerObjects()
    {
        $transfer = new AbstractTransfer();
        $transfer->setString('foo');
        $transfer->setInt(2);

        $innerTransfer = new AbstractTransfer();
        $innerTransfer->setString('bar');
        $innerTransfer->setInt(3);

        $transfer->setTransfer($innerTransfer);

        $given = $transfer->toArray();
        $expected = [
            'string' => 'foo',
            'int' => 2,
            'bool' => null,
            'array' => new \ArrayObject(),
            'transfer' => [
                'string' => 'bar',
                'int' => 3,
                'bool' => null,
                'array' => new \ArrayObject(),
                'transfer' => null,
                'transfer_collection' => new \ArrayObject(),
            ],
            'transfer_collection' => new \ArrayObject(),
        ];

        $this->assertEquals($expected, $given);
    }

    /**
     * @return void
     */
    public function testToArrayWithRecursiveFalseShouldReturnArrayWithAllPropertyNamesAsKeysAndWithoutRecursiveFilledInnerObjects()
    {
        $transfer = new AbstractTransfer();
        $transfer->setString('foo');
        $transfer->setInt(2);

        $innerTransfer = new AbstractTransfer();
        $innerTransfer->setString('bar');
        $innerTransfer->setInt(3);

        $transfer->setTransfer($innerTransfer);

        $given = $transfer->toArray(false);
        $expected = [
            'string' => 'foo',
            'int' => 2,
            'bool' => null,
            'array' => new \ArrayObject(),
            'transfer' => $innerTransfer->toArray(false),
            'transfer_collection' => new \ArrayObject(),
        ];

        $this->assertEquals($expected, $given);
    }

    /**
     * @return void
     */
    public function testModifiedToArrayShouldReturnArrayOnlyWithModifiedProperty()
    {
        $transfer = new AbstractTransfer();
        $transfer->setString('foo');
        $transfer->setInt(2);

        $given = $transfer->modifiedToArray();
        $expected = [
            'string' => 'foo',
            'int' => 2,
        ];

        $this->assertEquals($expected, $given);
    }

    /**
     * @return void
     */
    public function testModifiedToArrayWithRecursiveTrueShouldReturnArrayWithAllPropertyNamesAsKeysAndFilledValuesAndRecursiveFilledInnerObjectsWhichWhereModified()
    {
        $transfer = new AbstractTransfer();
        $transfer->setString('foo');
        $transfer->setInt(2);
        $transfer->setArray([]);

        $innerTransfer = new AbstractTransfer();
        $innerTransfer->setString('bar');
        $innerTransfer->setInt(3);

        $transfer->setTransfer($innerTransfer);

        $given = $transfer->modifiedToArray(true);
        $expected = [
            'string' => 'foo',
            'int' => 2,
            'array' => [],
            'transfer' => [
                'string' => 'bar',
                'int' => 3,
            ],
        ];

        $this->assertEquals($expected, $given);
    }

    /**
     * @return void
     */
    public function testSerializeAndUnSerializeShouldReturnUnSerializedInstance()
    {
        $transfer = new AbstractTransfer();
        $transfer->setString('foo');
        $transfer->setInt(2);

        $serialized = serialize($transfer);
        $unSerialized = unserialize($serialized);

        $given = $unSerialized->toArray();
        $expected = [
            'string' => 'foo',
            'int' => 2,
            'bool' => null,
            'array' => new \ArrayObject(),
            'transfer' => null,
            'transfer_collection' => new \ArrayObject(),
        ];

        $this->assertEquals($expected, $given);
    }

    /**
     * @return void
     */
    public function testCloneShouldReturnFullClonedObject()
    {
        $transfer = new AbstractTransfer();
        $transfer->setString('foo');
        $transfer->setInt(2);
        $transfer->setTransfer(new AbstractTransfer());

        $clonedTransfer = clone $transfer;

        $this->assertEquals($transfer, $clonedTransfer);
    }

    /**
     * @return void
     */
    public function testFromArrayShouldWorkWithCyclicReferences()
    {
        $transfer = new AbstractTransfer();

        $data = [
            'string' => 'foo',
            'transfer' => [
                'string' => 'bar',
                'transfer' => $transfer,
            ],
        ];

        $transfer->fromArray($data);

        $this->assertEquals('foo', $transfer->getString());
        $this->assertEquals('bar', $transfer->getTransfer()->getString());
        $this->assertEquals('foo', $transfer->getTransfer()->getTransfer()->getString());
        $this->assertEquals('bar', $transfer->getTransfer()->getTransfer()->getTransfer()->getString());
    }

}
