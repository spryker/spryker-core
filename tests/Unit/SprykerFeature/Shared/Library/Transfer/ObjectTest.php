<?php

namespace Unit\SprykerFeature\Shared\Library\Transfer;

use Unit\SprykerFeature\Shared\Library\Fixtures\KernelLocator;

/**
 * @group Transfer
 */
class ObjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @group Transfer:modifiedToArray
     */
    public function testModifiedToArrayShouldReturnEmptyArrayWhenNothingChangedInTransfer()
    {
        $locator = KernelLocator::getInstance();
        $transfer = $locator->system()->transferTestMain();
        $data = $transfer->modifiedToArray();
        $this->assertCount(0, $data);
    }

    /**
     * @group Transfer:modifiedToArray
     */
    public function testModifiedToArrayShouldReturnModifiedValue()
    {
        $locator = KernelLocator::getInstance();
        $transfer = $locator->system()->transferTestMain();
        $transfer->setFoo('foo');
        $data = $transfer->modifiedToArray();
        $this->assertCount(1, $data);
        $this->assertSame('foo', $data['foo']);
    }

    /**
     * @group Transfer:modifiedToArray
     */
    public function testModifiedToArrayShouldReturnModifiedValueWithDashedKeys()
    {
        $locator = KernelLocator::getInstance();
        $transfer = $locator->system()->transferTestMain();
        $transfer->setCamelCased('camelCased');
        $data = $transfer->modifiedToArray();
        $this->assertCount(1, $data);
        $this->assertTrue(array_key_exists('camel_cased', $data));
    }

    /**
     * @group Transfer:modifiedToArray
     */
    public function testModifiedToArrayShouldReturnModifiedValueWithCamelCasedKeys()
    {
        $locator = KernelLocator::getInstance();
        $transfer = $locator->system()->transferTestMain();
        $transfer->setCamelCased('camelCased');
        $data = $transfer->modifiedToArray(true, false);
        $this->assertCount(1, $data);
        $this->assertTrue(array_key_exists('camelCased', $data));
    }

    /**
     * @group Transfer:modifiedToArray
     */
    public function testModifiedToArrayShouldAlsoReturnModifiedChildElements()
    {
        $locator = KernelLocator::getInstance();
        $transfer = $locator->system()->transferTestMain();
        $transfer->setFoo('foo');
        $childTransfer = $locator->system()->transferTestChild();
        $childTransfer->setBat('bat');
        $transfer->setChildTransfer($childTransfer);
        $data = $transfer->modifiedToArray();
        $this->assertCount(2, $data);
        $this->assertSame('foo', $data['foo']);
        $this->assertTrue(is_array($data['child_transfer']));
    }

    /**
     * @group Transfer:modifiedToArray
     */
    public function testModifiedToArrayShouldAlsoReturnModifiedChildElementsIfRecursiveIsSetToFalseAsObject()
    {
        $locator = KernelLocator::getInstance();
        $transfer = $locator->system()->transferTestMain();
        $transfer->setFoo('foo');
        $childTransfer = $locator->system()->transferTestChild();
        $childTransfer->setBat('bat');
        $transfer->setChildTransfer($childTransfer);
        $data = $transfer->modifiedToArray(false);
        $this->assertCount(2, $data);
        $this->assertSame('foo', $data['foo']);
        $this->assertInstanceOf(
            'SprykerFeature\Shared\System\Transfer\Test\Child',
            $data['child_transfer']
        );
    }

    /**
     * @group Transfer:isEmpty
     */
    public function testIsEmptyShouldReturnTrueWhenNoValueWasSet()
    {
        $locator = KernelLocator::getInstance();
        $transfer = $locator->system()->transferTestMain();
        $this->assertTrue($transfer->isEmpty());
    }

    /**
     * @group Transfer:isEmpty
     */
    public function testIsEmptyShouldReturnFalseWhenValueWasSet()
    {
        $locator = KernelLocator::getInstance();
        $transfer = $locator->system()->transferTestMain();
        $transfer->setFoo('bar');
        $this->assertFalse($transfer->isEmpty());
    }

    /**
     * @group Transfer:constructor
     */
    public function testConstructorIsSameAsFromArray()
    {
        $locator = KernelLocator::getInstance();
        $transfer = $locator->system()->transferTestMain();
        $transfer->fromArray(['bar' => 1]);

        $data = $transfer->modifiedToArray();
        $this->assertEquals(['bar' => 1], $data);

        $array = $transfer->toArray();
        $this->assertEquals(['bar'=>1, 'foo'=>null, 'camel_cased'=>null, 'child_transfer'=>['baz'=>null, 'bat'=>null, 'collection'=>[]], 'interface_child' => null, 'interface_child_class_name' => null], $array);
    }

    public function testCloningTransferClonesDeep()
    {
        $locator = KernelLocator::getInstance();
        $transfer = $locator->system()->transferTestMain();
        $clonedTransfer = clone $transfer;

        $this->assertNotSame($transfer, $clonedTransfer);
        $this->assertNotSame($transfer->getChildTransfer(), $clonedTransfer->getChildTransfer());
    }

    public function testCloningTransferCollectionClonesDeep()
    {
        $locator = KernelLocator::getInstance();
        $transfer = $locator->system()->transferTestMain();
        $collection = $locator->system()->transferTestChildCollection();
        $collection->add($locator->system()->transferTestChild());

        $clonedCollection = clone $collection;

        $this->assertNotSame($collection, $clonedCollection);
        $this->assertNotSame($collection->getFirstItem(), $clonedCollection->getFirstItem());
    }

    public function testToArrayOnlyReturnsPropertiesAndClassNames()
    {
        $locator = KernelLocator::getInstance();
        $this->markTestSkipped();
        $transfer = $locator->system()->transferTestMain();
        $transfer->setInterfaceChild($locator->system()->transferTestInterfaceChild()->fromArray(['bub' => 1]));

        $this->assertEquals(['interface_child' => ['bub' => 1], 'interface_child_class_name' => 'SprykerFeature\Shared\System\Transfer\Test\InterfaceChild', 'child_transfer' => ['collection' => []]], $transfer->toArray(false));
    }

    public function testToArrayIsAcceptedByFromArray()
    {
        $locator = KernelLocator::getInstance();
        $this->markTestSkipped();
        $transfer = $locator->system()->transferTestMain();
        $transfer->setInterfaceChild($locator->system()->transferTestInterfaceChild()->fromArray(['bub' => 1]));

        $transfer2 = (new \SprykerEngine\Shared\Kernel\TransferLocator())->locateSystemTestMain($transfer->toArray(false));

        $this->assertEquals($transfer2->toArray(false), $transfer->toArray(false));
    }

    public function testToArrayIsAcceptedByFromArrayWithNull()
    {
        $locator = KernelLocator::getInstance();
        $this->markTestSkipped();
        $transfer = $locator->system()->transferTestMain();
        $transfer->setInterfaceChild($locator->system()->transferTestInterfaceChild()->fromArray(['bub' => 1]));

        $transfer2 = $locator->system()->transferTestMain()->fromArray($transfer->toArray());

        $this->assertEquals($transfer2->toArray(), $transfer->toArray());
    }
}
