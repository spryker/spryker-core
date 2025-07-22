<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Transfer\Business\Model\Generator;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\Transfer\AbstractAttributesTransfer;
use Spryker\Shared\Transfer\TransferConstants;
use Spryker\Zed\Transfer\Business\Exception\InvalidAbstractAttributesUsageException;
use Spryker\Zed\Transfer\Business\Exception\InvalidAssociativeTypeException;
use Spryker\Zed\Transfer\Business\Exception\InvalidAssociativeValueException;
use Spryker\Zed\Transfer\Business\Exception\InvalidNameException;
use Spryker\Zed\Transfer\Business\Model\Generator\ClassDefinition;
use Spryker\Zed\Transfer\Business\Model\Generator\ClassDefinitionInterface;
use Spryker\Zed\Transfer\TransferConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Transfer
 * @group Business
 * @group Model
 * @group Generator
 * @group ClassDefinitionTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Zed\Transfer\TransferBusinessTester $tester
 */
class ClassDefinitionTest extends Unit
{
    /**
     * @return void
     */
    public function testGetNameShouldReturnNormalizedTransferName(): void
    {
        $transferDefinition = [
            'name' => 'name',
        ];

        $classDefinition = $this->createClassDefinition();
        $classDefinition->setDefinition($transferDefinition);
        $this->assertSame('NameTransfer', $classDefinition->getName());
    }

    /**
     * @return void
     */
    public function testIfOnePropertyIsSetGetPropertiesShouldReturnArrayWithOneProperty(): void
    {
        $property = $this->getProperty('property1', 'string');
        $transferDefinition = [
            'name' => 'name',
            'property' => [$property],
        ];

        $classDefinition = $this->createClassDefinition();
        $classDefinition->setDefinition($transferDefinition);

        $properties = $classDefinition->getProperties();
        $this->assertTrue(is_array($properties));

        $given = $properties['property1'];
        $expected = $this->getProperty('property1', 'string|null');
        $this->assertEquals($expected, $given);
    }

    /**
     * @param string $name
     * @param string $type
     * @param string|null $singular
     * @param string|null $return
     * @param array $bundles
     * @param bool $isArrayCollection
     *
     * @return array
     */
    private function getProperty(
        string $name,
        string $type,
        ?string $singular = null,
        ?string $return = null,
        array $bundles = [],
        bool $isArrayCollection = false
    ): array {
        $property = [
            'name' => $name,
            'type' => ($return === null) ? $type : $return,
            'bundles' => $bundles,
            'is_array_collection' => $isArrayCollection,
            'is_associative' => false,
        ];

        if ($singular !== null) {
            $property['singular'] = $singular;
        }

        return $property;
    }

    /**
     * @return void
     */
    public function testIfMoreThenOnePropertyIsSetGetPropertiesShouldReturnArrayWithAllProperties(): void
    {
        $transferDefinition = [
            'name' => 'name',
            'property' => [
                $this->getProperty('property1', 'string'),
                $this->getProperty('property2', 'string'),
            ],
        ];

        $classDefinition = $this->createClassDefinition();
        $classDefinition->setDefinition($transferDefinition);

        $properties = $classDefinition->getProperties();
        $this->assertTrue(is_array($properties));

        $givenProperty = $properties['property1'];
        $expectedProperty = $this->getProperty('property1', 'string|null');
        $this->assertEquals($expectedProperty, $givenProperty);

        $givenProperty = $properties['property2'];
        $expectedProperty = $this->getProperty('property2', 'string|null');
        $this->assertEquals($expectedProperty, $givenProperty);
    }

    /**
     * @return void
     */
    public function testIfPropertyTypeIsArrayWithNameShouldBeMarkedAsArray(): void
    {
        $transferDefinition = [
            'name' => 'name',
            'property' => [$this->getProperty('property1', 'array')],
        ];

        $classDefinition = $this->createClassDefinition();
        $classDefinition->setDefinition($transferDefinition);

        $properties = $classDefinition->getProperties();
        $givenProperty = $properties['property1'];
        $expectedProperty = $this->getProperty('property1', 'array', null, null, [], true);
        $this->assertEquals($expectedProperty, $givenProperty);
    }

