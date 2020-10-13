<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Generated\Shared\Transfer;

use ArrayObject;
use Spryker\DecimalObject\Decimal;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 */
class StrictFooBarTransfer extends AbstractTransfer
{
    public const PROP_SCALAR = 'propScalar';

    public const PROP_SCALAR_STRICT = 'propScalarStrict';

    public const PROP_DECIMAL = 'propDecimal';

    public const PROP_DECIMAL_STRICT = 'propDecimalStrict';

    public const PROP_ARRAY = 'propArray';

    public const PROP_ARRAY_STRICT = 'propArrayStrict';

    public const PROP_DUMMY_TRANSFER = 'propDummyTransfer';

    public const PROP_DUMMY_TRANSFER_COLLECTION = 'propDummyTransferCollection';

    public const FOO_TYPED_ARRAY = 'fooTypedArray';

    public const FOO_TYPED_ARRAY_STRICT = 'fooTypedArrayStrict';

    public const FOO_TYPED_ARRAY_ASSOC = 'fooTypedArrayAssoc';

    public const FOO_TYPED_ARRAY_ASSOC_STRICT = 'fooTypedArrayAssocStrict';

    /**
     * @var int|null
     */
    protected $propScalar;

    /**
     * @var int|null
     */
    protected $propScalarStrict;

    /**
     * @var \Spryker\DecimalObject\Decimal|null
     */
    protected $propDecimal;

    /**
     * @var \Spryker\DecimalObject\Decimal|null
     */
    protected $propDecimalStrict;

    /**
     * @var array
     */
    protected $propArray = [];

    /**
     * @var array
     */
    protected $propArrayStrict = [];

    /**
     * @var \Generated\Shared\Transfer\StrictFooBarPropertyTransfer|null
     */
    protected $propDummyTransfer;

    /**
     * @var \ArrayObject|\Generated\Shared\Transfer\StrictFooBarPropertyTransfer[]
     */
    protected $propDummyTransferCollection;

    /**
     * @var string[]
     */
    protected $fooTypedArray = [];

    /**
     * @var string[]
     */
    protected $fooTypedArrayStrict = [];

    /**
     * @var string[]
     */
    protected $fooTypedArrayAssoc = [];

    /**
     * @var string[]
     */
    protected $fooTypedArrayAssocStrict = [];

    /**
     * @var array
     */
    protected $transferPropertyNameMap = [
        'prop_scalar' => 'propScalar',
        'propScalar' => 'propScalar',
        'PropScalar' => 'propScalar',
        'prop_scalar_strict' => 'propScalarStrict',
        'propScalarStrict' => 'propScalarStrict',
        'PropScalarStrict' => 'propScalarStrict',
        'prop_decimal' => 'propDecimal',
        'propDecimal' => 'propDecimal',
        'PropDecimal' => 'propDecimal',
        'prop_decimal_strict' => 'propDecimalStrict',
        'propDecimalStrict' => 'propDecimalStrict',
        'PropDecimalStrict' => 'propDecimalStrict',
        'prop_array' => 'propArray',
        'propArray' => 'propArray',
        'PropArray' => 'propArray',
        'prop_array_strict' => 'propArrayStrict',
        'propArrayStrict' => 'propArrayStrict',
        'PropArrayStrict' => 'propArrayStrict',
        'prop_dummy_transfer' => 'propDummyTransfer',
        'propDummyTransfer' => 'propDummyTransfer',
        'PropDummyTransfer' => 'propDummyTransfer',
        'prop_dummy_transfer_collection' => 'propDummyTransferCollection',
        'propDummyTransferCollection' => 'propDummyTransferCollection',
        'PropDummyTransferCollection' => 'propDummyTransferCollection',
        'foo_typed_array' => 'fooTypedArray',
        'fooTypedArray' => 'fooTypedArray',
        'FooTypedArray' => 'fooTypedArray',
        'foo_typed_array_strict' => 'fooTypedArrayStrict',
        'fooTypedArrayStrict' => 'fooTypedArrayStrict',
        'FooTypedArrayStrict' => 'fooTypedArrayStrict',
        'foo_typed_array_assoc' => 'fooTypedArrayAssoc',
        'fooTypedArrayAssoc' => 'fooTypedArrayAssoc',
        'FooTypedArrayAssoc' => 'fooTypedArrayAssoc',
        'foo_typed_array_assoc_strict' => 'fooTypedArrayAssocStrict',
        'fooTypedArrayAssocStrict' => 'fooTypedArrayAssocStrict',
        'FooTypedArrayAssocStrict' => 'fooTypedArrayAssocStrict',
    ];

