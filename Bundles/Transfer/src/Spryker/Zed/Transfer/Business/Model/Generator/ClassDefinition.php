<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

use ArrayObject;
use Laminas\Filter\Word\CamelCaseToUnderscore;
use Laminas\Filter\Word\UnderscoreToCamelCase;
use Spryker\DecimalObject\Decimal;
use Spryker\Shared\Transfer\TypeValidation\TransferTypeValidatorTrait;
use Spryker\Zed\Transfer\Business\Exception\InvalidAssociativeTypeException;
use Spryker\Zed\Transfer\Business\Exception\InvalidAssociativeValueException;
use Spryker\Zed\Transfer\Business\Exception\InvalidNameException;
use Spryker\Zed\Transfer\Business\Exception\InvalidSingularPropertyNameException;
use Spryker\Zed\Transfer\TransferConfig;

class ClassDefinition implements ClassDefinitionInterface
{
    /**
     * @var string
     */
    public const TYPE_FULLY_QUALIFIED = 'type_fully_qualified';

    /**
     * @var string
     */
    public const DEFAULT_ASSOCIATIVE_ARRAY_TYPE = 'string|int';

    /**
     * @var string
     */
    protected const EXTRA_TYPE_HINTS = 'extra_type_hints';

    /**
     * @var array<string, array<string, string>>
     */
    protected const SUPPORTED_VALUE_OBJECTS = [
        'decimal' => [
            self::TYPE_FULLY_QUALIFIED => Decimal::class,
            self::EXTRA_TYPE_HINTS => 'string|int|float',
        ],
    ];

    /**
     * @var string
     */
    protected const SHIM_NOTICE_TEMPLATE = 'Forward compatibility warning: %s is the actual type (please use that, %s is kept for BC).';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array<string, array>
     */
    protected $constants = [];

    /**
     * @var array<string, array>
     */
    protected $properties = [];

    /**
     * @var array<array>
     */
    protected $normalizedProperties = [];

    /**
     * @var array<string, array>
     */
    protected $methods = [];

    /**
     * @var array
     */
    protected $constructorDefinition = [];

    /**
     * @var string|null
     */
    protected $deprecationDescription;

    /**
     * @var array<string, string>
     */
    protected $useStatements = [];

    /**
     * @var array<string, string>
     */
    protected $propertyNameMap = [];

    /**
     * @var string|null
     */
    protected $entityNamespace;

    /**
     * @var \Spryker\Zed\Transfer\TransferConfig
     */
    protected $transferConfig;

    /**
     * @param \Spryker\Zed\Transfer\TransferConfig $transferConfig
     */
    public function __construct(TransferConfig $transferConfig)
    {
        $this->transferConfig = $transferConfig;
    }