    /**
     * @return void
     */
    public function testIfPropertyNameIsCapitalizedNameShouldBeNormalized(): void
    {
        $transferDefinition = [
            'name' => 'name',
            'property' => [$this->getProperty('Property1', 'array')],
        ];

        $classDefinition = $this->createClassDefinition();
        $classDefinition->setDefinition($transferDefinition);

        $properties = $classDefinition->getProperties();
        $givenProperty = $properties['property1'];
        $expectedProperty = $this->getProperty('property1', 'array', null, null, [], true);
        $this->assertEquals($expectedProperty, $givenProperty);
    }

    /**
     * @return void
     */
    public function testIfPropertyTypeIsCollectionTheReturnTypeShouldBeAnArrayObject(): void
    {
        $transferDefinition = [
            'name' => 'name',
            'property' => [$this->getProperty('property1', 'Type[]')],
        ];

        $classDefinition = $this->createClassDefinition();
        $classDefinition->setDefinition($transferDefinition);

        $properties = $classDefinition->getProperties();
        $givenProperty = $properties['property1'];
        $expectedProperty = $this->getProperty('property1', 'Type[]', null, '\ArrayObject<\Generated\Shared\Transfer\TypeTransfer>');
        $this->assertEquals($expectedProperty, $givenProperty);
    }

    /**
     * @return void
     */
    public function testIfPropertyTypeIsTransferObjectTheReturnTypeShouldBeTransferObject(): void
    {
        $property = $this->getProperty('property1', 'Type');

        $transferDefinition = [
            'name' => 'name',
            'property' => [$property],
        ];

        $classDefinition = $this->createClassDefinition();
        $classDefinition->setDefinition($transferDefinition);

        $properties = $classDefinition->getProperties();
        $givenProperty = $properties['property1'];
        $expectedProperty = $this->getProperty('property1', 'Type', null, '\Generated\Shared\Transfer\TypeTransfer|null');
        $this->assertEquals($expectedProperty, $givenProperty);
    }

    /**
     * @return void
     */
    public function testSimplePropertyShouldHaveOnlyGetterAndSetter(): void
    {
        $this->tester->mockEnvironmentConfig(TransferConstants::IS_DEBUG_ENABLED, false);

        $transferDefinition = [
            'name' => 'name',
            'property' => [$this->getProperty('property1', 'string')],
        ];

        $classDefinition = $this->createClassDefinition();
        $classDefinition->setDefinition($transferDefinition);

        $methods = $classDefinition->getMethods();

        $givenSetter = $methods['setProperty1'];
        $expectedSetter = $this->getMethod('setProperty1', 'property1', 'string|null', null, null, 'PROPERTY1', [], false, false);
        $this->assertEquals($expectedSetter, $givenSetter);

        $givenGetter = $methods['getProperty1'];
        $expectedGetter = $this->getGetMethod('getProperty1', 'property1', null, 'string|null', null, 'PROPERTY1');
        $this->assertEquals($expectedGetter, $givenGetter);
    }

    /**
     * @return void
     */
    public function testSimpleStringPropertyShouldHaveOnlySetterWithoutTypeHint(): void
    {
        $this->tester->mockEnvironmentConfig(TransferConstants::IS_DEBUG_ENABLED, false);

        $transferDefinition = [
            'name' => 'name',
            'property' => [$this->getProperty('property1', 'string')],
        ];

        $classDefinition = $this->createClassDefinition();
        $classDefinition->setDefinition($transferDefinition);

        $methods = $classDefinition->getMethods();
        $givenSetter = $methods['setProperty1'];
        $expectedSetter = $this->getMethod('setProperty1', 'property1', 'string|null', null, null, 'PROPERTY1', [], false, false);

        $this->assertEquals($expectedSetter, $givenSetter);
    }

