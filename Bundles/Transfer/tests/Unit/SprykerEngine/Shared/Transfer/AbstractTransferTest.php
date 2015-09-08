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

    public function testFromArrayShouldReturnInstanceWithSetDefaultTypes()
    {
        $data = [
            'string' => 'string',
            'integer' => 1,
            'bool' => true,
            'array' => [],
        ];

        $transfer = new AbstractTransfer();
        $transfer->fromArray($data);

        $this->assertSame('string', $transfer->getString());
        $this->assertSame(1, $transfer->getInteger());
        $this->assertTrue($transfer->getBool());
        $this->assertInternalType('array', $transfer->getArray());
    }

    public function testFromArrayShouldReturnInstanceWithSetTransferObject()
    {
        $data = [
            'transfer' => new AbstractTransfer(),
            'transferCollection' => new AbstractTransfer(),
        ];

        $transfer = new AbstractTransfer();
        $transfer->fromArray($data);

        $this->assertInstanceOf('SprykerEngine\Shared\Transfer\TransferInterface', $transfer->getTransfer());
        $this->assertInstanceOf('SprykerEngine\Shared\Transfer\TransferInterface', $transfer->getTransferCollection());
    }

    public function testFromArrayShouldOnlyWorkForGivenTransferNotForInnerTransfers()
    {
        $data = [
            'string' => 'foo',
            'integer' => 1,
            'transfer' => [
                'string' => 'foo',
                'integer' => 1,
            ],
        ];

        $transfer = new AbstractTransfer();
        $transfer->fromArray($data);

        $this->assertNull($transfer->getTransfer());
    }

    public function testFromArrayWithIgnoreMissingPropertyFalseShouldThrowExceptionIfPropertyIsInArrayButNotInObject()
    {
        $this->setExpectedException('\InvalidArgumentException');
        $data = [
            'not existing property key' => '',
        ];

        $transfer = new AbstractTransfer();
        $transfer->fromArray($data);
    }

    public function testFromArrayWithIgnoreMissingPropertyTrueShouldNotThrowExceptionIfPropertyIsInArrayButNotInObject()
    {
        $data = [
            'not existing property key' => '',
        ];

        $transfer = new AbstractTransfer();
        $transfer->fromArray($data, true);
    }
    public function testFromArrayWithNestedTransferCollectionShouldReturnValidDataFromEmbeddedTransferObjects()
    {
        $data = [
            'string' => 'level1',
            'integer' => 1,
            'transfer_collection' => [
                [
                    'string' => 'level2',
                    'integer' => 1,
                ], [
                    'string' => 'level2',
                    'integer' => 2,
                    'transfer_collection' => [
                        [
                            'string' => 'level3',
                            'integer' => 1,
                        ], [
                            'string' => 'level3',
                            'integer' => 2,
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

    public function testToArrayShouldReturnArrayWithAllPropertyNamesAsKeysAndNullValuesWhenNoPropertyWasSet()
    {
        $transfer = new AbstractTransfer();
        $given = $transfer->toArray();
        $expected = [
            'string' => null,
            'integer' => null,
            'bool' => null,
            'array' => null,
            'transfer' => null,
            'transfer_collection' => new \ArrayObject(),
        ];

        $this->assertEquals($expected, $given);
    }

    public function testToArrayShouldReturnArrayWithAllPropertyNamesAsKeysAndFilledValues()
    {
        $transfer = new AbstractTransfer();
        $transfer->setString('foo');
        $transfer->setInteger(2);

        $given = $transfer->toArray();
        $expected = [
            'string' => 'foo',
            'integer' => 2,
            'bool' => null,
            'array' => null,
            'transfer' => null,
            'transfer_collection' => new \ArrayObject(),
        ];

        $this->assertEquals($expected, $given);
    }

    public function testToArrayWithRecursiveTrueShouldReturnArrayWithAllPropertyNamesAsKeysAndFilledValuesAndRecursiveFilledInnerObjects()
    {
        $transfer = new AbstractTransfer();
        $transfer->setString('foo');
        $transfer->setInteger(2);

        $innerTransfer = new AbstractTransfer();
        $innerTransfer->setString('bar');
        $innerTransfer->setInteger(3);

        $transfer->setTransfer($innerTransfer);

        $given = $transfer->toArray();
        $expected = [
            'string' => 'foo',
            'integer' => 2,
            'bool' => null,
            'array' => null,
            'transfer' => [
                'string' => 'bar',
                'integer' => 3,
                'bool' => null,
                'array' => null,
                'transfer' => null,
                'transfer_collection' => new \ArrayObject(),
            ],
            'transfer_collection' => new \ArrayObject(),
        ];

        $this->assertEquals($expected, $given);
    }

    public function testToArrayWithRecursiveFalseShouldReturnArrayWithAllPropertyNamesAsKeysAndWithoutRecursiveFilledInnerObjects()
    {
        $transfer = new AbstractTransfer();
        $transfer->setString('foo');
        $transfer->setInteger(2);

        $innerTransfer = new AbstractTransfer();
        $innerTransfer->setString('bar');
        $innerTransfer->setInteger(3);

        $transfer->setTransfer($innerTransfer);

        $given = $transfer->toArray(false);
        $expected = [
            'string' => 'foo',
            'integer' => 2,
            'bool' => null,
            'array' => null,
            'transfer' => $innerTransfer->toArray(false),
            'transfer_collection' => new \ArrayObject(),
        ];

        $this->assertEquals($expected, $given);
    }

    public function testModifiedToArrayShouldReturnArrayOnlyWithModifiedProperty()
    {
        $transfer = new AbstractTransfer();
        $transfer->setString('foo');
        $transfer->setInteger(2);

        $given = $transfer->modifiedToArray();
        $expected = [
            'string' => 'foo',
            'integer' => 2,
        ];

        $this->assertEquals($expected, $given);
    }

    public function testModifiedToArrayWithRecursiveTrueShouldReturnArrayWithAllPropertyNamesAsKeysAndFilledValuesAndRecursiveFilledInnerObjectsWhichWhereModified()
    {
        $transfer = new AbstractTransfer();
        $transfer->setString('foo');
        $transfer->setInteger(2);
        $transfer->setArray([]);

        $innerTransfer = new AbstractTransfer();
        $innerTransfer->setString('bar');
        $innerTransfer->setInteger(3);

        $transfer->setTransfer($innerTransfer);

        $given = $transfer->modifiedToArray(true);
        $expected = [
            'string' => 'foo',
            'integer' => 2,
            'array' => [],
            'transfer' => [
                'string' => 'bar',
                'integer' => 3,
            ],
        ];

        $this->assertEquals($expected, $given);
    }

    public function testSerializeAndUnSerializeShouldReturnUnSerializedInstance()
    {
        $transfer = new AbstractTransfer();
        $transfer->setString('foo');
        $transfer->setInteger(2);

        $serialized = serialize($transfer);
        $unSerialized = unserialize($serialized);

        $given = $unSerialized->toArray();
        $expected = [
            'string' => 'foo',
            'integer' => 2,
            'bool' => null,
            'array' => null,
            'transfer' => null,
            'transfer_collection' => new \ArrayObject(),
        ];

        $this->assertEquals($expected, $given);
    }

    public function testCloneShouldReturnFullClonedObject()
    {
        $transfer = new AbstractTransfer();
        $transfer->setString('foo');
        $transfer->setInteger(2);
        $transfer->setTransfer(new AbstractTransfer());

        $clonedTransfer = clone $transfer;

        $this->assertEquals($transfer, $clonedTransfer);
    }

}