    /**
     * @var array
     */
    protected $transferMetadata = [
        self::PROP_SCALAR => [
            'type' => 'int',
            'name_underscore' => 'prop_scalar',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
        ],
        self::PROP_SCALAR_STRICT => [
            'type' => 'int',
            'name_underscore' => 'prop_scalar_strict',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
        ],
        self::PROP_DECIMAL => [
            'type' => 'Spryker\DecimalObject\Decimal',
            'name_underscore' => 'prop_decimal',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => true,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
        ],
        self::PROP_DECIMAL_STRICT => [
            'type' => 'Spryker\DecimalObject\Decimal',
            'name_underscore' => 'prop_decimal_strict',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => true,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
        ],
        self::PROP_ARRAY => [
            'type' => 'array',
            'name_underscore' => 'prop_array',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
        ],
        self::PROP_ARRAY_STRICT => [
            'type' => 'array',
            'name_underscore' => 'prop_array_strict',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
        ],
        self::PROP_DUMMY_TRANSFER => [
            'type' => 'Generated\Shared\Transfer\StrictFooBarPropertyTransfer',
            'name_underscore' => 'prop_dummy_transfer',
            'is_collection' => false,
            'is_transfer' => true,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
        ],
        self::PROP_DUMMY_TRANSFER_COLLECTION => [
            'type' => 'Generated\Shared\Transfer\StrictFooBarPropertyTransfer',
            'name_underscore' => 'prop_dummy_transfer_collection',
            'is_collection' => true,
            'is_transfer' => true,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
        ],
        self::FOO_TYPED_ARRAY => [
            'type' => 'string[]',
            'name_underscore' => 'foo_typed_array',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
        ],
        self::FOO_TYPED_ARRAY_STRICT => [
            'type' => 'string[]',
            'name_underscore' => 'foo_typed_array_strict',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
        ],
        self::FOO_TYPED_ARRAY_ASSOC => [
            'type' => 'string[]',
            'name_underscore' => 'foo_typed_array_assoc',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => true,
            'is_nullable' => false,
        ],
        self::FOO_TYPED_ARRAY_ASSOC_STRICT => [
            'type' => 'string[]',
            'name_underscore' => 'foo_typed_array_assoc_strict',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => true,
            'is_nullable' => false,
        ],
    ];

