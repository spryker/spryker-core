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
class FullyStrictTransfer extends AbstractTransfer
{
    public const PROP_SCALAR = 'propScalar';

    public const PROP_DECIMAL = 'propDecimal';

    public const PROP_SIMPLE_ARRAY = 'propSimpleArray';

    public const PROP_ARRAY_SINGULAR = 'propArraySingular';

    public const PROP_DUMMY_ITEM = 'propDummyItem';

    public const PROP_DUMMY_ITEM_COLLECTION = 'propDummyItemCollection';

    public const PROP_TYPED_ARRAY = 'propTypedArray';

    public const PROP_TYPED_ARRAY_ASSOC = 'propTypedArrayAssoc';

    public const PROP_DUMMY_ITEM_COLLECTION_ASSOC = 'propDummyItemCollectionAssoc';

    /**
     * @var int|null
     */
    protected $propScalar;

    /**
     * @var \Spryker\DecimalObject\Decimal|null
     */
    protected $propDecimal;

    /**
     * @var array|null
     */
    protected $propSimpleArray;

    /**
     * @var array
     */
    protected $propArraySingular = [];

    /**
     * @var \Generated\Shared\Transfer\DummyItemTransfer|null
     */
    protected $propDummyItem;

    /**
     * @var \ArrayObject|\Generated\Shared\Transfer\DummyItemTransfer[]
     */
    protected $propDummyItemCollection;

    /**
     * @var string[]
     */
    protected $propTypedArray = [];

    /**
     * @var string[]
     */
    protected $propTypedArrayAssoc = [];

    /**
     * @var \ArrayObject|\Generated\Shared\Transfer\DummyItemTransfer[]
     */
    protected $propDummyItemCollectionAssoc;

    /**
     * @var array
     */
    protected $transferPropertyNameMap = [
        'prop_scalar' => 'propScalar',
        'propScalar' => 'propScalar',
        'PropScalar' => 'propScalar',
        'prop_decimal' => 'propDecimal',
        'propDecimal' => 'propDecimal',
        'PropDecimal' => 'propDecimal',
        'prop_simple_array' => 'propSimpleArray',
        'propSimpleArray' => 'propSimpleArray',
        'PropSimpleArray' => 'propSimpleArray',
        'prop_array_singular' => 'propArraySingular',
        'propArraySingular' => 'propArraySingular',
        'PropArraySingular' => 'propArraySingular',
        'prop_dummy_item' => 'propDummyItem',
        'propDummyItem' => 'propDummyItem',
        'PropDummyItem' => 'propDummyItem',
        'prop_dummy_item_collection' => 'propDummyItemCollection',
        'propDummyItemCollection' => 'propDummyItemCollection',
        'PropDummyItemCollection' => 'propDummyItemCollection',
        'prop_typed_array' => 'propTypedArray',
        'propTypedArray' => 'propTypedArray',
        'PropTypedArray' => 'propTypedArray',
        'prop_typed_array_assoc' => 'propTypedArrayAssoc',
        'propTypedArrayAssoc' => 'propTypedArrayAssoc',
        'PropTypedArrayAssoc' => 'propTypedArrayAssoc',
        'prop_dummy_item_collection_assoc' => 'propDummyItemCollectionAssoc',
        'propDummyItemCollectionAssoc' => 'propDummyItemCollectionAssoc',
        'PropDummyItemCollectionAssoc' => 'propDummyItemCollectionAssoc',
    ];