    /**
     * @param array<string, mixed> $definition
     *
     * @return $this
     */
    public function setDefinition(array $definition)
    {
        $this->setName($definition['name']);

        if (isset($definition['deprecated'])) {
            $this->deprecationDescription = $definition['deprecated'];
        }

        $this->addEntityNamespace($definition);
        $this->addExtraUseStatements();

        if (isset($definition['property'])) {
            $definition = $this->shimTransferDefinitionPropertyTypes($definition);
            $properties = $this->normalizePropertyTypes($definition['property']);
            $this->addConstants($properties);
            $this->addProperties($properties);
            $this->setPropertyNameMap($properties);
            $this->addMethods($properties);
        }

        return $this;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    protected function setName($name)
    {
        if (!$this->transferConfig->isTransferNameValidated()) {
            $this->setNameWithoutValidation($name);

            return $this;
        }

        $this->assertValidName($name);

        $this->name = $name . 'Transfer';

        return $this;
    }

    /**
     * BC shim to use strict generation only as feature flag to be
     * enabled manually on project level.
     *
     * @deprecated Will be removed with the next major to enforce validation then.
     *
     * @param string $name
     *
     * @return $this
     */
    protected function setNameWithoutValidation(string $name)
    {
        if (strpos($name, 'Transfer') === false) {
            $name .= 'Transfer';
        }

        $this->name = ucfirst($name);

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<string>
     */
    public function getUseStatements(): array
    {
        return $this->useStatements;
    }

    /**
     * @param array $properties
     *
     * @return void
     */
    protected function addConstants(array $properties): void
    {
        foreach ($properties as $property) {
            $this->addConstant($property);
        }
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return void
     */
    protected function addConstant(array $property): void
    {
        $property['name'] = lcfirst($property['name']);
        $propertyInfo = [
            'name' => $this->getPropertyConstantName($property),
            'value' => $property['name'],
            'deprecationDescription' => $this->getPropertyDeprecationDescription($property),
        ];

        $this->constants[$property['name']] = $propertyInfo;
    }

    /**
     * @param array $properties
     *
     * @return void
     */
    protected function addProperties(array $properties): void
    {
        foreach ($properties as $property) {
            $this->addProperty($property);
        }
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return void
     */
    protected function addProperty(array $property): void
    {
        $property['name'] = lcfirst($property['name']);
        $propertyInfo = [
            'name' => $property['name'],
            'type' => $this->buildPropertyType($property),
            'bundles' => $property['bundles'],
            'is_associative' => $property['is_associative'],
            'is_array_collection' => $this->isArrayCollection($property),
        ];

        $this->properties[$property['name']] = $propertyInfo;
    }

    /**
     * @param array $properties
     *
     * @return void
     */
    protected function setPropertyNameMap(array $properties): void
    {
        foreach ($properties as $property) {
            $nameCamelCase = $this->getPropertyName($property);
            $this->propertyNameMap[$property['name_underscore']] = $nameCamelCase;
            $this->propertyNameMap[$nameCamelCase] = $nameCamelCase;
            $this->propertyNameMap[ucfirst($nameCamelCase)] = $nameCamelCase;
        }
    }

    /**
     * Properties which are Transfer MUST be suffixed with Transfer
     *
     * @param array $properties
     *
     * @return array
     */
    protected function normalizePropertyTypes(array $properties): array
    {
        $normalizedProperties = [];
        foreach ($properties as $property) {
            $this->assertProperty($property);

            $property[static::TYPE_FULLY_QUALIFIED] = $property['type'];
            $property['is_collection'] = false;
            $property['is_transfer'] = false;
            $property['is_value_object'] = false;
            $property['propertyConst'] = $this->getPropertyConstantName($property);
            $property['name_underscore'] = mb_strtolower($property['propertyConst']);

            if ($this->isTransferOrTransferArray($property['type'])) {
                $property = $this->buildTransferPropertyDefinition($property);
            }

            if ($this->isValueObject($property)) {
                $property = $this->buildValueObjectPropertyDefinition($property);
            }

            $property['is_associative'] = $this->isAssociativeArray($property);
            $property['is_strict'] = $this->isStrictProperty($property);

            $normalizedProperties[] = $property;
        }

        $this->normalizedProperties = $normalizedProperties;

        return $normalizedProperties;
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return array
     */
    protected function buildTransferPropertyDefinition(array $property): array
    {
        $property['is_transfer'] = true;
        $property[static::TYPE_FULLY_QUALIFIED] = 'Generated\\Shared\\Transfer\\';

        if (preg_match('/\[\]$/', $property['type'])) {
            $property['type'] = str_replace('[]', '', $property['type']) . 'Transfer[]';
            $property[static::TYPE_FULLY_QUALIFIED] = 'Generated\\Shared\\Transfer\\' . str_replace('[]', '', $property['type']);
            $property['is_collection'] = true;

            return $property;
        }
        $property['type'] .= 'Transfer';
        $property[static::TYPE_FULLY_QUALIFIED] .= $property['type'];

        return $property;
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return array
     */
    protected function buildValueObjectPropertyDefinition(array $property): array
    {
        $property['is_value_object'] = true;
        $property[static::TYPE_FULLY_QUALIFIED] = $this->getValueObjectFullyQualifiedClassName($property);

        return $property;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    protected function isTransferOrTransferArray($type): bool
    {
        return (bool)preg_match('/^[A-Z].*/', $type);
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return bool
     */
    protected function isValueObject(array $property): bool
    {
        return isset(static::SUPPORTED_VALUE_OBJECTS[$property['type']]);
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return string
     */
    protected function getPropertyType(array $property): string
    {
        if ($this->isValueObject($property)) {
            return sprintf('\%s|null', $this->getValueObjectFullyQualifiedClassName($property));
        }

        if ($this->isTypedArray($property)) {
            $type = preg_replace('/\[\]/', '', $property['type']);

            return $type . '[]';
        }

        if ($this->isPrimitiveArray($property)) {
            return 'array|null';
        }

        if ($this->isArrayCollection($property)) {
            return 'array';
        }

        if ($this->isCollection($property)) {
            return '\ArrayObject|\Generated\Shared\Transfer\\' . $property['type'];
        }

        if ($this->isTypeTransferObject($property)) {
            return '\Generated\Shared\Transfer\\' . $property['type'] . '|null';
        }

        return $property['type'] . '|null';
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return bool
     */
    protected function isTypeTransferObject(array $property): bool
    {
        return ($property['is_transfer']);
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return string
     */
    protected function getSetVar(array $property): string
    {
        if ($this->isValueObject($property)) {
            if (empty(static::SUPPORTED_VALUE_OBJECTS[$property['type']][static::EXTRA_TYPE_HINTS])) {
                return sprintf('\%s', $this->getValueObjectFullyQualifiedClassName($property));
            }

            return sprintf(
                '%s|\%s',
                static::SUPPORTED_VALUE_OBJECTS[$property['type']][static::EXTRA_TYPE_HINTS],
                $this->getValueObjectFullyQualifiedClassName($property),
            );
        }

        if ($this->isTypedArray($property)) {
            $type = preg_replace('/\[\]/', '', $property['type']);

            return $type . '[]';
        }

        if ($this->isArray($property)) {
            return 'array';
        }

        if ($this->isCollection($property)) {
            return '\ArrayObject|\Generated\Shared\Transfer\\' . $property['type'];
        }

        if ($this->isTypeTransferObject($property)) {
            return '\Generated\Shared\Transfer\\' . $property['type'];
        }

        if ($this->isStrictProperty($property)) {
            return $property['type'];
        }

        return $property['type'] . '|null';
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return string
     */
    protected function getAddVar(array $property): string
    {
        if ($this->isTypedArray($property)) {
            return preg_replace('/\[\]/', '', $property['type']);
        }

        if ($this->isArray($property)) {
            return 'mixed';
        }

        if ($this->isCollection($property)) {
            return '\Generated\Shared\Transfer\\' . str_replace('[]', '', $property['type']);
        }

        return str_replace('[]', '', $property['type']);
    }

    /**
     * @return array
     */
    public function getConstants(): array
    {
        return $this->constants;
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @return array<string, string>
     */
    public function getPropertyNameMap(): array
    {
        return $this->propertyNameMap;
    }

    /**
     * @param array $properties
     *
     * @return void
     */
    protected function addMethods(array $properties): void
    {
        foreach ($properties as $property) {
            $this->addPropertyMethods($property);
        }
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return void
     */
    protected function addPropertyMethods(array $property): void
    {
        $this->buildGetterAndSetter($property);

        if ($this->isCollection($property) || $this->isArray($property)) {
            $this->buildAddMethod($property);
        }

        $this->buildRequireMethod($property);
        $this->buildStrictPropertyMethods($property);
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return void
     */
    protected function buildStrictPropertyMethods(array $property): void
    {
        if (!$this->isStrictProperty($property)) {
            return;
        }

        if ($this->isAssociativeArray($property)) {
            $this->buildGetCollectionElementMethod($property);
        }
    }

    /**
     * @return array
     */
    public function getConstructorDefinition(): array
    {
        return $this->constructorDefinition;
    }

    /**
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @return array
     */
    public function getNormalizedProperties(): array
    {
        return $this->normalizedProperties;
    }

    /**
     * @return string|null
     */
    public function getDeprecationDescription(): ?string
    {
        return $this->deprecationDescription;
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return void
     */
    protected function buildGetterAndSetter(array $property): void
    {
        $this->buildSetMethod($property);
        $this->buildGetMethod($property);

        if (!$this->isArrayCollection($property) && !$this->isCollection($property)) {
            $this->buildGetOrFailMethod($property);
        }
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return void
     */
    protected function buildGetOrFailMethod(array $property): void
    {
        $propertyName = $this->getPropertyName($property);
        $methodName = sprintf('get%sOrFail', ucfirst($propertyName));
        $method = [
            'name' => $methodName,
            'property' => $propertyName,
            'propertyConst' => $this->getPropertyConstantName($property),
            'return' => preg_replace('/\|null$/', '', $this->getReturnType($property)),
            'bundles' => $property['bundles'],
            'deprecationDescription' => $this->getPropertyDeprecationDescription($property),
        ];

        $method = $this->addGetOrFailTypeHint($method, $property);
        $this->methods[$methodName] = $method;
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return string
     */
    protected function getPropertyConstantName(array $property): string
    {
        $filter = new CamelCaseToUnderscore();

        return mb_strtoupper($filter->filter($property['name']));
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return string
     */
    protected function getPropertyName(array $property): string
    {
        $filter = new UnderscoreToCamelCase();

        return lcfirst($filter->filter($property['name']));
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return string
     */
    protected function getReturnType(array $property): string
    {
        if ($this->isValueObject($property)) {
            return sprintf('\%s|null', $this->getValueObjectFullyQualifiedClassName($property));
        }

        if ($this->isTypedArray($property)) {
            $type = preg_replace('/\[\]/', '', $property['type']);

            return $type . '[]';
        }

        if ($this->isPrimitiveArray($property)) {
            return 'array|null';
        }

        if ($this->isArrayCollection($property)) {
            return 'array';
        }

        if ($this->isCollection($property)) {
            return '\\ArrayObject|\Generated\Shared\Transfer\\' . $property['type'];
        }

        if ($this->isTypeTransferObject($property)) {
            return '\Generated\Shared\Transfer\\' . $property['type'] . '|null';
        }

        return $property['type'] . '|null';
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return bool
     */
    protected function isCollection(array $property): bool
    {
        return (bool)preg_match('/((.*?)\[\])/', $property['type']);
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return bool
     */
    protected function isArray(array $property): bool
    {
        return ($property['type'] === 'array' || $property['type'] === '[]' || $this->isTypedArray($property));
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return bool
     */
    protected function isPrimitiveArray(array $property): bool
    {
        if (!$this->isStrictProperty($property)) {
            return false;
        }

        return $property['type'] === 'array' && !isset($property['singular']) && !$this->isAssociativeArray($property);
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return bool
     */
    protected function isArrayCollection(array $property): bool
    {
        if ($this->isStrictProperty($property)) {
            return $this->isArray($property) && !$this->isPrimitiveArray($property);
        }

        return $this->isArray($property);
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return bool
     */
    protected function isAssociativeArray(array $property): bool
    {
        return isset($property['associative']) && filter_var($property['associative'], FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return bool
     */
    protected function isTypedArray(array $property): bool
    {
        return (bool)preg_match('/array\[\]|callable\[\]|int\[\]|integer\[\]|float\[\]|decimal\[\]|string\[\]|bool\[\]|boolean\[\]|iterable\[\]|object\[\]|resource\[\]|mixed\[\]/', $property['type']);
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return string|bool
     */
    protected function getSetTypeHint(array $property)
    {
        if ($this->isStrictProperty($property)) {
            return $this->getStrictSetTypeHint($property);
        }

        if ($this->isArray($property) && isset($property['associative'])) {
            return false;
        }

        if ($this->isArray($property)) {
            return 'array';
        }

        if ($this->isValueObject($property)) {
            $this->addUseStatement($this->getValueObjectFullyQualifiedClassName($property));

            return false;
        }

        if (preg_match('/^(string|int|integer|float|bool|boolean)$/', $property['type'])) {
            return false;
        }

        if ($this->isCollection($property)) {
            $this->addUseStatement(ArrayObject::class);

            return 'ArrayObject';
        }

        return $property['type'];
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return string|bool
     */
    protected function getAddTypeHint(array $property)
    {
        if ($this->isStrictProperty($property)) {
            return $this->getStrictCollectionElementTypeHint($property);
        }

        if (preg_match('/^(string|int|integer|float|bool|boolean|mixed|resource|callable|iterable|array|\[\])/', $property['type'])) {
            return false;
        }

        return str_replace('[]', '', $property['type']);
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return string|bool
     */
    protected function getStrictCollectionElementTypeHint(array $property)
    {
        if ($property['type'] === 'array' || $property['type'] === 'mixed') {
            return false;
        }

        return str_replace('[]', '', $property['type']);
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return void
     */
    protected function buildGetMethod(array $property): void
    {
        $propertyName = $this->getPropertyName($property);
        $methodName = 'get' . ucfirst($propertyName);
        $method = [
            'name' => $methodName,
            'property' => $propertyName,
            'propertyConst' => $this->getPropertyConstantName($property),
            'return' => $this->buildGetReturnTypeData($property),
            'bundles' => $property['bundles'],
            'deprecationDescription' => $this->getPropertyDeprecationDescription($property),
        ];

        if ($this->propertyHasTypeShim($property)) {
            $method['typeShimNotice'] = $this->buildTypeShimNotice(
                $property['type'],
                $this->getPropertyTypeShim($property),
            );
        }

        $method = $this->addGetReturnTypeHint($method, $property);

        $this->methods[$methodName] = $method;
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return void
     */
    protected function buildSetMethod(array $property): void
    {
        $propertyName = $this->getPropertyName($property);
        $methodName = 'set' . ucfirst($propertyName);
        $method = [
            'name' => $methodName,
            'property' => $propertyName,
            'propertyConst' => $this->getPropertyConstantName($property),
            'var' => $this->buildSetArgumentType($property),
            'valueObject' => false,
            'bundles' => $property['bundles'],
            'typeHint' => null,
            'deprecationDescription' => $this->getPropertyDeprecationDescription($property),
        ];
        $method = $this->addSetTypeHint($method, $property);
        $method = $this->addDefaultNull($method, $property);
        $method = $this->setTypeAssertionMode($method);

        if ($this->isArrayCollection($property)) {
            $method['setsArrayCollection'] = true;
        }

        if ($this->isCollectionPropertyTypeCheckNeeded($property)) {
            $method['isCollectionPropertyTypeCheckNeeded'] = true;
            $method['addMethodName'] = 'add' . ucfirst($this->getPropertySingularName($property));
        }

        if ($this->propertyHasTypeShim($property)) {
            $method['typeShimNotice'] = $this->buildTypeShimNotice(
                $property['type'],
                $this->getPropertyTypeShim($property),
            );
        }

        if ($this->isValueObject($property)) {
            $method['valueObject'] = $this->getShortClassName(
                $this->getValueObjectFullyQualifiedClassName($property),
            );
        }

        $this->methods[$methodName] = $method;
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return void
     */
    protected function buildAddMethod(array $property): void
    {
        $parent = $this->getPropertyName($property);
        $propertyConstant = $this->getPropertyConstantName($property);
        $propertyName = $this->getPropertySingularName($property);
        $methodName = 'add' . ucfirst($propertyName);

        $method = [
            'name' => $methodName,
            'property' => $propertyName,
            'propertyConst' => $propertyConstant,
            'parent' => $parent,
            'var' => $this->buildAddArgumentType($property),
            'bundles' => $property['bundles'],
            'deprecationDescription' => $this->getPropertyDeprecationDescription($property),
            'is_associative' => $this->isAssociativeArray($property),
        ];

        if ($this->propertyHasTypeShim($property)) {
            $method['typeShimNotice'] = $this->buildAddTypeShimNotice(
                $property['type'],
                $this->getPropertyTypeShim($property),
            );
        }

        $typeHint = $this->getAddTypeHint($property);
        if ($typeHint) {
            $method['typeHint'] = $typeHint;
        }

        if ($method['is_associative']) {
            $method['var'] = static::DEFAULT_ASSOCIATIVE_ARRAY_TYPE;
            $method['typeHint'] = null;
            $method['varValue'] = $this->buildAddArgumentType($property);
            $method['typeHintValue'] = $this->getAddTypeHint($property);
        }

        $method = $this->setTypeAssertionMode($method);
        $this->methods[$methodName] = $method;
    }

    /**
     * @param array<string, mixed> $method
     * @param array<string, mixed> $property
     *
     * @return array<string, mixed>
     */
    protected function addSetTypeHint(array $method, array $property): array
    {
        $typeHint = $this->getSetTypeHint($property);

        if ($typeHint) {
            $method['typeHint'] = $typeHint;
        }

        return $method;
    }

    /**
     * @param array<string, mixed> $method
     * @param array<string, mixed> $property
     *
     * @return array<string, mixed>
     */
    protected function addDefaultNull(array $method, array $property): array
    {
        $method['hasDefaultNull'] = false;

        if ($this->isValueObject($property) || ($method['typeHint'] && (!$this->isCollection($property) || $method['typeHint'] === 'array'))) {
            $method['hasDefaultNull'] = true;
        }

        return $method;
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return void
     */
    protected function buildRequireMethod(array $property): void
    {
        $propertyName = $this->getPropertyName($property);
        $methodName = 'require' . ucfirst($propertyName);
        $method = [
            'name' => $methodName,
            'property' => $propertyName,
            'propertyConst' => $this->getPropertyConstantName($property),
            'isCollection' => ($this->isCollection($property) && !$this->isArray($property)),
            'bundles' => $property['bundles'],
            'deprecationDescription' => $this->getPropertyDeprecationDescription($property),
        ];
        $this->methods[$methodName] = $method;
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return bool
     */
    protected function isStrictProperty(array $property): bool
    {
        return $property[DefinitionNormalizer::KEY_STRICT_MODE] ?? false;
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return void
     */
    protected function assertProperty(array $property): void
    {
        $this->assertPropertyName($property['name']);
        $this->assertPropertyAssociative($property);
    }

    /**
     * @param string $propertyName
     *
     * @throws \Spryker\Zed\Transfer\Business\Exception\InvalidNameException
     *
     * @return void
     */
    protected function assertPropertyName($propertyName): void
    {
        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9]+$/', $propertyName)) {
            throw new InvalidNameException(sprintf(
                'Transfer property "%s" needs to be alpha-numeric and camel-case formatted in "%s"!',
                $propertyName,
                $this->name,
            ));
        }
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return void
     */
    protected function assertPropertyAssociative(array $property): void
    {
        if (isset($property['associative'])) {
            $this->assertPropertyAssociativeType($property);
            $this->assertPropertyAssociativeValue($property);
        }
    }

    /**
     * @param array<string, mixed> $property
     *
     * @throws \Spryker\Zed\Transfer\Business\Exception\InvalidAssociativeValueException
     *
     * @return void
     */
    protected function assertPropertyAssociativeValue(array $property): void
    {
        if (!preg_match('(true|false|1|0)', $property['associative'])) {
            throw new InvalidAssociativeValueException(
                'Transfer property "associative" has invalid value. The value has to be "true" or "false".',
            );
        }
    }

    /**
     * @param array<string, mixed> $property
     *
     * @throws \Spryker\Zed\Transfer\Business\Exception\InvalidAssociativeTypeException
     *
     * @return void
     */
    protected function assertPropertyAssociativeType(array $property): void
    {
        if (!$this->isArray($property) && !$this->isCollection($property)) {
            throw new InvalidAssociativeTypeException(sprintf(
                'Transfer property "associative" cannot be defined to type: "%s"!',
                $property['type'],
            ));
        }
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return string|null
     */
    protected function getPropertyDeprecationDescription(array $property): ?string
    {
        return isset($property['deprecated']) ? $property['deprecated'] : null;
    }

    /**
     * @param array<string, mixed> $definition
     *
     * @return void
     */
    protected function addEntityNamespace(array $definition): void
    {
        if (isset($definition['entity-namespace'])) {
            $this->entityNamespace = $definition['entity-namespace'];
        }
    }

    /**
     * @return string|null
     */
    public function getEntityNamespace(): ?string
    {
        return $this->entityNamespace;
    }

    /**
     * @param string $name
     *
     * @throws \Spryker\Zed\Transfer\Business\Exception\InvalidNameException
     *
     * @return void
     */
    protected function assertValidName(string $name): void
    {
        if (preg_match('/Transfer$/', $name)) {
            throw new InvalidNameException(sprintf(
                'Transfer names must not be suffixed with the word "Transfer", it will be auto-appended on generation: `%s`. Please remove the suffix.',
                $name,
            ));
        }
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return string
     */
    public function getValueObjectFullyQualifiedClassName(array $property): string
    {
        return static::SUPPORTED_VALUE_OBJECTS[$property['type']][static::TYPE_FULLY_QUALIFIED];
    }

    /**
     * @return bool
     */
    public function isDebugMode(): bool
    {
        return $this->transferConfig->isDebugEnabled();
    }

    /**
     * @param string $fullyQualifiedClassName
     *
     * @return string
     */
    protected function getShortClassName(string $fullyQualifiedClassName): string
    {
        return substr(strrchr($fullyQualifiedClassName, '\\'), 1);
    }

    /**
     * @param string $fullyQualifiedClassName
     *
     * @return void
     */
    protected function addUseStatement(string $fullyQualifiedClassName): void
    {
        if (isset($this->useStatements[$fullyQualifiedClassName])) {
            return;
        }

        $this->useStatements[$fullyQualifiedClassName] = $fullyQualifiedClassName;
        ksort($this->useStatements, SORT_STRING);
    }

    /**
     * @param array<string, mixed> $transferDefinition
     *
     * @return array<string, mixed>
     */
    protected function shimTransferDefinitionPropertyTypes(array $transferDefinition): array
    {
        $transferName = $transferDefinition['name'];
        $shim = $this->transferConfig->getTypeShims()[$transferName] ?? null;

        if (!isset($transferDefinition['property']) || !$shim) {
            return $transferDefinition;
        }

        foreach ($shim as $propertyName => $shimChange) {
            foreach ($transferDefinition['property'] as $propertyKey => $propertyDefinition) {
                if ($propertyDefinition['name'] !== $propertyName) {
                    continue;
                }

                $propertyDefinition = $this->shimPropertyType($propertyDefinition, $shimChange);
                $transferDefinition['property'][$propertyKey] = $propertyDefinition;
            }
        }

        return $transferDefinition;
    }

    /**
     * @param array<string, mixed> $propertyDefinition
     * @param array<string> $shimChange
     *
     * @return array
     */
    protected function shimPropertyType(array $propertyDefinition, array $shimChange): array
    {
        $toType = $shimChange[$propertyDefinition['type']] ?? null;

        if ($toType !== null) {
            $propertyDefinition['typeShim'] = $toType;
        }

        return $propertyDefinition;
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return string|null
     */
    protected function getPropertyTypeShim(array $property): ?string
    {
        return $property['typeShim'] ?? null;
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return bool
     */
    protected function propertyHasTypeShim(array $property): bool
    {
        return (bool)$this->getPropertyTypeShim($property);
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return string
     */
    protected function buildPropertyType(array $property): string
    {
        return $this->buildType(
            $this->getPropertyType($property),
            $this->getPropertyTypeShim($property),
        );
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return string
     */
    protected function buildSetArgumentType(array $property): string
    {
        return $this->buildType(
            $this->getSetVar($property),
            $this->getPropertyTypeShim($property),
        );
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return string
     */
    protected function buildGetReturnTypeData(array $property): string
    {
        return $this->buildType(
            $this->getReturnType($property),
            $this->getPropertyTypeShim($property),
        );
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return string
     */
    protected function buildAddArgumentType(array $property): string
    {
        $type = $this->getAddVar($property);
        $typeShim = $this->getPropertyTypeShim($property);

        if ($typeShim !== null) {
            $typeShim = str_replace('[]', '', $typeShim);
        }

        return $this->buildType($type, $typeShim);
    }

    /**
     * @param string $type
     * @param string|null $typeShim
     *
     * @return string
     */
    protected function buildType(string $type, ?string $typeShim = null): string
    {
        if ($typeShim === null) {
            return $type;
        }

        return sprintf('%s|%s', $typeShim, $type);
    }

    /**
     * @param string $type
     * @param string $typeShim
     *
     * @return string
     */
    protected function buildAddTypeShimNotice(string $type, string $typeShim): string
    {
        $type = str_replace('[]', '', $type);
        $typeShim = str_replace('[]', '', $typeShim);

        return $this->buildTypeShimNotice($type, $typeShim);
    }

    /**
     * @param string $type
     * @param string|null $typeShim
     *
     * @return string
     */
    protected function buildTypeShimNotice(string $type, ?string $typeShim): string
    {
        return sprintf(static::SHIM_NOTICE_TEMPLATE, $typeShim, $type);
    }

    /**
     * @param array<string, mixed> $method
     *
     * @return array<string, mixed>
     */
    protected function setTypeAssertionMode(array $method): array
    {
        $method['isTypeAssertionEnabled'] = false;
        $methodArgumentType = $method['varValue'] ?? $method['var'];

        if (!$this->isDebugMode() || $this->getEntityNamespace() || $methodArgumentType === 'mixed') {
            return $method;
        }

        $methodArgumentTypeHint = $method['typeHintValue'] ?? $method['typeHint'] ?? null;
        $method['isTypeAssertionEnabled'] = empty($methodArgumentTypeHint) || $methodArgumentTypeHint === 'array';

        return $method;
    }

    /**
     * @return void
     */
    protected function addExtraUseStatements(): void
    {
        if ($this->isDebugMode() && !$this->getEntityNamespace()) {
            $this->addUseStatement(TransferTypeValidatorTrait::class);
        }
    }

    /**
     * @param array<string, mixed> $method
     * @param array<string, mixed> $property
     *
     * @return array<string, mixed>
     */
    protected function addGetReturnTypeHint(array $method, array $property): array
    {
        if ($this->isStrictProperty($property)) {
            $method['returnTypeHint'] = $this->buildGetReturnTypeHint($property);
        }

        return $method;
    }

    /**
     * @param array<string, mixed> $method
     * @param array<string, mixed> $property
     *
     * @return array<string, mixed>
     */
    protected function addGetOrFailTypeHint(array $method, array $property): array
    {
        if ($this->isStrictProperty($property)) {
            $method['returnTypeHint'] = ltrim($this->buildGetReturnTypeHint($property), '?');
        }

        return $method;
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return string
     */
    protected function buildGetReturnTypeHint(array $property): string
    {
        if ($this->isPrimitiveArray($property)) {
            return '?array';
        }

        if ($this->isArrayCollection($property)) {
            return 'array';
        }

        if ($this->isCollection($property)) {
            return 'ArrayObject';
        }

        $type = $property['type'];

        if ($this->isValueObject($property)) {
            $type = $this->getShortClassName(
                $this->getValueObjectFullyQualifiedClassName($property),
            );
        }

        return sprintf('?%s', $type);
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return string|bool
     */
    protected function getStrictSetTypeHint(array $property)
    {
        if ($this->isPrimitiveArray($property)) {
            return '?array';
        }

        if ($this->isArrayCollection($property)) {
            return 'array';
        }

        if ($this->isValueObject($property)) {
            $this->addUseStatement($this->getValueObjectFullyQualifiedClassName($property));

            return false;
        }

        if ($this->isCollection($property)) {
            $this->addUseStatement(ArrayObject::class);

            return 'ArrayObject';
        }

        return sprintf('?%s', $property['type']);
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return void
     */
    protected function buildGetCollectionElementMethod(array $property): void
    {
        $this->assertSingularPropertyNameIsValid($property);

        $originalPropertyName = $this->getPropertyName($property);
        $property['name'] = $property['singular'];
        $singularPropertyName = $this->getPropertyName($property);
        $methodName = 'get' . ucfirst($singularPropertyName);

        $method = [
            'name' => $methodName,
            'property' => $originalPropertyName,
            'var' => static::DEFAULT_ASSOCIATIVE_ARRAY_TYPE,
            'bundles' => $property['bundles'],
            'deprecationDescription' => $this->getPropertyDeprecationDescription($property),
            'return' => $this->getGetCollectionElementReturnType($property),
            'isItemGetter' => true,
        ];

        $typeHint = $this->getStrictCollectionElementTypeHint($property);
        if ($typeHint) {
            $method['returnTypeHint'] = $typeHint;
        }

        $this->methods[$methodName] = $method;
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return string
     */
    protected function getGetCollectionElementReturnType(array $property): string
    {
        $propertyReturnType = str_replace('[]', '', $property['type']);

        if ($propertyReturnType === 'array') {
            return 'mixed';
        }

        if ($this->isCollection($property)) {
            return '\Generated\Shared\Transfer\\' . $propertyReturnType;
        }

        return $propertyReturnType;
    }

    /**
     * @param array<string, mixed> $property
     *
     * @throws \Spryker\Zed\Transfer\Business\Exception\InvalidSingularPropertyNameException
     *
     * @return void
     */
    protected function assertSingularPropertyNameIsValid(array $property): void
    {
        if ($this->isStrictProperty($property) && $this->isAssociativeArray($property) && !isset($property['singular'])) {
            throw new InvalidSingularPropertyNameException(
                sprintf(
                    'No singular form for the property %s.%s is found. Please add "singular" attribute to this property\'s definition.',
                    $this->name,
                    $property['name'],
                ),
            );
        }

        if ($this->isStrictProperty($property) && $this->isAssociativeArray($property) && $property['name'] === $property['singular']) {
            throw new InvalidSingularPropertyNameException(
                sprintf(
                    'Values of the "name" and "singular" attributes of the property %s.%s must not match.',
                    $this->name,
                    $property['name'],
                ),
            );
        }
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return string
     */
    protected function getPropertySingularName(array $property): string
    {
        $property['name'] = isset($property['singular']) ? $property['singular'] : $property['name'];

        return $this->getPropertyName($property);
    }

    /**
     * @param array<string, mixed> $property
     *
     * @return bool
     */
    protected function isCollectionPropertyTypeCheckNeeded(array $property): bool
    {
        return $this->isStrictProperty($property) && $this->isCollection($property);
    }
}
