<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

use ArrayObject;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Transfer\Business\Exception\InvalidAssociativeTypeException;
use Spryker\Zed\Transfer\Business\Exception\InvalidAssociativeValueException;
use Spryker\Zed\Transfer\Business\Exception\InvalidNameException;
use Spryker\Zed\Transfer\TransferConfig;
use Zend\Filter\Word\CamelCaseToUnderscore;
use Zend\Filter\Word\UnderscoreToCamelCase;

class ClassDefinition implements ClassDefinitionInterface
{
    public const TYPE_FULLY_QUALIFIED = 'type_fully_qualified';
    public const DEFAULT_ASSOCIATIVE_ARRAY_TYPE = 'string|int';

    protected const EXTRA_TYPE_HINTS = 'extra_type_hints';
    protected const SUPPORTED_VALUE_OBJECTS = [
        'decimal' => [
            self::TYPE_FULLY_QUALIFIED => Decimal::class,
            self::EXTRA_TYPE_HINTS => 'string|int|float',
        ],
    ];

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $constants = [];

    /**
     * @var array
     */
    protected $properties = [];

    /**
     * @var array
     */
    protected $normalizedProperties = [];

    /**
     * @var array
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
     * @var string[]
     */
    protected $useStatements = [];

    /**
     * @var array
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
     * @param array $definition
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

        if (isset($definition['property'])) {
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
            return $this->setNameWithoutValidation($name);
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
     * @return string[]
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
     * @param array $property
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
     * @param array $property
     *
     * @return void
     */
    protected function addProperty(array $property): void
    {
        $property['name'] = lcfirst($property['name']);
        $propertyInfo = [
            'name' => $property['name'],
            'type' => $this->getPropertyType($property),
            'is_typed_array' => $property['is_typed_array'],
            'bundles' => $property['bundles'],
            'is_associative' => $property['is_associative'],
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

            $property['is_typed_array'] = false;
            if ($this->isTypedArray($property)) {
                $property['is_typed_array'] = true;
            }

            $property['is_associative'] = $this->isAssociativeArray($property);

            $normalizedProperties[] = $property;
        }

        $this->normalizedProperties = $normalizedProperties;

        return $normalizedProperties;
    }