    /**
     * @return void
     */
    public function testCollectionPropertyShouldHaveOnlySetterWithTypeAsTypeHint(): void
    {
        $transferDefinition = [
            'name' => 'name',
            'property' => [$this->getProperty('property1', 'Type[]')],
        ];

        $classDefinition = $this->createClassDefinition();
        $classDefinition->setDefinition($transferDefinition);

        $methods = $classDefinition->getMethods();
        $givenSetter = $methods['setProperty1'];
        $expectedSetter = $this->getMethod('setProperty1', 'property1', '\ArrayObject<\Generated\Shared\Transfer\TypeTransfer>', null, 'ArrayObject', 'PROPERTY1', [], false, false);

        $this->assertEquals($expectedSetter, $givenSetter);
    }

    /**
     * @return void
     */
    public function testCollectionPropertyShouldHaveGetSetAndAdd(): void
    {
        $bundles = ['Bundle1'];

        $transferDefinition = [
            'name' => 'name',
            'property' => [$this->getProperty('property1', 'Type[]', null, null, $bundles)],
        ];

        $classDefinition = $this->createClassDefinition();
        $classDefinition->setDefinition($transferDefinition);

        $methods = $classDefinition->getMethods();
        $given = $methods['setProperty1'];
        $expected = $this->getMethod('setProperty1', 'property1', '\\ArrayObject<\Generated\Shared\Transfer\TypeTransfer>', null, 'ArrayObject', 'PROPERTY1', $bundles, false, false);
        $this->assertEquals($expected, $given);

        $given = $methods['getProperty1'];
        $expected = $this->getGetMethod('getProperty1', 'property1', null, '\\ArrayObject<\Generated\Shared\Transfer\TypeTransfer>', null, 'PROPERTY1', $bundles);
        $this->assertEquals($expected, $given);

        $given = $methods['addProperty1'];
        $expected = $this->getCollectionMethod('addProperty1', 'property1', 'property1', '\Generated\Shared\Transfer\TypeTransfer', null, 'TypeTransfer', 'PROPERTY1', $bundles);
        $this->assertEquals($expected, $given);
    }

    /**
     * @return void
     */
    public function testTypedArray(): void
    {
        $bundles = ['Bundle1'];

        $transferDefinition = [
            'name' => 'name',
            'property' => [$this->getProperty('property1', 'string[]', null, null, $bundles)],
        ];

        $classDefinition = $this->createClassDefinition();
        $classDefinition->setDefinition($transferDefinition);

        $properties = $classDefinition->getProperties();

        $expected = [
            'property1' => [
                'name' => 'property1',
                'type' => 'string[]',
                'is_array_collection' => true,
                'bundles' => [
                    'Bundle1',
                ],
                'is_associative' => false,
            ],
        ];
        $this->assertEquals($expected, $properties);
    }

    /**
     * @return void
     */
    public function testAbstractAttributesTransfer(): void
    {
        $bundles = ['Bundle1'];

        $transferDefinition = [
            'name' => 'name',
            'property' => [$this->getProperty('property1', 'AbstractAttributes', null, null, $bundles)],
        ];

        $classDefinition = $this->createClassDefinition();
        $classDefinition->setDefinition($transferDefinition);

        $methods = $classDefinition->getMethods();
        $givenSetter = $methods['setProperty1'];
        $expectedSetter = $this->getMethod('setProperty1', 'property1', '\Spryker\Shared\Kernel\Transfer\AbstractTransfer', null, 'AbstractTransfer', 'PROPERTY1', ['Bundle1'], true, false, false, true);
        $this->assertEquals($expectedSetter, $givenSetter);

        $properties = $classDefinition->getProperties();

        $expected = [
            'property1' => [
                'name' => 'property1',
                'type' => '\Spryker\Shared\Kernel\Transfer\AbstractTransfer|\null',
                'is_array_collection' => false,
                'bundles' => [
                    'Bundle1',
                ],
                'is_associative' => false,
            ],
        ];
        $this->assertEquals($expected, $properties);

        $this->assertArrayHasKey(
            AbstractAttributesTransfer::class,
            $classDefinition->getUseStatements(),
        );
        $this->assertSame(
            AbstractAttributesTransfer::class,
            $classDefinition->getUseStatements()[AbstractAttributesTransfer::class],
        );
    }

