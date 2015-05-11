<?php

namespace Unit\SprykerEngine\Zed\Transfer\Business\Model\Generator;

use SprykerEngine\Zed\Transfer\Business\Model\Generator\ClassDefinition;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\ClassGenerator;

/**
 * @group SprykerEngine
 * @group Zed
 * @group Transfer
 * @group Business
 * @group ClassDefinition
 */
class ClassDefinitionTest extends \PHPUnit_Framework_TestCase
{

    public function testGetNameShouldReturnNormalizedTransferName()
    {
        $transferDefinition = [
            'name' => 'name'
        ];

        $classDefinition = new ClassDefinition($transferDefinition);
        $this->assertSame('NameTransfer', $classDefinition->getName());
    }

    public function testIfOneInterfaceIsSetGetUsesShouldReturnArrayWithOneName()
    {
        $transferDefinition = [
            'name' => 'name',
            'interface' => [
                ['name' => 'Used\Interface']
            ]
        ];

        $classDefinition = new ClassDefinition($transferDefinition);

        $uses = $classDefinition->getUses();
        $this->assertTrue(is_array($uses));
        $this->assertContains('Used\Interface', $uses);
    }

    public function testIfMoreThanOneInterfaceIsSetGetUsesShouldReturnArrayWithAllNames()
    {
        $transferDefinition = [
            'name' => 'name',
            'interface' => [
                ['name' => 'Used\Interface1'],
                ['name' => 'Used\Interface2'],
            ]
        ];

        $classDefinition = new ClassDefinition($transferDefinition);

        $uses = $classDefinition->getUses();
        $this->assertTrue(is_array($uses));
        $this->assertContains('Used\Interface1', $uses);
        $this->assertContains('Used\Interface2', $uses);
    }

    public function testIfOneInterfaceIsSetGetInterfacesShouldReturnArrayWithOneName()
    {
        $transferDefinition = [
            'name' => 'name',
            'interface' => [
                ['name' => 'Used\Interface']
            ]
        ];

        $classDefinition = new ClassDefinition($transferDefinition);

        $interfaces = $classDefinition->getInterfaces();
        $this->assertTrue(is_array($interfaces));
        $this->assertContains('Interface', $interfaces);
    }

    public function testIfMoreThanInterfaceUseIsSetGetInterfacesShouldReturnArrayWithAllNames()
    {
        $transferDefinition = [
            'name' => 'name',
            'interface' => [
                ['name' => 'Used\Interface1'],
                ['name' => 'Used\Interface2'],
            ]
        ];

        $classDefinition = new ClassDefinition($transferDefinition);

        $interfaces = $classDefinition->getInterfaces();
        $this->assertTrue(is_array($interfaces));
        $this->assertContains('Interface1', $interfaces);
        $this->assertContains('Interface2', $interfaces);
    }

    public function testIfOnePropertyIsSetGetPropertiesShouldReturnArrayWithOneProperty()
    {
        $property = $this->getProperty('property1', 'type');
        $transferDefinition = [
            'name' => 'name',
            'property' => [$property]
        ];

        $classDefinition = new ClassDefinition($transferDefinition);

        $properties = $classDefinition->getProperties();
        $this->assertTrue(is_array($properties));

        $given = $properties['property1'];
        $expected = $this->getProperty('property1', 'type');
        $this->assertEquals($expected, $given);
    }

    /**
     * @param $name
     * @param $type
     * @param null $singular
     *
     * @return array
     */
    private function getProperty($name, $type, $singular = null, $return = null)
    {
        $property = [
            'name' => $name,
            'type' => (is_null($return)) ? $type : $return
        ];

        if (!is_null($singular)) {
            $property['singular'] = $singular;
        }

        return $property;
    }

    public function testIfMoreThenOnePropertyIsSetGetPropertiesShouldReturnArrayWithOneProperty()
    {
        $transferDefinition = [
            'name' => 'name',
            'property' => [
                $this->getProperty('property1', 'type'),
                $this->getProperty('property2', 'type')
            ]
        ];

        $classDefinition = new ClassDefinition($transferDefinition);

        $properties = $classDefinition->getProperties();
        $this->assertTrue(is_array($properties));

        $givenProperty = $properties['property1'];
        $expectedProperty = $this->getProperty('property1', 'type');
        $this->assertEquals($expectedProperty, $givenProperty);

        $givenProperty = $properties['property2'];
        $expectedProperty = $this->getProperty('property2', 'type');
        $this->assertEquals($expectedProperty, $givenProperty);
    }

    public function testIfPropertyTypeIsArrayWithNameShouldBeMarkedAsArray()
    {
        $transferDefinition = [
            'name' => 'name',
            'property' => [$this->getProperty('property1', 'array')]
        ];

        $classDefinition = new ClassDefinition($transferDefinition);

        $properties = $classDefinition->getProperties();
        $givenProperty = $properties['property1'];
        $expectedProperty = $this->getProperty('property1', 'array');
        $this->assertEquals($expectedProperty, $givenProperty);
    }

    public function testIfPropertyTypeIsArrayWithBracketsShouldBeMarkedAsArray()
    {
        $transferDefinition = [
            'name' => 'name',
            'property' => [$this->getProperty('property1', '[]')]
        ];

        $classDefinition = new ClassDefinition($transferDefinition);

        $properties = $classDefinition->getProperties();
        $givenProperty = $properties['property1'];
        $expectedProperty = $this->getProperty('property1', 'array');
        $this->assertEquals($expectedProperty, $givenProperty);
    }