    /**
     * @var array
     */
    protected $transferMetadata = [
        self::PROP_SCALAR => [
            'type' => 'int',
            'type_shim' => null,
            'name_underscore' => 'prop_scalar',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => true,
        ],
        self::PROP_DECIMAL => [
            'type' => 'Spryker\DecimalObject\Decimal',
            'type_shim' => null,
            'name_underscore' => 'prop_decimal',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => true,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => true,
        ],
        self::PROP_SIMPLE_ARRAY => [
            'type' => 'array',
            'type_shim' => null,
            'name_underscore' => 'prop_simple_array',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => true,
        ],
        self::PROP_ARRAY_SINGULAR => [
            'type' => 'array',
            'type_shim' => null,
            'name_underscore' => 'prop_array_singular',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => true,
        ],
        self::PROP_DUMMY_ITEM => [
            'type' => 'Generated\Shared\Transfer\DummyItemTransfer',
            'type_shim' => null,
            'name_underscore' => 'prop_dummy_item',
            'is_collection' => false,
            'is_transfer' => true,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => true,
        ],
        self::PROP_DUMMY_ITEM_COLLECTION => [
            'type' => 'Generated\Shared\Transfer\DummyItemTransfer',
            'type_shim' => null,
            'name_underscore' => 'prop_dummy_item_collection',
            'is_collection' => true,
            'is_transfer' => true,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => true,
        ],
        self::PROP_TYPED_ARRAY => [
            'type' => 'string[]',
            'type_shim' => null,
            'name_underscore' => 'prop_typed_array',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => true,
        ],
        self::PROP_TYPED_ARRAY_ASSOC => [
            'type' => 'string[]',
            'type_shim' => null,
            'name_underscore' => 'prop_typed_array_assoc',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => true,
            'is_nullable' => false,
            'is_strict' => true,
        ],
        self::PROP_DUMMY_ITEM_COLLECTION_ASSOC => [
            'type' => 'Generated\Shared\Transfer\DummyItemTransfer',
            'type_shim' => null,
            'name_underscore' => 'prop_dummy_item_collection_assoc',
            'is_collection' => true,
            'is_transfer' => true,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => true,
            'is_nullable' => false,
            'is_strict' => true,
        ],
    ];