    /**
     * @return void
     */
    public function testAbstractAttributesArrayTransfer(): void
    {
        $bundles = ['Bundle1'];

        $transferDefinition = [
            'name' => 'name',
            'property' => [$this->getProperty('property1', 'AbstractAttributes[]', null, null, $bundles)],
        ];

        $this->expectException(InvalidAbstractAttributesUsageException::class);

        $classDefinition = $this->createClassDefinition();
        $classDefinition->setDefinition($transferDefinition);
    }

    /**
     * @return void
     */
    public function testCollectionPropertyWithSingularDefinitionShouldHaveAddWithDefinedName(): void
    {
        $property = $this->getProperty('properties', 'Type[]', 'property');

        $transferDefinition = [
            'name' => 'name',
            'property' => [$property],
        ];

        $classDefinition = $this->createClassDefinition();
        $classDefinition->setDefinition($transferDefinition);

        $methods = $classDefinition->getMethods();
        $given = $methods['setProperties'];
        $expected = $this->getMethod('setProperties', 'properties', '\\ArrayObject<\Generated\Shared\Transfer\TypeTransfer>', null, 'ArrayObject', 'PROPERTIES', [], false, false);
        $this->assertEquals($expected, $given);

        $given = $methods['getProperties'];
        $expected = $this->getGetMethod('getProperties', 'properties', null, '\\ArrayObject<\Generated\Shared\Transfer\TypeTransfer>', null, 'PROPERTIES');
        $this->assertEquals($expected, $given);

        $given = $methods['addProperty'];
        $expected = $this->getCollectionMethod('addProperty', 'property', 'properties', '\Generated\Shared\Transfer\TypeTransfer', null, 'TypeTransfer', 'PROPERTIES');
        $this->assertEquals($expected, $given);
    }

    /**
     * @param string $method
     * @param string $property
     * @param string|null $var
     * @param string|null $return
     * @param string|null $typeHint
     * @param string|null $constant
     * @param array $bundles
     * @param bool|null $hasDefaultNull
     * @param bool|null $valueObject
     * @param bool $isTypeAssertionEnabled
     * @param bool $isAbstractAttributesTransfer
     *
     * @return array
     */
    private function getMethod(
        string $method,
        string $property,
        ?string $var = null,
        ?string $return = null,
        ?string $typeHint = null,
        ?string $constant = null,
        array $bundles = [],
        ?bool $hasDefaultNull = null,
        ?bool $valueObject = null,
        bool $isTypeAssertionEnabled = false,
        bool $isAbstractAttributesTransfer = false
    ): array {
        $method = [
            'name' => $method,
            'property' => $property,
            'bundles' => $bundles,
            'deprecationDescription' => null,
            'isTypeAssertionEnabled' => $isTypeAssertionEnabled,
            'isAbstractAttributesTransfer' => $isAbstractAttributesTransfer,
        ];

        if ($isAbstractAttributesTransfer) {
            $method['attributesTransfer'] = true;
            $method['abstractAttributesTransfer'] = 'AbstractAttributesTransfer';
        }

        if ($var !== null) {
            $method['var'] = $var;
        }

        if ($return !== null) {
            $method['return'] = $return;
        }

        $method['typeHint'] = $typeHint;

        if ($constant !== null) {
            $method['propertyConst'] = $constant;
        }

        if ($hasDefaultNull !== null) {
            $method['hasDefaultNull'] = $hasDefaultNull;
        }

        if ($valueObject !== null) {
            $method['valueObject'] = $valueObject;
        }

        return $method;
    }

    /**
     * @param string $method
     * @param string $property
     * @param string|null $var
     * @param string|null $return
     * @param string|null $typeHint
     * @param string|null $constant
     * @param array $bundles
     * @param bool|null $hasDefaultNull
     *
     * @return array
     */
    private function getGetMethod(
        string $method,
        string $property,
        ?string $var = null,
        ?string $return = null,
        ?string $typeHint = null,
        ?string $constant = null,
        array $bundles = [],
        ?bool $hasDefaultNull = null
    ): array {
        $method = $this->getMethod($method, $property, $var, $return, $typeHint, $constant, $bundles, $hasDefaultNull);
        unset($method['typeHint']);
        unset($method['shimNotice']);
        unset($method['isTypeAssertionEnabled']);

        return $method;
    }