    /**
     * @module Test
     *
     * @param int|null $propScalar
     *
     * @return $this
     */
    public function setPropScalar($propScalar)
    {
        $this->propScalar = $propScalar;
        $this->modifiedProperties[self::PROP_SCALAR] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return int|null
     */
    public function getPropScalar()
    {
        return $this->propScalar;
    }

    /**
     * @module Test
     *
     * @return $this
     */
    public function requirePropScalar()
    {
        $this->assertPropertyIsSet(self::PROP_SCALAR);

        return $this;
    }

    /**
     * @module Test
     *
     * @param int|null $propScalarStrict
     *
     * @return $this
     */
    public function setPropScalarStrict(?int $propScalarStrict = null)
    {
        $this->propScalarStrict = $propScalarStrict;
        $this->modifiedProperties[self::PROP_SCALAR_STRICT] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return int|null
     */
    public function getPropScalarStrict(): ?int 
    {
        return $this->propScalarStrict;
    }

    /**
    * @module Test
    *
    * @return bool
    */
    public function hasPropScalarStrict(): bool
    {
        return isset($this->propScalarStrict);
    }

    /**
     * @module Test
     *
     * @param string|int|float|\Spryker\DecimalObject\Decimal|null $propDecimal
     *
     * @return $this
     */
    public function setPropDecimal($propDecimal = null)
    {
        if ($propDecimal !== null && !$propDecimal instanceof Decimal) {
            $propDecimal = new Decimal($propDecimal);
        }

        $this->propDecimal = $propDecimal;
        $this->modifiedProperties[self::PROP_DECIMAL] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return \Spryker\DecimalObject\Decimal|null
     */
    public function getPropDecimal()
    {
        return $this->propDecimal;
    }

    /**
     * @module Test
     *
     * @return $this
     */
    public function requirePropDecimal()
    {
        $this->assertPropertyIsSet(self::PROP_DECIMAL);

        return $this;
    }

    /**
     * @module Test
     *
     * @param string|int|float|\Spryker\DecimalObject\Decimal|null $propDecimalStrict
     *
     * @return $this
     */
    public function setPropDecimalStrict($propDecimalStrict = null)
    {
        if ($propDecimalStrict !== null && !$propDecimalStrict instanceof Decimal) {
            $propDecimalStrict = new Decimal($propDecimalStrict);
        }

        $this->propDecimalStrict = $propDecimalStrict;
        $this->modifiedProperties[self::PROP_DECIMAL_STRICT] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return \Spryker\DecimalObject\Decimal|null
     */
    public function getPropDecimalStrict(): ?Decimal 
    {
        return $this->propDecimalStrict;
    }

    /**
    * @module Test
    *
    * @return bool
    */
    public function hasPropDecimalStrict(): bool
    {
        return isset($this->propDecimalStrict);
    }

    /**
     * @module Test
     *
     * @param array|null $propArray
     *
     * @return $this
     */
    public function setPropArray(array $propArray = null)
    {
        if ($propArray === null) {
            $propArray = [];
        }

        $this->propArray = $propArray;
        $this->modifiedProperties[self::PROP_ARRAY] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return array
     */
    public function getPropArray()
    {
        return $this->propArray;
    }

    /**
     * @module Test
     *
     * @param mixed $propArray
     *
     * @return $this
     */
    public function addPropArray($propArray)
    {
        $this->propArray[] = $propArray;
        $this->modifiedProperties[self::PROP_ARRAY] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return $this
     */
    public function requirePropArray()
    {
        $this->assertPropertyIsSet(self::PROP_ARRAY);

        return $this;
    }

    /**
     * @module Test
     *
     * @param array|null $propArrayStrict
     *
     * @return $this
     */
    public function setPropArrayStrict(?array $propArrayStrict = null)
    {
        if ($propArrayStrict === null) {
            $propArrayStrict = [];
        }

        $this->propArrayStrict = $propArrayStrict;
        $this->modifiedProperties[self::PROP_ARRAY_STRICT] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return array
     */
    public function getPropArrayStrict(): array 
    {
        return $this->propArrayStrict;
    }

    /**
     * @module Test
     *
     * @param mixed $propArrayStrict
     *
     * @return $this
     */
    public function addPropArrayStrict($propArrayStrict)
    {
        $this->propArrayStrict[] = $propArrayStrict;
        $this->modifiedProperties[self::PROP_ARRAY_STRICT] = true;

        return $this;
    }

    /**
    * @module Test
    *
    * @return bool
    */
    public function hasPropArrayStrict(): bool
    {
        return isset($this->propArrayStrict);
    }

    /**
     * @module Test
     *
     * @param \Generated\Shared\Transfer\StrictFooBarPropertyTransfer|null $propDummyTransfer
     *
     * @return $this
     */
    public function setPropDummyTransfer(StrictFooBarPropertyTransfer $propDummyTransfer = null)
    {
        $this->propDummyTransfer = $propDummyTransfer;
        $this->modifiedProperties[self::PROP_DUMMY_TRANSFER] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return \Generated\Shared\Transfer\StrictFooBarPropertyTransfer|null
     */
    public function getPropDummyTransfer()
    {
        return $this->propDummyTransfer;
    }

    /**
     * @module Test
     *
     * @return $this
     */
    public function requirePropDummyTransfer()
    {
        $this->assertPropertyIsSet(self::PROP_DUMMY_TRANSFER);

        return $this;
    }

    /**
     * @module Test
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\StrictFooBarPropertyTransfer[] $propDummyTransferCollection
     *
     * @return $this
     */
    public function setPropDummyTransferCollection(ArrayObject $propDummyTransferCollection)
    {
        $this->propDummyTransferCollection = $propDummyTransferCollection;
        $this->modifiedProperties[self::PROP_DUMMY_TRANSFER_COLLECTION] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\StrictFooBarPropertyTransfer[]
     */
    public function getPropDummyTransferCollection(): ArrayObject 
    {
        return $this->propDummyTransferCollection;
    }

    /**
     * @module Test
     *
     * @param \Generated\Shared\Transfer\StrictFooBarPropertyTransfer $propDummyTransferCollection
     *
     * @return $this
     */
    public function addPropDummyTransferCollection(StrictFooBarPropertyTransfer $propDummyTransferCollection)
    {
        $this->propDummyTransferCollection[] = $propDummyTransferCollection;
        $this->modifiedProperties[self::PROP_DUMMY_TRANSFER_COLLECTION] = true;

        return $this;
    }

    /**
    * @module Test
    *
    * @return bool
    */
    public function hasPropDummyTransferCollection(): bool
    {
        return isset($this->propDummyTransferCollection);
    }

    /**
     * @module Test
     *
     * @param string[]|null $fooTypedArray
     *
     * @return $this
     */
    public function setFooTypedArray(array $fooTypedArray = null)
    {
        if ($fooTypedArray === null) {
            $fooTypedArray = [];
        }

        $this->fooTypedArray = $fooTypedArray;
        $this->modifiedProperties[self::FOO_TYPED_ARRAY] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return string[]
     */
    public function getFooTypedArray()
    {
        return $this->fooTypedArray;
    }

    /**
     * @module Test
     *
     * @param string $fooTypedArray
     *
     * @return $this
     */
    public function addFooTypedArray($fooTypedArray)
    {
        $this->fooTypedArray[] = $fooTypedArray;
        $this->modifiedProperties[self::FOO_TYPED_ARRAY] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return $this
     */
    public function requireFooTypedArray()
    {
        $this->assertPropertyIsSet(self::FOO_TYPED_ARRAY);

        return $this;
    }

    /**
     * @module Test
     *
     * @param string[]|null $fooTypedArrayStrict
     *
     * @return $this
     */
    public function setFooTypedArrayStrict(?array $fooTypedArrayStrict = null)
    {
        if ($fooTypedArrayStrict === null) {
            $fooTypedArrayStrict = [];
        }

        $this->fooTypedArrayStrict = $fooTypedArrayStrict;
        $this->modifiedProperties[self::FOO_TYPED_ARRAY_STRICT] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return string[]
     */
    public function getFooTypedArrayStrict(): array 
    {
        return $this->fooTypedArrayStrict;
    }

    /**
     * @module Test
     *
     * @param string $fooTypedArrayStrict
     *
     * @return $this
     */
    public function addFooTypedArrayStrict($fooTypedArrayStrict)
    {
        $this->fooTypedArrayStrict[] = $fooTypedArrayStrict;
        $this->modifiedProperties[self::FOO_TYPED_ARRAY_STRICT] = true;

        return $this;
    }

    /**
    * @module Test
    *
    * @return bool
    */
    public function hasFooTypedArrayStrict(): bool
    {
        return isset($this->fooTypedArrayStrict);
    }

    /**
     * @module Test
     *
     * @param string[] $fooTypedArrayAssoc
     *
     * @return $this
     */
    public function setFooTypedArrayAssoc($fooTypedArrayAssoc)
    {
        $this->fooTypedArrayAssoc = $fooTypedArrayAssoc;
        $this->modifiedProperties[self::FOO_TYPED_ARRAY_ASSOC] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return string[]
     */
    public function getFooTypedArrayAssoc()
    {
        return $this->fooTypedArrayAssoc;
    }

    /**
     * @module Test
     *
     * @param string|int $fooTypedArrayAssocKey
     * @param string $fooTypedArrayAssocValue
     *
     * @return $this
     */
    public function addFooTypedArrayAssoc($fooTypedArrayAssocKey, $fooTypedArrayAssocValue)
    {
        $this->fooTypedArrayAssoc[$fooTypedArrayAssocKey] = $fooTypedArrayAssocValue;
        $this->modifiedProperties[self::FOO_TYPED_ARRAY_ASSOC] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return $this
     */
    public function requireFooTypedArrayAssoc()
    {
        $this->assertPropertyIsSet(self::FOO_TYPED_ARRAY_ASSOC);

        return $this;
    }

    /**
     * @module Test
     *
     * @param string[]|null $fooTypedArrayAssocStrict
     *
     * @return $this
     */
    public function setFooTypedArrayAssocStrict(?array $fooTypedArrayAssocStrict = null)
    {
        if ($fooTypedArrayAssocStrict === null) {
            $fooTypedArrayAssocStrict = [];
        }

        $this->fooTypedArrayAssocStrict = $fooTypedArrayAssocStrict;
        $this->modifiedProperties[self::FOO_TYPED_ARRAY_ASSOC_STRICT] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return string[]
     */
    public function getFooTypedArrayAssocStrict(): array 
    {
        return $this->fooTypedArrayAssocStrict;
    }

    /**
     * @module Test
     *
     * @param string|int $fooTypedArrayAssocStrictKey
     * @param string $fooTypedArrayAssocStrictValue
     *
     * @return $this
     */
    public function addFooTypedArrayAssocStrict($fooTypedArrayAssocStrictKey, $fooTypedArrayAssocStrictValue)
    {
        $this->fooTypedArrayAssocStrict[$fooTypedArrayAssocStrictKey] = $fooTypedArrayAssocStrictValue;
        $this->modifiedProperties[self::FOO_TYPED_ARRAY_ASSOC_STRICT] = true;

        return $this;
    }

    /**
    * @module Test
    *
    * @return bool
    */
    public function hasFooTypedArrayAssocStrict(): bool
    {
        return isset($this->fooTypedArrayAssocStrict);
    }

    /**
     * @param array $data
     * @param bool $ignoreMissingProperty
     * @return StrictFooBarTransfer
     */
    public function fromArray(array $data, $ignoreMissingProperty = false)
    {
        foreach ($data as $property => $value) {
            $normalizedPropertyName = $this->transferPropertyNameMap[$property] ?? null;

            switch ($normalizedPropertyName) {
                case 'propScalar':
                case 'propScalarStrict':
                case 'propArray':
                case 'propArrayStrict':
                case 'fooTypedArray':
                case 'fooTypedArrayStrict':
                case 'fooTypedArrayAssoc':
                case 'fooTypedArrayAssocStrict':
                    $this->$normalizedPropertyName = $value;
                    $this->modifiedProperties[$normalizedPropertyName] = true;
                    break;
                case 'propDummyTransfer':
                    if (is_array($value)) {
                        $type = $this->transferMetadata[$normalizedPropertyName]['type'];
                        /** @var \Spryker\Shared\Kernel\Transfer\TransferInterface $transferObject */
                        $value = (new $type())->fromArray($value, $ignoreMissingProperty);
                    }
                    $this->$normalizedPropertyName = $value;
                    $this->modifiedProperties[$normalizedPropertyName] = true;

                    break;
                case 'propDummyTransferCollection':
                    $elementType = $this->transferMetadata[$normalizedPropertyName]['type'];
                    $this->$normalizedPropertyName = $this->processArrayObject($elementType, $value, $ignoreMissingProperty);
                    $this->modifiedProperties[$normalizedPropertyName] = true;
                    break;
                case 'propDecimal':
                case 'propDecimalStrict':
                    $this->assignValueObject($normalizedPropertyName, $value);
                    break;
                default:
                    if (!$ignoreMissingProperty) {
                        throw new \InvalidArgumentException(sprintf('Missing property `%s` in `%s`', $property, static::class));
                    }
            }
        }

        return $this;
    }

    /**
    * @param bool $isRecursive
    * @param bool $camelCasedKeys
    * @return array
    */
    public function modifiedToArray($isRecursive = true, $camelCasedKeys = false)
    {
        if ($isRecursive && !$camelCasedKeys) {
            return $this->modifiedToArrayRecursiveNotCamelCased();
        }
        if ($isRecursive && $camelCasedKeys) {
            return $this->modifiedToArrayRecursiveCamelCased();
        }
        if (!$isRecursive && $camelCasedKeys) {
            return $this->modifiedToArrayNotRecursiveCamelCased();
        }
        if (!$isRecursive && !$camelCasedKeys) {
            return $this->modifiedToArrayNotRecursiveNotCamelCased();
        }
    }

    /**
    * @param bool $isRecursive
    * @param bool $camelCasedKeys
    * @return array
    */
    public function toArray($isRecursive = true, $camelCasedKeys = false)
    {
        if ($isRecursive && !$camelCasedKeys) {
            return $this->toArrayRecursiveNotCamelCased();
        }
        if ($isRecursive && $camelCasedKeys) {
            return $this->toArrayRecursiveCamelCased();
        }
        if (!$isRecursive && !$camelCasedKeys) {
            return $this->toArrayNotRecursiveNotCamelCased();
        }
        if (!$isRecursive && $camelCasedKeys) {
            return $this->toArrayNotRecursiveCamelCased();
        }
    }

    /**
    * @param mixed $value
    * @param bool $isRecursive
    * @param bool $camelCasedKeys
    * @return array
    */
    protected function addValuesToCollectionModified($value, $isRecursive, $camelCasedKeys)
    {
        $result = [];
        foreach ($value as $elementKey => $arrayElement) {
            if ($arrayElement instanceof AbstractTransfer) {
                $result[$elementKey] = $arrayElement->modifiedToArray($isRecursive, $camelCasedKeys);
                continue;
            }
            $result[$elementKey] = $arrayElement;
        }

        return $result;
    }

    /**
    * @param mixed $value
    * @param bool $isRecursive
    * @param bool $camelCasedKeys
    * @return array
    */
    protected function addValuesToCollection($value, $isRecursive, $camelCasedKeys)
    {
        $result = [];
        foreach ($value as $elementKey => $arrayElement) {
            if ($arrayElement instanceof AbstractTransfer) {
                $result[$elementKey] = $arrayElement->toArray($isRecursive, $camelCasedKeys);
                continue;
            }
            $result[$elementKey] = $arrayElement;
        }

        return $result;
    }

    /**
    * @return array
    */
    public function modifiedToArrayRecursiveCamelCased()
    {
        $values = [];
        foreach ($this->modifiedProperties as $property => $_) {
            $value = $this->$property;

            $arrayKey = $property;

            if ($value instanceof AbstractTransfer) {
                $values[$arrayKey] = $value->modifiedToArray(true, true);
                continue;
            }
            switch ($property) {
                case 'propScalar':
                case 'propScalarStrict':
                case 'propArray':
                case 'propArrayStrict':
                case 'fooTypedArray':
                case 'fooTypedArrayStrict':
                case 'fooTypedArrayAssoc':
                case 'fooTypedArrayAssocStrict':
                case 'propDecimal':
                case 'propDecimalStrict':
                    $values[$arrayKey] = $value;
                    break;
                case 'propDummyTransfer':
                    $values[$arrayKey] = $value instanceof AbstractTransfer ? $value->modifiedToArray(true, true) : $value;
                    break;
                case 'propDummyTransferCollection':
                    $values[$arrayKey] = $value ? $this->addValuesToCollectionModified($value, true, true) : $value;
                    break;
            }
        }

        return $values;
    }

    /**
    * @return array
    */
    public function modifiedToArrayRecursiveNotCamelCased()
    {
        $values = [];
        foreach ($this->modifiedProperties as $property => $_) {
            $value = $this->$property;

            $arrayKey = $this->transferMetadata[$property]['name_underscore'];

            if ($value instanceof AbstractTransfer) {
                $values[$arrayKey] = $value->modifiedToArray(true, false);
                continue;
            }
            switch ($property) {
                case 'propScalar':
                case 'propScalarStrict':
                case 'propArray':
                case 'propArrayStrict':
                case 'fooTypedArray':
                case 'fooTypedArrayStrict':
                case 'fooTypedArrayAssoc':
                case 'fooTypedArrayAssocStrict':
                case 'propDecimal':
                case 'propDecimalStrict':
                    $values[$arrayKey] = $value;
                    break;
                case 'propDummyTransfer':
                    $values[$arrayKey] = $value instanceof AbstractTransfer ? $value->modifiedToArray(true, false) : $value;
                    break;
                case 'propDummyTransferCollection':
                    $values[$arrayKey] = $value ? $this->addValuesToCollectionModified($value, true, false) : $value;
                    break;
            }
        }

        return $values;
    }

    /**
    * @return array
    */
    public function modifiedToArrayNotRecursiveNotCamelCased()
    {
        $values = [];
        foreach ($this->modifiedProperties as $property => $_) {
            $value = $this->$property;

            $arrayKey = $this->transferMetadata[$property]['name_underscore'];

            $values[$arrayKey] = $value;
        }

        return $values;
    }

    /**
    * @return array
    */
    public function modifiedToArrayNotRecursiveCamelCased()
    {
        $values = [];
        foreach ($this->modifiedProperties as $property => $_) {
            $value = $this->$property;

            $arrayKey = $property;

            $values[$arrayKey] = $value;
        }

        return $values;
    }

    /**
    * @return void
    */
    protected function initCollectionProperties()
    {
        $this->propDummyTransferCollection = $this->propDummyTransferCollection ?: new ArrayObject();
    }

    /**
    * @return array
    */
    public function toArrayNotRecursiveCamelCased()
    {
        return [
            'propScalar' => $this->propScalar,
            'propScalarStrict' => $this->propScalarStrict,
            'propArray' => $this->propArray,
            'propArrayStrict' => $this->propArrayStrict,
            'fooTypedArray' => $this->fooTypedArray,
            'fooTypedArrayStrict' => $this->fooTypedArrayStrict,
            'fooTypedArrayAssoc' => $this->fooTypedArrayAssoc,
            'fooTypedArrayAssocStrict' => $this->fooTypedArrayAssocStrict,
            'propDummyTransfer' => $this->propDummyTransfer,
            'propDummyTransferCollection' => $this->propDummyTransferCollection,
            'propDecimal' => $this->propDecimal,
            'propDecimalStrict' => $this->propDecimalStrict,
        ];
    }

    /**
    * @return array
    */
    public function toArrayNotRecursiveNotCamelCased()
    {
        return [
            'prop_scalar' => $this->propScalar,
            'prop_scalar_strict' => $this->propScalarStrict,
            'prop_array' => $this->propArray,
            'prop_array_strict' => $this->propArrayStrict,
            'foo_typed_array' => $this->fooTypedArray,
            'foo_typed_array_strict' => $this->fooTypedArrayStrict,
            'foo_typed_array_assoc' => $this->fooTypedArrayAssoc,
            'foo_typed_array_assoc_strict' => $this->fooTypedArrayAssocStrict,
            'prop_dummy_transfer' => $this->propDummyTransfer,
            'prop_dummy_transfer_collection' => $this->propDummyTransferCollection,
            'prop_decimal' => $this->propDecimal,
            'prop_decimal_strict' => $this->propDecimalStrict,
        ];
    }

    /**
    * @return array
    */
    public function toArrayRecursiveNotCamelCased()
    {
        return [
            'prop_scalar' => $this->propScalar instanceof AbstractTransfer ? $this->propScalar->toArray(true, false) : $this->propScalar,
            'prop_scalar_strict' => $this->propScalarStrict instanceof AbstractTransfer ? $this->propScalarStrict->toArray(true, false) : $this->propScalarStrict,
            'prop_array' => $this->propArray instanceof AbstractTransfer ? $this->propArray->toArray(true, false) : $this->propArray,
            'prop_array_strict' => $this->propArrayStrict instanceof AbstractTransfer ? $this->propArrayStrict->toArray(true, false) : $this->propArrayStrict,
            'foo_typed_array' => $this->fooTypedArray instanceof AbstractTransfer ? $this->fooTypedArray->toArray(true, false) : $this->fooTypedArray,
            'foo_typed_array_strict' => $this->fooTypedArrayStrict instanceof AbstractTransfer ? $this->fooTypedArrayStrict->toArray(true, false) : $this->fooTypedArrayStrict,
            'foo_typed_array_assoc' => $this->fooTypedArrayAssoc instanceof AbstractTransfer ? $this->fooTypedArrayAssoc->toArray(true, false) : $this->fooTypedArrayAssoc,
            'foo_typed_array_assoc_strict' => $this->fooTypedArrayAssocStrict instanceof AbstractTransfer ? $this->fooTypedArrayAssocStrict->toArray(true, false) : $this->fooTypedArrayAssocStrict,
            'prop_dummy_transfer' => $this->propDummyTransfer instanceof AbstractTransfer ? $this->propDummyTransfer->toArray(true, false) : $this->propDummyTransfer,
            'prop_dummy_transfer_collection' => $this->propDummyTransferCollection instanceof AbstractTransfer ? $this->propDummyTransferCollection->toArray(true, false) : $this->addValuesToCollection($this->propDummyTransferCollection, true, false),
            'prop_decimal' => $this->propDecimal,
            'prop_decimal_strict' => $this->propDecimalStrict,
        ];
    }

    /**
    * @return array
    */
    public function toArrayRecursiveCamelCased()
    {
        return [
            'propScalar' => $this->propScalar instanceof AbstractTransfer ? $this->propScalar->toArray(true, true) : $this->propScalar,
            'propScalarStrict' => $this->propScalarStrict instanceof AbstractTransfer ? $this->propScalarStrict->toArray(true, true) : $this->propScalarStrict,
            'propArray' => $this->propArray instanceof AbstractTransfer ? $this->propArray->toArray(true, true) : $this->propArray,
            'propArrayStrict' => $this->propArrayStrict instanceof AbstractTransfer ? $this->propArrayStrict->toArray(true, true) : $this->propArrayStrict,
            'fooTypedArray' => $this->fooTypedArray instanceof AbstractTransfer ? $this->fooTypedArray->toArray(true, true) : $this->fooTypedArray,
            'fooTypedArrayStrict' => $this->fooTypedArrayStrict instanceof AbstractTransfer ? $this->fooTypedArrayStrict->toArray(true, true) : $this->fooTypedArrayStrict,
            'fooTypedArrayAssoc' => $this->fooTypedArrayAssoc instanceof AbstractTransfer ? $this->fooTypedArrayAssoc->toArray(true, true) : $this->fooTypedArrayAssoc,
            'fooTypedArrayAssocStrict' => $this->fooTypedArrayAssocStrict instanceof AbstractTransfer ? $this->fooTypedArrayAssocStrict->toArray(true, true) : $this->fooTypedArrayAssocStrict,
            'propDummyTransfer' => $this->propDummyTransfer instanceof AbstractTransfer ? $this->propDummyTransfer->toArray(true, true) : $this->propDummyTransfer,
            'propDummyTransferCollection' => $this->propDummyTransferCollection instanceof AbstractTransfer ? $this->propDummyTransferCollection->toArray(true, true) : $this->addValuesToCollection($this->propDummyTransferCollection, true, true),
            'propDecimal' => $this->propDecimal,
            'propDecimalStrict' => $this->propDecimalStrict,
        ];
    }
}
