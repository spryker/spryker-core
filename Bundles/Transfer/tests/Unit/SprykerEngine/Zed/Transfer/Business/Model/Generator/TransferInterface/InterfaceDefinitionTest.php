<?php

namespace Unit\SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferInterface;

use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferInterface\InterfaceDefinition;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferInterface\InterfaceGenerator;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @group SprykerEngine
 * @group Zed
 * @group Transfer
 * @group Business
 * @group InterfaceDefinition
 */
class InterfaceDefinitionTest extends \PHPUnit_Framework_TestCase
{

    public function testSetDefinitionShouldSetBundleName()
    {
        $expectedBundleName = 'Bundle';
        $interfaceDefinition = new InterfaceDefinition();
        $interfaceDefinition->setDefinition([
            'bundle' => $expectedBundleName,
            'name' => 'Name',
        ]);

        $this->assertSame($expectedBundleName, $interfaceDefinition->getBundle());
    }

    public function testSetDefinitionShouldSetNameWithInterfaceSuffixIfInterfaceNotAlreadyInName()
    {
        $interfaceDefinition = new InterfaceDefinition();
        $interfaceDefinition->setDefinition([
            'bundle' => 'Bundle',
            'name' => 'Name',
        ]);
        $expectedName = 'NameInterface';

        $this->assertSame($expectedName, $interfaceDefinition->getName());
    }

    public function testSetDefinitionShouldSetNameWithInterfaceSuffixIfInterfaceAlreadyInName()
    {
        $expectedName = 'NameInterface';
        $interfaceDefinition = new InterfaceDefinition();
        $interfaceDefinition->setDefinition([
            'bundle' => 'Bundle',
            'name' => $expectedName,
        ]);

        $this->assertSame($expectedName, $interfaceDefinition->getName());
    }

    public function testSetDefinitionWithTransferPropertyShouldAddUseForGivenTransfer()
    {
        $interfaceDefinition = new InterfaceDefinition();
        $interfaceDefinition->setDefinition([
            'bundle' => 'Bundle',
            'name' => 'Name',
            'property' => [
                [
                    'name' => 'Foo',
                    'type' => 'Bar'
                ]
            ]
        ]);

        $expectedUse = 'Generated\\Shared\\Transfer\\BarTransfer';
        $this->assertArrayHasKey($expectedUse, $interfaceDefinition->getUses());
        $this->assertContains($expectedUse, $interfaceDefinition->getUses());
    }

    public function testSetDefinitionWithTransferPropertyMarkedAsCollectionShouldSetDefinitionForSetGetAndAddMethods()
    {
        $interfaceDefinition = new InterfaceDefinition();
        $interfaceDefinition->setDefinition([
            'bundle' => 'Bundle',
            'name' => 'Name',
            'property' => [
                [
                    'name' => 'Foo',
                    'type' => 'Bar[]'
                ]
            ]
        ]);

        $methods = $interfaceDefinition->getMethods();
        $this->assertArrayHasKey('setFoo', $methods);
        $this->assertArrayHasKey('getFoo', $methods);
        $this->assertArrayHasKey('addFoo', $methods);
    }

    public function testSetDefinitionWithArrayPropertyShouldSetDefinitionForSetGetAndAddMethods()
    {
        $interfaceDefinition = new InterfaceDefinition();
        $interfaceDefinition->setDefinition([
            'bundle' => 'Bundle',
            'name' => 'Name',
            'property' => [
                [
                    'name' => 'Foo',
                    'type' => 'array'
                ]
            ]
        ]);

        $methods = $interfaceDefinition->getMethods();
        $this->assertArrayHasKey('setFoo', $methods);
        $this->assertArrayHasKey('getFoo', $methods);
        $this->assertArrayHasKey('addFoo', $methods);
    }

    public function testSetDefinitionWithArrayPropertyAndDefinedSingularAttributeShouldUseSingularAsNameForAddMethod()
    {
        $interfaceDefinition = new InterfaceDefinition();
        $interfaceDefinition->setDefinition([
            'bundle' => 'Bundle',
            'name' => 'Name',
            'property' => [
                [
                    'name' => 'Items',
                    'type' => 'array',
                    'singular' => 'Item'
                ]
            ]
        ]);

        $methods = $interfaceDefinition->getMethods();
        $this->assertArrayHasKey('setItems', $methods);
        $this->assertArrayHasKey('getItems', $methods);
        $this->assertArrayHasKey('addItem', $methods);
    }
}
