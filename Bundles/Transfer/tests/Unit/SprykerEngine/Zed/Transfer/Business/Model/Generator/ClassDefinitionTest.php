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

    public function testIfOneUseIsSetGetUsesShouldReturnArrayWithOneName()
    {
        $transferDefinition = [
            'name' => 'name',
            'use' => [
                'name' => 'Use\For\Class'
            ]
        ];

        $classDefinition = new ClassDefinition($transferDefinition);

        $uses = $classDefinition->getUses();
        $this->assertTrue(is_array($uses));
        $this->assertContains('Use\For\Class', $uses);
    }

    public function testIfMoreThanOneUseIsSetGetUsesShouldReturnArrayWithAllNames()
    {
        $transferDefinition = [
            'name' => 'name',
            'use' => [
                ['name' => 'Use\For\Class1'],
                ['name' => 'Use\For\Class2'],
            ]
        ];

        $classDefinition = new ClassDefinition($transferDefinition);

        $uses = $classDefinition->getUses();
        $this->assertTrue(is_array($uses));
        $this->assertContains('Use\For\Class1', $uses);
        $this->assertContains('Use\For\Class2', $uses);
    }

    public function testIfOneInterfaceIsSetGetInterfacesShouldReturnArrayWithOneName()
    {
        $transferDefinition = [
            'name' => 'name',
            'interface' => [
                'name' => 'Interface'
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
                ['name' => 'Interface1'],
                ['name' => 'Interface2'],
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
        $property = $this->getProperty('property1', 'type', 'default');
        $transferDefinition = [
            'name' => 'name',
            'property' => $property
        ];

        $classDefinition = new ClassDefinition($transferDefinition);

        $properties = $classDefinition->getProperties();
        $this->assertTrue(is_array($properties));

        $givenProperty = $properties['property1'];
        $expectedProperty = $this->getProperty('property1', 'type', 'default');
        $this->assertEquals($expectedProperty, $givenProperty);
    }

    /**
     * @param $name
     * @param $type
     * @param $default
     * @param null $collection
     * @param null $options
     *
     * @return array
     */
    private function getProperty($name, $type, $default, $collection = null, $options = null)
    {
        $property = [
            'name' => $name,
            'type' => $type,
            'default' => $default
        ];

        if (!is_null($collection)) {
            $property['collection'] = $collection;
        }

        if (!is_null($options)) {
            foreach ($options as $option) {
                foreach ($option as $key => $value) {
                    $property[$key] = $value;
                }
            }
        }

        return $property;
    }

    public function testIfMoreThenOnePropertyIsSetGetPropertiesShouldReturnArrayWithOneProperty()
    {
        $transferDefinition = [
            'name' => 'name',
            'property' => [
                $this->getProperty('property1', 'type', 'default'),
                $this->getProperty('property2', 'type', 'default')
            ]
        ];

        $classDefinition = new ClassDefinition($transferDefinition);

        $properties = $classDefinition->getProperties();
        $this->assertTrue(is_array($properties));

        $givenProperty = $properties['property1'];
        $expectedProperty = $this->getProperty('property1', 'type', 'default');
        $this->assertEquals($expectedProperty, $givenProperty);

        $givenProperty = $properties['property2'];
        $expectedProperty = $this->getProperty('property2', 'type', 'default');
        $this->assertEquals($expectedProperty, $givenProperty);
    }

    public function testIfPropertyTypeIsArrayWithNameShouldBeMarkedAsArray()
    {
        $transferDefinition = [
            'name' => 'name',
            'property' => $this->getProperty('property1', 'array', 'default')
        ];

        $classDefinition = new ClassDefinition($transferDefinition);

        $properties = $classDefinition->getProperties();
        $givenProperty = $properties['property1'];
        $expectedProperty = $this->getProperty('property1', 'array', 'default');
        $this->assertEquals($expectedProperty, $givenProperty);
    }

    public function testIfPropertyTypeIsArrayWithBracketsShouldBeMarkedAsArray()
    {
        $transferDefinition = [
            'name' => 'name',
            'property' => $this->getProperty('property1', '[]', 'default')
        ];

        $classDefinition = new ClassDefinition($transferDefinition);

        $properties = $classDefinition->getProperties();
        $givenProperty = $properties['property1'];
        $expectedProperty = $this->getProperty('property1', 'array', 'default');
        $this->assertEquals($expectedProperty, $givenProperty);
    }

    public function testIfPropertyTypeIsCollectionTheReturnedTypeShouldBeTheType()
    {
        $transferDefinition = [
            'name' => 'name',
            'property' => $this->getProperty('property1', 'Type', 'default', 'collection')
        ];

        $classDefinition = new ClassDefinition($transferDefinition);

        $properties = $classDefinition->getProperties();
        $givenProperty = $properties['property1'];
        $expectedProperty = $this->getProperty('property1', 'Type', 'default');
        $this->assertEquals($expectedProperty, $givenProperty);
    }

    public function testSimplePropertyShouldHaveOnlyGetterAndSetter()
    {
        $transferDefinition = [
            'name' => 'name',
            'property' => $this->getProperty('property1', 'string', 'default')
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
            'property' => $this->getProperty('property1', 'string', 'default')
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
            'property' => $this->getProperty('property1', 'type', 'default', 'collection')
        ];

        $classDefinition = new ClassDefinition($transferDefinition);

        $methods = $classDefinition->getMethods();
        $givenSetter = $methods['setProperty1'];
        $expectedSetter = $this->getMethod('setProperty1', 'property1', 'string', null, 'type');
    }

    public function testCollectionPropertyShouldHaveGetSetAddHasAndRemove()
    {
        $transferDefinition = [
            'name' => 'name',
            'property' => $this->getProperty('property1', 'string', 'default', 'collection')
        ];

        $classDefinition = new ClassDefinition($transferDefinition);

        $methods = $classDefinition->getMethods();
        $givenSetter = $methods['setProperty1'];
        $expectedSetter = $this->getMethod('setProperty1', 'property1', 'string', '$this');

        $givenGetter = $methods['getProperty1'];
        $expectedGetter = $this->getMethod('getProperty1', 'property1', 'string', 'string');

        $givenAdd = $methods['addProperty1'];
        $expectedAdd = $this->getMethod('addProperty1', 'property1', 'string', 'string');

        $givenRemove = $methods['removeProperty1'];
        $expectedRemove = $this->getMethod('removeProperty1', 'property1', 'string', 'string');

        $givenHas = $methods['hasProperty1'];
        $expectedHas = $this->getMethod('hasProperty1', 'property1', 'string', 'string');
    }

    public function testCollectionPropertyWithMethodDefinitionShouldHaveAddHasAndRemoveWithDefinedNames()
    {
        $property = $this->getProperty(
            'properties', 'string', 'default', 'collection', [
                ['add' => ['type' => 'otherType', 'name' => 'property']],
                ['has' => ['type' => 'otherType', 'name' => 'property']],
                ['remove' => ['type' => 'otherType', 'name' => 'property']]
            ]
        );

        $transferDefinition = [
            'name' => 'name',
            'property' => $property
        ];

        $classDefinition = new ClassDefinition($transferDefinition);

        $methods = $classDefinition->getMethods();
        $given = $methods['setProperties'];
        $expected = $this->getCollectionMethod('setProperties', 'properties', 'properties', 'string');
        $this->assertEquals($expected, $given);

        $given = $methods['getProperties'];
        $expected = $this->getMethod('getProperties', 'properties', null, 'string');
        $this->assertEquals($expected, $given);

        $given = $methods['addProperty'];
        $expected = $this->getCollectionMethod('addProperty', 'property', 'properties', 'otherType', null, 'otherType');
        $this->assertEquals($expected, $given);

        $given = $methods['removeProperty'];
        $expected = $this->getCollectionMethod('removeProperty', 'property', 'properties', 'otherType', null, 'otherType');
        $this->assertEquals($expected, $given);

        $given = $methods['hasProperty'];
        $expected = $this->getCollectionMethod('hasProperty', 'property', 'properties', 'otherType', null, 'otherType');
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