    public function testIfPropertyTypeIsCollectionConstructorDefinitionMustContainArrayWithThisEntry()
    {
        $transferDefinition = [
            'name' => 'name',
            'property' => [$this->getProperty('property1', 'Collection[]')]
        ];

        $classDefinition = new ClassDefinition($transferDefinition);

        $constructorDefinition = $classDefinition->getConstructorDefinition();
        $this->assertArrayHasKey('property1', $constructorDefinition);
        $this->assertSame('\\ArrayObject', $constructorDefinition['property1']);
    }

    public function testIfMoreThanOnePropertyTypeHasSameCollectionTypeUseShouldContainOnlyOneOfThisEntries()
    {
        $transferDefinition = [
            'name' => 'name',
            'property' => [
                $this->getProperty('property1', 'Collection[]'),
                $this->getProperty('property2', 'Collection[]')
            ]
        ];

        $classDefinition = new ClassDefinition($transferDefinition);

        $uses = $classDefinition->getUses();
        $this->assertCount(1, $uses);
    }

    public function testIfPropertyTypeIsCollectionTheReturTypeShouldBeAnArrayObject()
    {
        $transferDefinition = [
            'name' => 'name',
            'property' => [$this->getProperty('property1', 'Type[]')]
        ];

        $classDefinition = new ClassDefinition($transferDefinition);

        $properties = $classDefinition->getProperties();
        $givenProperty = $properties['property1'];
        $expectedProperty = $this->getProperty('property1', 'Type[]', null, '\ArrayObject');
        $this->assertEquals($expectedProperty, $givenProperty);
    }

    public function testSimplePropertyShouldHaveOnlyGetterAndSetter()
    {
        $transferDefinition = [
            'name' => 'name',
            'property' => [$this->getProperty('property1', 'string')]
        ];

        $classDefinition = new ClassDefinition($transferDefinition);

        $methods = $classDefinition->getMethods();
        $givenSetter = $methods['setProperty1'];
        $expectedSetter = $this->getMethod('setProperty1', 'property1', 'string', '$this');

        $givenGetter = $methods['getProperty1'];
        $expectedGetter = $this->getMethod('getProperty1', 'property1', 'string', 'string');
    }

    public function testSimpleStringPropertyShouldHaveOnlySetterWithoutTypeHint()
    {
        $transferDefinition = [
            'name' => 'name',
            'property' => [$this->getProperty('property1', 'string')]
        ];

        $classDefinition = new ClassDefinition($transferDefinition);

        $methods = $classDefinition->getMethods();
        $givenSetter = $methods['setProperty1'];
        $expectedSetter = $this->getMethod('setProperty1', 'property1', 'string');
    }

    public function testCollectionPropertyShouldHaveOnlySetterWithTypeAsTypeHint()
    {
        $transferDefinition = [
            'name' => 'name',
            'property' => [$this->getProperty('property1', 'Type[]')]
        ];

        $classDefinition = new ClassDefinition($transferDefinition);

        $methods = $classDefinition->getMethods();
        $givenSetter = $methods['setProperty1'];
        $expectedSetter = $this->getMethod('setProperty1', 'property1', 'Type', null, 'Type');
    }

    public function testCollectionPropertyShouldHaveGetSetAndAdd()
    {
        $transferDefinition = [
            'name' => 'name',
            'property' => [$this->getProperty('property1', 'Type[]')]
        ];

        $classDefinition = new ClassDefinition($transferDefinition);

        $methods = $classDefinition->getMethods();
        $given = $methods['setProperty1'];
        $expected = $this->getMethod('setProperty1', 'property1', '\\ArrayObject', null, '\\ArrayObject');
        $this->assertEquals($expected, $given);

        $given = $methods['getProperty1'];
        $expected = $this->getMethod('getProperty1', 'property1', null, 'Type[]');
        $this->assertEquals($expected, $given);

        $given = $methods['addProperty1'];
        $expected = $this->getCollectionMethod('addProperty1', 'property1', 'property1', 'Type', null, 'Type');
        $this->assertEquals($expected, $given);
    }

    public function testCollectionPropertyWithSingularDefinitionShouldHaveAddWithDefinedName()
    {
        $property = $this->getProperty('properties', 'Type[]', 'property');

        $transferDefinition = [
            'name' => 'name',
            'property' => [$property]
        ];

        $classDefinition = new ClassDefinition($transferDefinition);

        $methods = $classDefinition->getMethods();
        $given = $methods['setProperties'];
        $expected = $this->getMethod('setProperties', 'properties', '\\ArrayObject', null, '\\ArrayObject');
        $this->assertEquals($expected, $given);

        $given = $methods['getProperties'];
        $expected = $this->getMethod('getProperties', 'properties', null, 'Type[]');
        $this->assertEquals($expected, $given);

        $given = $methods['addProperty'];
        $expected = $this->getCollectionMethod('addProperty', 'property', 'properties', 'Type', null, 'Type');
        $this->assertEquals($expected, $given);
    }

    /**
     * @param string $method
     * @param string $property
     * @param string $var
     * @param null $return
     * @param null $typeHint
     *
     * @return array
     */
    private function getMethod($method, $property, $var = null, $return = null, $typeHint = null)
    {
        $method = [
            'name' => $method,
            'property' => $property
        ];

        if (!is_null($var)) {
            $method['var'] = $var;
        }

        if (!is_null($return)) {
            $method['return'] = $return;
        }

        if (!is_null($typeHint)) {
            $method['typeHint'] = $typeHint;
        }

        return $method;
    }

    /**
     * @param string $method
     * @param string $property
     * @param string $parent
     * @param string $var
     * @param null $return
     * @param null $typeHint
     *
     * @return array
     */
    private function getCollectionMethod($method, $property, $parent, $var = null, $return = null, $typeHint = null)
    {
        $method = $this->getMethod($method, $property, $var, $return, $typeHint);
        $method['parent'] = $parent;

        return $method;
    }
}