    /**
     * @param string $method
     * @param string $property
     * @param string $parent
     * @param string|null $var
     * @param string|null $return
     * @param string|null $typeHint
     * @param string|null $constant
     * @param array $bundles
     *
     * @return array
     */
    private function getCollectionMethod(
        string $method,
        string $property,
        string $parent,
        ?string $var = null,
        ?string $return = null,
        ?string $typeHint = null,
        ?string $constant = null,
        array $bundles = []
    ): array {
        $method = $this->getMethod($method, $property, $var, $return, $typeHint, $constant, $bundles);
        $method['parent'] = $parent;
        $method['is_associative'] = false;
        unset($method['shimNotice']);
        unset($method['isAbstractAttributesTransfer']);

        return $method;
    }

    /**
     * @return void
     */
    public function testInvalidPropertyNameShouldThrowException(): void
    {
        $this->expectException(InvalidNameException::class);
        $property = $this->getProperty('invalid_property_name', 'string');

        $transferDefinition = [
            'name' => 'name',
            'property' => [$property],
        ];

        $classDefinition = $this->createClassDefinition();
        $classDefinition->setDefinition($transferDefinition);
    }

    /**
     * @param string $name
     * @param string $type
     * @param string|null $singular
     * @param string|null $return
     * @param array $bundles
     * @param mixed $isAssociative
     *
     * @return array
     */
    private function getPropertyAssociative(
        string $name,
        string $type,
        ?string $singular = null,
        ?string $return = null,
        array $bundles = [],
        $isAssociative = false
    ): array {
        $property = [
            'name' => $name,
            'type' => ($return === null) ? $type : $return,
            'bundles' => $bundles,
            'is_array_collection' => false,
            'associative' => $isAssociative,
        ];

        if ($singular !== null) {
            $property['singular'] = $singular;
        }

        return $property;
    }

    /**
     * @return void
     */
    public function testTypedAssociativeSimpleArray(): void
    {
        $transferDefinition = [
            'name' => 'name',
            'property' => [$this->getPropertyAssociative('property1', 'string[]', null, null, [], true)],
        ];

        $classDefinition = $this->createClassDefinition();
        $classDefinition->setDefinition($transferDefinition);

        $properties = $classDefinition->getProperties();

        $expected = [
            'property1' => [
                'name' => 'property1',
                'type' => 'string[]',
                'is_array_collection' => true,
                'bundles' => [],
                'is_associative' => true,
            ],
        ];
        $this->assertEquals($expected, $properties);
    }

    /**
     * @return void
     */
    public function testTypedAssociativeCollectionArray(): void
    {
        $transferDefinition = [
            'name' => 'name',
            'property' => [$this->getPropertyAssociative('property1', 'Type[]', null, null, [], true)],
        ];

        $classDefinition = $this->createClassDefinition();
        $classDefinition->setDefinition($transferDefinition);

        $properties = $classDefinition->getProperties();

        $expected = [
            'property1' => [
                'name' => 'property1',
                'type' => "\ArrayObject<\Generated\Shared\Transfer\TypeTransfer>",
                'is_array_collection' => false,
                'bundles' => [],
                'is_associative' => true,
            ],
        ];
        $this->assertEquals($expected, $properties);
    }

    /**
     * @return void
     */
    public function testTypedYesAssociativeCollectionArray(): void
    {
        $transferDefinition = [
            'name' => 'name',
            'property' => [$this->getPropertyAssociative('property1', 'Type[]', null, null, [], '1')],
        ];

        $classDefinition = $this->createClassDefinition();
        $classDefinition->setDefinition($transferDefinition);

        $properties = $classDefinition->getProperties();

        $expected = [
            'property1' => [
                'name' => 'property1',
                'type' => "\ArrayObject<\Generated\Shared\Transfer\TypeTransfer>",
                'is_array_collection' => false,
                'bundles' => [],
                'is_associative' => true,
            ],
        ];
        $this->assertEquals($expected, $properties);
    }