    /**
     * @module Test
     *
     * @param int|null $propScalar
     *
     * @return $this
     */
    public function setPropScalar(?int $propScalar = null)
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
    public function getPropScalar(): ?int
    {
        return $this->propScalar;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return int
     */
    public function getPropScalarOrFail(): int
    {
        if ($this->propScalar === null) {
            $this->throwNullValueException(static::PROP_SCALAR);
        }

        return $this->propScalar;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
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
    public function getPropDecimal(): ?Decimal
    {
        return $this->propDecimal;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function getPropDecimalOrFail(): Decimal
    {
        if ($this->propDecimal === null) {
            $this->throwNullValueException(static::PROP_DECIMAL);
        }

        return $this->propDecimal;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
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
     * @param array|null $propSimpleArray
     *
     * @return $this
     */
    public function setPropSimpleArray(?array $propSimpleArray = null)
    {
        $this->propSimpleArray = $propSimpleArray;
        $this->modifiedProperties[self::PROP_SIMPLE_ARRAY] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return array|null
     */
    public function getPropSimpleArray(): ?array
    {
        return $this->propSimpleArray;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return array
     */
    public function getPropSimpleArrayOrFail(): array
    {
        if ($this->propSimpleArray === null) {
            $this->throwNullValueException(static::PROP_SIMPLE_ARRAY);
        }

        return $this->propSimpleArray;
    }

    /**
     * @module Test
     *
     * @param mixed $propSimpleArray
     *
     * @return $this
     */
    public function addPropSimpleArray($propSimpleArray)
    {
        $this->propSimpleArray[] = $propSimpleArray;
        $this->modifiedProperties[self::PROP_SIMPLE_ARRAY] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requirePropSimpleArray()
    {
        $this->assertPropertyIsSet(self::PROP_SIMPLE_ARRAY);

        return $this;
    }

    /**
     * @module Test
     *
     * @param array|null $propArraySingular
     *
     * @return $this
     */
    public function setPropArraySingular(array $propArraySingular = null)
    {
        if ($propArraySingular === null) {
            $propArraySingular = [];
        }

        $this->propArraySingular = $propArraySingular;
        $this->modifiedProperties[self::PROP_ARRAY_SINGULAR] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return array
     */
    public function getPropArraySingular(): array
    {
        return $this->propArraySingular;
    }

    /**
     * @module Test
     *
     * @param mixed $propArraySingularItem
     *
     * @return $this
     */
    public function addPropArraySingularItem($propArraySingularItem)
    {
        $this->propArraySingular[] = $propArraySingularItem;
        $this->modifiedProperties[self::PROP_ARRAY_SINGULAR] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requirePropArraySingular()
    {
        $this->assertPropertyIsSet(self::PROP_ARRAY_SINGULAR);

        return $this;
    }

    /**
     * @module Test
     *
     * @param \Generated\Shared\Transfer\DummyItemTransfer|null $propDummyItem
     *
     * @return $this
     */
    public function setPropDummyItem(?DummyItemTransfer $propDummyItem = null)
    {
        $this->propDummyItem = $propDummyItem;
        $this->modifiedProperties[self::PROP_DUMMY_ITEM] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return \Generated\Shared\Transfer\DummyItemTransfer|null
     */
    public function getPropDummyItem(): ?DummyItemTransfer
    {
        return $this->propDummyItem;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return \Generated\Shared\Transfer\DummyItemTransfer
     */
    public function getPropDummyItemOrFail(): DummyItemTransfer
    {
        if ($this->propDummyItem === null) {
            $this->throwNullValueException(static::PROP_DUMMY_ITEM);
        }

        return $this->propDummyItem;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requirePropDummyItem()
    {
        $this->assertPropertyIsSet(self::PROP_DUMMY_ITEM);

        return $this;
    }

    /**
     * @module Test
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\DummyItemTransfer[] $propDummyItemCollection
     *
     * @return $this
     */
    public function setPropDummyItemCollection(ArrayObject $propDummyItemCollection)
    {
        $this->propDummyItemCollection = new ArrayObject();

        foreach ($propDummyItemCollection as $key => $value) {
            $args = [$value];

            if ($this->transferMetadata[static::PROP_DUMMY_ITEM_COLLECTION]['is_associative']) {
                $args = [$key, $value];
            }

            $this->addPropDummyItemCollection(...$args);
        }

        $this->modifiedProperties[self::PROP_DUMMY_ITEM_COLLECTION] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\DummyItemTransfer[]
     */
    public function getPropDummyItemCollection(): ArrayObject
    {
        return $this->propDummyItemCollection;
    }

    /**
     * @module Test
     *
     * @param \Generated\Shared\Transfer\DummyItemTransfer $propDummyItemCollection
     *
     * @return $this
     */
    public function addPropDummyItemCollection(DummyItemTransfer $propDummyItemCollection)
    {
        $this->propDummyItemCollection[] = $propDummyItemCollection;
        $this->modifiedProperties[self::PROP_DUMMY_ITEM_COLLECTION] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requirePropDummyItemCollection()
    {
        $this->assertCollectionPropertyIsSet(self::PROP_DUMMY_ITEM_COLLECTION);

        return $this;
    }

    /**
     * @module Test
     *
     * @param string[]|null $propTypedArray
     *
     * @return $this
     */
    public function setPropTypedArray(array $propTypedArray = null)
    {
        if ($propTypedArray === null) {
            $propTypedArray = [];
        }

        $this->propTypedArray = [];

        foreach ($propTypedArray as $key => $value) {
            $args = [$value];

            if ($this->transferMetadata[static::PROP_TYPED_ARRAY]['is_associative']) {
                $args = [$key, $value];
            }

            $this->addPropTypedArray(...$args);
        }

        $this->modifiedProperties[self::PROP_TYPED_ARRAY] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return string[]
     */
    public function getPropTypedArray(): array
    {
        return $this->propTypedArray;
    }

    /**
     * @module Test
     *
     * @param string $propTypedArray
     *
     * @return $this
     */
    public function addPropTypedArray(string $propTypedArray)
    {
        $this->propTypedArray[] = $propTypedArray;
        $this->modifiedProperties[self::PROP_TYPED_ARRAY] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requirePropTypedArray()
    {
        $this->assertPropertyIsSet(self::PROP_TYPED_ARRAY);

        return $this;
    }

    /**
     * @module Test
     *
     * @param string[]|null $propTypedArrayAssoc
     *
     * @return $this
     */
    public function setPropTypedArrayAssoc(array $propTypedArrayAssoc = null)
    {
        if ($propTypedArrayAssoc === null) {
            $propTypedArrayAssoc = [];
        }

        $this->propTypedArrayAssoc = [];

        foreach ($propTypedArrayAssoc as $key => $value) {
            $args = [$value];

            if ($this->transferMetadata[static::PROP_TYPED_ARRAY_ASSOC]['is_associative']) {
                $args = [$key, $value];
            }

            $this->addPropTypedArrayAssocSingular(...$args);
        }

        $this->modifiedProperties[self::PROP_TYPED_ARRAY_ASSOC] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return string[]
     */
    public function getPropTypedArrayAssoc(): array
    {
        return $this->propTypedArrayAssoc;
    }

    /**
     * @module Test
     *
     * @param string|int $propTypedArrayAssocSingularKey
     * @param string $propTypedArrayAssocSingularValue
     *
     * @return $this
     */
    public function addPropTypedArrayAssocSingular($propTypedArrayAssocSingularKey, string $propTypedArrayAssocSingularValue)
    {
        $this->propTypedArrayAssoc[$propTypedArrayAssocSingularKey] = $propTypedArrayAssocSingularValue;
        $this->modifiedProperties[self::PROP_TYPED_ARRAY_ASSOC] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requirePropTypedArrayAssoc()
    {
        $this->assertPropertyIsSet(self::PROP_TYPED_ARRAY_ASSOC);

        return $this;
    }

    /**
     * @module Test
     *
     * @param string|int $key
     *
     * @return \Generated\Shared\Transfer\string
     */
    public function getPropTypedArrayAssocSingular($key): string
    {
        return $this->propTypedArrayAssoc[$key];
    }


    /**
     * @module Test
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\DummyItemTransfer[] $propDummyItemCollectionAssoc
     *
     * @return $this
     */
    public function setPropDummyItemCollectionAssoc(ArrayObject $propDummyItemCollectionAssoc)
    {
        $this->propDummyItemCollectionAssoc = new ArrayObject();

        foreach ($propDummyItemCollectionAssoc as $key => $value) {
            $args = [$value];

            if ($this->transferMetadata[static::PROP_DUMMY_ITEM_COLLECTION_ASSOC]['is_associative']) {
                $args = [$key, $value];
            }

            $this->addPropDummyItemCollectionAssocSingular(...$args);
        }

        $this->modifiedProperties[self::PROP_DUMMY_ITEM_COLLECTION_ASSOC] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\DummyItemTransfer[]
     */
    public function getPropDummyItemCollectionAssoc(): ArrayObject
    {
        return $this->propDummyItemCollectionAssoc;
    }

    /**
     * @module Test
     *
     * @param string|int $propDummyItemCollectionAssocSingularKey
     * @param \Generated\Shared\Transfer\DummyItemTransfer $propDummyItemCollectionAssocSingularValue
     *
     * @return $this
     */
    public function addPropDummyItemCollectionAssocSingular($propDummyItemCollectionAssocSingularKey, DummyItemTransfer $propDummyItemCollectionAssocSingularValue)
    {
        $this->propDummyItemCollectionAssoc[$propDummyItemCollectionAssocSingularKey] = $propDummyItemCollectionAssocSingularValue;
        $this->modifiedProperties[self::PROP_DUMMY_ITEM_COLLECTION_ASSOC] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requirePropDummyItemCollectionAssoc()
    {
        $this->assertCollectionPropertyIsSet(self::PROP_DUMMY_ITEM_COLLECTION_ASSOC);

        return $this;
    }

    /**
     * @module Test
     *
     * @param string|int $key
     *
     * @return \Generated\Shared\Transfer\DummyItemTransfer
     */
    public function getPropDummyItemCollectionAssocSingular($key): DummyItemTransfer
    {
        return $this->propDummyItemCollectionAssoc[$key];
    }


    /**
     * @param array $data
     * @param bool $ignoreMissingProperty
     * @return FullyStrictTransfer
     */
    public function fromArray(array $data, $ignoreMissingProperty = false)
    {
        foreach ($data as $property => $value) {
            $normalizedPropertyName = $this->transferPropertyNameMap[$property] ?? null;

            switch ($normalizedPropertyName) {
                case 'propScalar':
                case 'propSimpleArray':
                case 'propArraySingular':
                case 'propTypedArray':
                case 'propTypedArrayAssoc':
                    $this->$normalizedPropertyName = $value;
                    $this->modifiedProperties[$normalizedPropertyName] = true;
                    break;
                case 'propDummyItem':
                    if (is_array($value)) {
                        $type = $this->transferMetadata[$normalizedPropertyName]['type'];
                        /** @var \Spryker\Shared\Kernel\Transfer\TransferInterface $transferObject */
                        $value = (new $type())->fromArray($value, $ignoreMissingProperty);
                    }

                    if ($this->isPropertyStrict($normalizedPropertyName)) {
                        $this->assertInstanceOfTransfer($normalizedPropertyName, $value);
                    }
                    $this->$normalizedPropertyName = $value;
                    $this->modifiedProperties[$normalizedPropertyName] = true;

                    break;
                case 'propDummyItemCollection':
                case 'propDummyItemCollectionAssoc':
                    $elementType = $this->transferMetadata[$normalizedPropertyName]['type'];
                    $this->$normalizedPropertyName = $this->processArrayObject($elementType, $value, $ignoreMissingProperty);
                    $this->modifiedProperties[$normalizedPropertyName] = true;
                    break;
                case 'propDecimal':
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
                case 'propSimpleArray':
                case 'propArraySingular':
                case 'propTypedArray':
                case 'propTypedArrayAssoc':
                case 'propDecimal':
                    $values[$arrayKey] = $value;
                    break;
                case 'propDummyItem':
                    $values[$arrayKey] = $value instanceof AbstractTransfer ? $value->modifiedToArray(true, true) : $value;
                    break;
                case 'propDummyItemCollection':
                case 'propDummyItemCollectionAssoc':
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
                case 'propSimpleArray':
                case 'propArraySingular':
                case 'propTypedArray':
                case 'propTypedArrayAssoc':
                case 'propDecimal':
                    $values[$arrayKey] = $value;
                    break;
                case 'propDummyItem':
                    $values[$arrayKey] = $value instanceof AbstractTransfer ? $value->modifiedToArray(true, false) : $value;
                    break;
                case 'propDummyItemCollection':
                case 'propDummyItemCollectionAssoc':
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
        $this->propDummyItemCollection = $this->propDummyItemCollection ?: new ArrayObject();
        $this->propDummyItemCollectionAssoc = $this->propDummyItemCollectionAssoc ?: new ArrayObject();
    }

    /**
    * @return array
    */
    public function toArrayNotRecursiveCamelCased()
    {
        return [
            'propScalar' => $this->propScalar,
            'propSimpleArray' => $this->propSimpleArray,
            'propArraySingular' => $this->propArraySingular,
            'propTypedArray' => $this->propTypedArray,
            'propTypedArrayAssoc' => $this->propTypedArrayAssoc,
            'propDummyItem' => $this->propDummyItem,
            'propDummyItemCollection' => $this->propDummyItemCollection,
            'propDummyItemCollectionAssoc' => $this->propDummyItemCollectionAssoc,
            'propDecimal' => $this->propDecimal,
        ];
    }

    /**
    * @return array
    */
    public function toArrayNotRecursiveNotCamelCased()
    {
        return [
            'prop_scalar' => $this->propScalar,
            'prop_simple_array' => $this->propSimpleArray,
            'prop_array_singular' => $this->propArraySingular,
            'prop_typed_array' => $this->propTypedArray,
            'prop_typed_array_assoc' => $this->propTypedArrayAssoc,
            'prop_dummy_item' => $this->propDummyItem,
            'prop_dummy_item_collection' => $this->propDummyItemCollection,
            'prop_dummy_item_collection_assoc' => $this->propDummyItemCollectionAssoc,
            'prop_decimal' => $this->propDecimal,
        ];
    }

    /**
    * @return array
    */
    public function toArrayRecursiveNotCamelCased()
    {
        return [
            'prop_scalar' => $this->propScalar instanceof AbstractTransfer ? $this->propScalar->toArray(true, false) : $this->propScalar,
            'prop_simple_array' => $this->propSimpleArray instanceof AbstractTransfer ? $this->propSimpleArray->toArray(true, false) : $this->propSimpleArray,
            'prop_array_singular' => $this->propArraySingular instanceof AbstractTransfer ? $this->propArraySingular->toArray(true, false) : $this->propArraySingular,
            'prop_typed_array' => $this->propTypedArray instanceof AbstractTransfer ? $this->propTypedArray->toArray(true, false) : $this->propTypedArray,
            'prop_typed_array_assoc' => $this->propTypedArrayAssoc instanceof AbstractTransfer ? $this->propTypedArrayAssoc->toArray(true, false) : $this->propTypedArrayAssoc,
            'prop_dummy_item' => $this->propDummyItem instanceof AbstractTransfer ? $this->propDummyItem->toArray(true, false) : $this->propDummyItem,
            'prop_dummy_item_collection' => $this->propDummyItemCollection instanceof AbstractTransfer ? $this->propDummyItemCollection->toArray(true, false) : $this->addValuesToCollection($this->propDummyItemCollection, true, false),
            'prop_dummy_item_collection_assoc' => $this->propDummyItemCollectionAssoc instanceof AbstractTransfer ? $this->propDummyItemCollectionAssoc->toArray(true, false) : $this->addValuesToCollection($this->propDummyItemCollectionAssoc, true, false),
            'prop_decimal' => $this->propDecimal,
        ];
    }

    /**
    * @return array
    */
    public function toArrayRecursiveCamelCased()
    {
        return [
            'propScalar' => $this->propScalar instanceof AbstractTransfer ? $this->propScalar->toArray(true, true) : $this->propScalar,
            'propSimpleArray' => $this->propSimpleArray instanceof AbstractTransfer ? $this->propSimpleArray->toArray(true, true) : $this->propSimpleArray,
            'propArraySingular' => $this->propArraySingular instanceof AbstractTransfer ? $this->propArraySingular->toArray(true, true) : $this->propArraySingular,
            'propTypedArray' => $this->propTypedArray instanceof AbstractTransfer ? $this->propTypedArray->toArray(true, true) : $this->propTypedArray,
            'propTypedArrayAssoc' => $this->propTypedArrayAssoc instanceof AbstractTransfer ? $this->propTypedArrayAssoc->toArray(true, true) : $this->propTypedArrayAssoc,
            'propDummyItem' => $this->propDummyItem instanceof AbstractTransfer ? $this->propDummyItem->toArray(true, true) : $this->propDummyItem,
            'propDummyItemCollection' => $this->propDummyItemCollection instanceof AbstractTransfer ? $this->propDummyItemCollection->toArray(true, true) : $this->addValuesToCollection($this->propDummyItemCollection, true, true),
            'propDummyItemCollectionAssoc' => $this->propDummyItemCollectionAssoc instanceof AbstractTransfer ? $this->propDummyItemCollectionAssoc->toArray(true, true) : $this->addValuesToCollection($this->propDummyItemCollectionAssoc, true, true),
            'propDecimal' => $this->propDecimal,
        ];
    }
}