    /**
     * @param array $property
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
     * @param array $property
     *
     * @return array
     */
    protected function buildValueObjectPropertyDefinition(array $property): array
    {
        $property['is_value_object'] = true;
        $property[static::TYPE_FULLY_QUALIFIED] = static::SUPPORTED_VALUE_OBJECTS[$property['type']][static::TYPE_FULLY_QUALIFIED];

        return $property;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    protected function isTransferOrTransferArray($type): bool
    {
        return preg_match('/^[A-Z].*/', $type);
    }

    /**
     * @param array $property
     *
     * @return bool
     */
    protected function isValueObject(array $property): bool
    {
        return isset(static::SUPPORTED_VALUE_OBJECTS[$property['type']]);
    }

    /**
     * @param array $property
     *
     * @return string
     */
    protected function getPropertyType(array $property): string
    {
        if ($this->isValueObject($property)) {
            return sprintf('\%s|null', static::SUPPORTED_VALUE_OBJECTS[$property['type']][static::TYPE_FULLY_QUALIFIED]);
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
            return '\Generated\Shared\Transfer\\' . $property['type'] . '|null';
        }

        return $property['type'] . '|null';
    }

    /**
     * @param array $property
     *
     * @return bool
     */
    protected function isTypeTransferObject(array $property): bool
    {
        return ($property['is_transfer']);
    }

    /**
     * @param array $property
     *
     * @return string
     */
    protected function getSetVar(array $property): string
    {
        if ($this->isValueObject($property)) {
            if (empty(static::SUPPORTED_VALUE_OBJECTS[$property['type']][static::EXTRA_TYPE_HINTS])) {
                return sprintf('\%s', static::SUPPORTED_VALUE_OBJECTS[$property['type']][static::TYPE_FULLY_QUALIFIED]);
            }

            return sprintf(
                '%s|\%s',
                static::SUPPORTED_VALUE_OBJECTS[$property['type']][static::EXTRA_TYPE_HINTS],
                static::SUPPORTED_VALUE_OBJECTS[$property['type']][static::TYPE_FULLY_QUALIFIED]
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

        return $property['type'] . '|null';
    }

    /**
     * @param array $property
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
     * @return array
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
     * @param array $property
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
     * @param array $property
     *
     * @return void
     */
    protected function buildGetterAndSetter(array $property): void
    {
        $this->buildSetMethod($property);
        $this->buildGetMethod($property);
    }

    /**
     * @param array $property
     *
     * @return string
     */
    protected function getPropertyConstantName(array $property): string
    {
        $filter = new CamelCaseToUnderscore();

        return mb_strtoupper($filter->filter($property['name']));
    }

    /**
     * @param array $property
     *
     * @return string
     */
    protected function getPropertyName(array $property): string
    {
        $filter = new UnderscoreToCamelCase();

        return lcfirst($filter->filter($property['name']));
    }

    /**
     * @param array $property
     *
     * @return string
     */
    protected function getReturnType(array $property): string
    {
        if ($this->isValueObject($property)) {
            return sprintf('\%s|null', static::SUPPORTED_VALUE_OBJECTS[$property['type']][static::TYPE_FULLY_QUALIFIED]);
        }

        if ($this->isTypedArray($property)) {
            $type = preg_replace('/\[\]/', '', $property['type']);

            return $type . '[]';
        }

        if ($this->isArray($property)) {
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
     * @param array $property
     *
     * @return bool
     */
    protected function isCollection(array $property): bool
    {
        return (bool)preg_match('/((.*?)\[\])/', $property['type']);
    }

    /**
     * @param array $property
     *
     * @return bool
     */
    protected function isArray(array $property): bool
    {
        return ($property['type'] === 'array' || $property['type'] === '[]' || $this->isTypedArray($property));
    }

    /**
     * @param array $property
     *
     * @return bool
     */
    protected function isAssociativeArray(array $property): bool
    {
        return isset($property['associative']) && filter_var($property['associative'], FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @param array $property
     *
     * @return bool
     */
    protected function isTypedArray(array $property): bool
    {
        return (bool)preg_match('/array\[\]|callable\[\]|int\[\]|integer\[\]|float\[\]|decimal\[\]|string\[\]|bool\[\]|boolean\[\]|iterable\[\]|object\[\]|resource\[\]|mixed\[\]/', $property['type']);
    }

    /**
     * @param array $property
     *
     * @return bool|string
     */
    protected function getTypeHint(array $property)
    {
        if ($this->isArray($property) && isset($property['associative'])) {
            return false;
        }

        if ($this->isArray($property)) {
            return 'array';
        }

        if ($this->isValueObject($property)) {
            $this->addUseStatement(static::SUPPORTED_VALUE_OBJECTS[$property['type']][static::TYPE_FULLY_QUALIFIED]);

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
     * @param array $property
     *
     * @return bool|string
     */
    protected function getAddTypeHint(array $property)
    {
        if (preg_match('/^(string|int|integer|float|bool|boolean|mixed|resource|callable|iterable|array|\[\])/', $property['type'])) {
            return false;
        }

        return str_replace('[]', '', $property['type']);
    }

    /**
     * @param array $property
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
            'return' => $this->getReturnType($property),
            'bundles' => $property['bundles'],
            'deprecationDescription' => $this->getPropertyDeprecationDescription($property),
        ];
        $this->methods[$methodName] = $method;
    }

    /**
     * @param array $property
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
            'var' => $this->getSetVar($property),
            'valueObject' => false,
            'bundles' => $property['bundles'],
            'typeHint' => null,
            'deprecationDescription' => $this->getPropertyDeprecationDescription($property),
        ];
        $method = $this->addTypeHint($property, $method);
        $method = $this->addDefaultNull($method, $property);

        if ($this->isValueObject($property)) {
            $method['valueObject'] = substr(strrchr(static::SUPPORTED_VALUE_OBJECTS[$property['type']][static::TYPE_FULLY_QUALIFIED], "\\"), 1);
        }

        $this->methods[$methodName] = $method;
    }

    /**
     * @param array $property
     *
     * @return void
     */
    protected function buildAddMethod(array $property): void
    {
        $parent = $this->getPropertyName($property);
        $propertyConstant = $this->getPropertyConstantName($property);
        if (isset($property['singular'])) {
            $property['name'] = $property['singular'];
        }
        $propertyName = $this->getPropertyName($property);
        $methodName = 'add' . ucfirst($propertyName);

        $method = [
            'name' => $methodName,
            'property' => $propertyName,
            'propertyConst' => $propertyConstant,
            'parent' => $parent,
            'var' => $this->getAddVar($property),
            'bundles' => $property['bundles'],
            'deprecationDescription' => $this->getPropertyDeprecationDescription($property),
            'is_associative' => $this->isAssociativeArray($property),
        ];

        $typeHint = $this->getAddTypeHint($property);
        if ($typeHint) {
            $method['typeHint'] = $typeHint;
        }

        if ($method['is_associative']) {
            $method['var'] = static::DEFAULT_ASSOCIATIVE_ARRAY_TYPE;
            $method['typeHint'] = null;
            $method['varValue'] = $this->getAddVar($property);
            $method['typeHintValue'] = $this->getAddTypeHint($property);
        }

        $this->methods[$methodName] = $method;
    }

    /**
     * @param array $property
     * @param array $method
     *
     * @return array
     */
    protected function addTypeHint(array $property, array $method): array
    {
        $typeHint = $this->getTypeHint($property);
        if ($typeHint) {
            $method['typeHint'] = $typeHint;
        }

        return $method;
    }

    /**
     * @param array $method
     * @param array $property
     *
     * @return array
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
     * @param array $property
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
     * @param array $property
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
                $this->name
            ));
        }
    }

    /**
     * @param array $property
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
     * @param array $property
     *
     * @throws \Spryker\Zed\Transfer\Business\Exception\InvalidAssociativeValueException
     *
     * @return void
     */
    protected function assertPropertyAssociativeValue(array $property): void
    {
        if (!preg_match('(true|false|1|0)', $property['associative'])) {
            throw new InvalidAssociativeValueException(
                'Transfer property "associative" has invalid value. The value has to be "true" or "false".'
            );
        }
    }

    /**
     * @param array $property
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
                $property['type']
            ));
        }
    }

    /**
     * @param array $property
     *
     * @return string|null
     */
    protected function getPropertyDeprecationDescription(array $property): ?string
    {
        return isset($property['deprecated']) ? $property['deprecated'] : null;
    }

    /**
     * @param array $definition
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
                $name
            ));
        }
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
}