    /**
     * @return void
     */
    public function testInvalidAssociativeTypeException(): void
    {
        $this->expectException(InvalidAssociativeTypeException::class);
        $transferDefinition = [
            'name' => 'name',
            'property' => [$this->getPropertyAssociative('property1', 'string', null, null, [], true)],
        ];

        $classDefinition = $this->createClassDefinition();
        $classDefinition->setDefinition($transferDefinition);
    }

    /**
     * @return void
     */
    public function testInvalidAssociativeValueException(): void
    {
        $this->expectException(InvalidAssociativeValueException::class);
        $transferDefinition = [
            'name' => 'name',
            'property' => [$this->getPropertyAssociative('properties', 'string[]', 'property', null, [], 'Yeah')],
        ];

        $classDefinition = $this->createClassDefinition();
        $classDefinition->setDefinition($transferDefinition);
    }

    /**
     * @dataProvider transferDefinitionTypeIsCorrectlyShimmedDataProvider
     *
     * @param array $shimConfig
     * @param array<string> $expectedTypes
     *
     * @return void
     */
    public function testTransferDefinitionTypeIsCorrectlyShimmed(array $shimConfig, array $expectedTypes): void
    {
        // Arrange
        $this->tester->mockConfigMethod('getTypeShims', $shimConfig);
        $definition = [
            'name' => 'FooBar',
            'property' => [
                'property1' => [
                    'name' => 'property1',
                    'type' => 'int',
                    'is_array_collection' => false,
                    'bundles' => [],
                    'is_associative' => false,
                ],
                'property2' => [
                    'name' => 'property2',
                    'type' => 'string[]',
                    'is_array_collection' => true,
                    'bundles' => [],
                    'is_associative' => false,
                ],
                'property3' => [
                    'name' => 'property3',
                    'type' => 'array',
                    'is_transfer' => true,
                    'is_array_collection' => false,
                    'bundles' => [],
                    'is_associative' => false,
                ],
            ],
        ];
        $classDefinition = $this->createClassDefinition();

        // Act
        $classDefinition->setDefinition($definition);

        // Assert
        $this->assertMethodHasCorrectTypeShim($classDefinition, $expectedTypes);
    }

    /**
     * @return array
     */
    public function transferDefinitionTypeIsCorrectlyShimmedDataProvider(): array
    {
        return [
            'scalar type' => [
                'shimConfig' => [
                    'FooBar' => [
                        'property1' => [
                            'int' => 'string',
                        ],
                    ],
                ],
                'expectedTypes' => [
                    'property1',
                    'string|int|null',
                ],
            ],
            'typed array type' => [
                'shimConfig' => [
                    'FooBar' => [
                        'property2' => [
                            'string[]' => 'bool',
                        ],
                    ],
                ],
                'expectedTypes' => [
                    'property2',
                    'bool|string[]',
                ],
            ],
            'transfer type' => [
                'shimConfig' => [
                    'FooBar' => [
                        'property3' => [
                            'array' => 'int',
                        ],
                    ],
                ],
                'expectedTypes' => [
                    'property3',
                    'int|array',
                ],
            ],
        ];
    }

    /**
     * @param \Spryker\Zed\Transfer\Business\Model\Generator\ClassDefinitionInterface $classDefinition
     * @param array<string> $expectedTypes
     *
     * @return void
     */
    protected function assertMethodHasCorrectTypeShim(ClassDefinitionInterface $classDefinition, array $expectedTypes): void
    {
        $methods = $classDefinition->getMethods();
        [$propertyName, $expectedVarTypes] = $expectedTypes;
        $methodName = 'set' . ucfirst($propertyName);
        $this->assertTrue(isset($methods[$methodName]));
        $this->assertEquals($expectedVarTypes, $methods[$methodName]['var']);
    }

    /**
     * @return \Spryker\Zed\Transfer\TransferConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getTransferConfigMock(): TransferConfig
    {
        return $this->createMock(TransferConfig::class);
    }

    /**
     * @return \Spryker\Zed\Transfer\Business\Model\Generator\ClassDefinition
     */
    protected function createClassDefinition(): ClassDefinition
    {
        $classDefinition = new ClassDefinition(
            $this->tester->getModuleConfig(),
        );

        return $classDefinition;
    }

