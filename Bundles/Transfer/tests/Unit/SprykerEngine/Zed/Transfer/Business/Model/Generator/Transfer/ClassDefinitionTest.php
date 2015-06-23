<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Transfer\Business\Model\Generator\Transfer;

use SprykerEngine\Zed\Transfer\Business\Model\Generator\Transfer\ClassDefinition;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\Transfer\ClassGenerator;

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

        $classDefinition = new ClassDefinition();
        $classDefinition->setDefinition($transferDefinition);
        $this->assertSame('NameTransfer', $classDefinition->getName());
    }

    public function testGetUsesShouldReturnArrayWithDefinedInterface()
    {
        $transferDefinition = [
            'name' => 'name',
            'interface' => [
                ['name' => 'Used\Interface', 'bundle' => 'Test']
            ]
        ];

        $classDefinition = new ClassDefinition();
        $classDefinition->setDefinition($transferDefinition);

        $uses = $classDefinition->getUses();
        $this->assertTrue(is_array($uses));
        $this->assertContains('Used\Interface as TestInterface', $uses);
    }

    public function testIfOnePropertyIsSetGetPropertiesShouldReturnArrayWithOneProperty()
    {
        $property = $this->getProperty('property1', 'string');
        $transferDefinition = [
            'name' => 'name',
            'property' => [$property]
        ];

        $classDefinition = new ClassDefinition();
        $classDefinition->setDefinition($transferDefinition);

        $properties = $classDefinition->getProperties();
        $this->assertTrue(is_array($properties));

        $given = $properties['property1'];
        $expected = $this->getProperty('property1', 'string');
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
                $this->getProperty('property1', 'string'),
                $this->getProperty('property2', 'string')
            ]
        ];

        $classDefinition = new ClassDefinition();
        $classDefinition->setDefinition($transferDefinition);

        $properties = $classDefinition->getProperties();
        $this->assertTrue(is_array($properties));

        $givenProperty = $properties['property1'];
        $expectedProperty = $this->getProperty('property1', 'string');
        $this->assertEquals($expectedProperty, $givenProperty);

        $givenProperty = $properties['property2'];
        $expectedProperty = $this->getProperty('property2', 'string');
        $this->assertEquals($expectedProperty, $givenProperty);
    }

    public function testIfPropertyTypeIsArrayWithNameShouldBeMarkedAsArray()
    {
        $transferDefinition = [
            'name' => 'name',
            'property' => [$this->getProperty('property1', 'array')]
        ];

        $classDefinition = new ClassDefinition();
        $classDefinition->setDefinition($transferDefinition);

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

        $classDefinition = new ClassDefinition();
        $classDefinition->setDefinition($transferDefinition);

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

        $classDefinition = new ClassDefinition();
        $classDefinition->setDefinition($transferDefinition);

        $uses = $classDefinition->getUses();
        $this->assertCount(1, $uses);
    }

    public function testIfPropertyTypeIsCollectionTheReturnTypeShouldBeAnArrayObject()
    {
        $transferDefinition = [
            'name' => 'name',
            'property' => [$this->getProperty('property1', 'Type[]')]
        ];

        $classDefinition = new ClassDefinition();
        $classDefinition->setDefinition($transferDefinition);

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

        $classDefinition = new ClassDefinition();
        $classDefinition->setDefinition($transferDefinition);

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

        $classDefinition = new ClassDefinition();
        $classDefinition->setDefinition($transferDefinition);

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

        $classDefinition = new ClassDefinition();
        $classDefinition->setDefinition($transferDefinition);

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

        $classDefinition = new ClassDefinition();
        $classDefinition->setDefinition($transferDefinition);

        $methods = $classDefinition->getMethods();
        $given = $methods['setProperty1'];
        $expected = $this->getMethod('setProperty1', 'property1', '\\ArrayObject', null, '\\ArrayObject');
        $this->assertEquals($expected, $given);

        $given = $methods['getProperty1'];
        $expected = $this->getMethod('getProperty1', 'property1', null, 'TypeTransfer[]');
        $this->assertEquals($expected, $given);

        $given = $methods['addProperty1'];
        $expected = $this->getCollectionMethod('addProperty1', 'property1', 'property1', 'TypeTransfer', null, 'TypeTransfer');
        $this->assertEquals($expected, $given);
    }

    public function testCollectionPropertyWithSingularDefinitionShouldHaveAddWithDefinedName()
    {
        $property = $this->getProperty('properties', 'Type[]', 'property');

        $transferDefinition = [
            'name' => 'name',
            'property' => [$property]
        ];

        $classDefinition = new ClassDefinition();
        $classDefinition->setDefinition($transferDefinition);

        $methods = $classDefinition->getMethods();
        $given = $methods['setProperties'];
        $expected = $this->getMethod('setProperties', 'properties', '\\ArrayObject', null, '\\ArrayObject');
        $this->assertEquals($expected, $given);

        $given = $methods['getProperties'];
        $expected = $this->getMethod('getProperties', 'properties', null, 'TypeTransfer[]');
        $this->assertEquals($expected, $given);

        $given = $methods['addProperty'];
        $expected = $this->getCollectionMethod('addProperty', 'property', 'properties', 'TypeTransfer', null, 'TypeTransfer');
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