    /**
     * @dataProvider provideTransferSuffixTestCases
     *
     * @param string $inputName
     * @param bool $isStrictMode
     * @param string $expectedName
     * @param string $testDescription
     *
     * @return void
     */
    public function testSetNameWithoutValidationBehaviorWithTransferSuffixCheckStrict(
        string $inputName,
        bool $isStrictMode,
        string $expectedName,
        string $testDescription
    ): void {
        // Arrange
        $transferConfigMock = $this->getTransferConfigMock();
        $transferConfigMock->method('isTransferSuffixCheckStrict')->willReturn($isStrictMode);

        $classDefinition = new ClassDefinition($transferConfigMock);
        $transferDefinition = ['name' => $inputName];

        // Act
        $classDefinition->setDefinition($transferDefinition);
        $result = $classDefinition->getName();

        // Assert
        $this->assertEquals($expectedName, $result, $testDescription);
    }

    /**
     * @return array<string, array<string, string|bool>>
     */
    public function provideTransferSuffixTestCases(): array
    {
        return [
            'legacy mode: name without Transfer anywhere gets suffix' => [
                'inputName' => 'User',
                'isStrictMode' => false,
                'expectedName' => 'UserTransfer',
                'testDescription' => 'Legacy mode should add Transfer suffix when name does not contain Transfer',
            ],
            'strict mode: name without Transfer anywhere gets suffix' => [
                'inputName' => 'User',
                'isStrictMode' => true,
                'expectedName' => 'UserTransfer',
                'testDescription' => 'Strict mode should add Transfer suffix when name does not contain Transfer',
            ],
            'legacy mode: name ending with Transfer keeps as is' => [
                'inputName' => 'UserTransfer',
                'isStrictMode' => false,
                'expectedName' => 'UserTransfer',
                'testDescription' => 'Legacy mode should not add suffix when name ends with Transfer',
            ],
            'strict mode: name ending with Transfer keeps as is' => [
                'inputName' => 'UserTransfer',
                'isStrictMode' => true,
                'expectedName' => 'UserTransfer',
                'testDescription' => 'Strict mode should not add suffix when name ends with Transfer',
            ],
            'legacy mode: name with Transfer in middle keeps as is (bug case)' => [
                'inputName' => 'EventTransferResponse',
                'isStrictMode' => false,
                'expectedName' => 'EventTransferResponse',
                'testDescription' => 'Legacy mode should not add suffix when Transfer appears anywhere in name',
            ],
            'strict mode: name with Transfer in middle gets suffix (fixed case)' => [
                'inputName' => 'EventTransferResponse',
                'isStrictMode' => true,
                'expectedName' => 'EventTransferResponseTransfer',
                'testDescription' => 'Strict mode should add suffix when Transfer appears in middle but not at end',
            ],
            'legacy mode: name with Transfer at start keeps as is' => [
                'inputName' => 'TransferManager',
                'isStrictMode' => false,
                'expectedName' => 'TransferManager',
                'testDescription' => 'Legacy mode should not add suffix when Transfer appears at start',
            ],
            'strict mode: name with Transfer at start gets suffix' => [
                'inputName' => 'TransferManager',
                'isStrictMode' => true,
                'expectedName' => 'TransferManagerTransfer',
                'testDescription' => 'Strict mode should add suffix when Transfer appears at start but not at end',
            ],
            'legacy mode: multiple Transfer occurrences in name keeps as is' => [
                'inputName' => 'TransferEventTransferResponse',
                'isStrictMode' => false,
                'expectedName' => 'TransferEventTransferResponse',
                'testDescription' => 'Legacy mode should not add suffix when Transfer appears multiple times',
            ],
            'strict mode: multiple Transfer occurrences not ending with Transfer gets suffix' => [
                'inputName' => 'TransferEventTransferResponse',
                'isStrictMode' => true,
                'expectedName' => 'TransferEventTransferResponseTransfer',
                'testDescription' => 'Strict mode should add suffix when Transfer appears multiple times but not at end',
            ],
        ];
    }
}
