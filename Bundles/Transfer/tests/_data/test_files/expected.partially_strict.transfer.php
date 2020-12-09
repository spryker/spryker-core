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
class PartiallyStrictTransfer extends AbstractTransfer
{
    public const PROP_SCALAR = 'propScalar';

    public const PROP_SCALAR_STRICT = 'propScalarStrict';

    public const PROP_DECIMAL = 'propDecimal';

    public const PROP_DECIMAL_STRICT = 'propDecimalStrict';

    public const PROP_SIMPLE_ARRAY = 'propSimpleArray';

    public const PROP_SIMPLE_ARRAY_STRICT = 'propSimpleArrayStrict';

    public const PROP_DUMMY_ITEM = 'propDummyItem';

    public const PROP_DUMMY_ITEM_STRICT = 'propDummyItemStrict';

    public const PROP_DUMMY_ITEM_COLLECTION = 'propDummyItemCollection';

    public const PROP_DUMMY_ITEM_COLLECTION_STRICT = 'propDummyItemCollectionStrict';

    public const PROP_TYPED_ARRAY = 'propTypedArray';

    public const PROP_TYPED_ARRAY_STRICT = 'propTypedArrayStrict';

    public const PROP_TYPED_ARRAY_ASSOC = 'propTypedArrayAssoc';

    public const PROP_TYPED_ARRAY_ASSOC_STRICT = 'propTypedArrayAssocStrict';

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
    protected $propSimpleArray = [];

    /**
     * @var array|null
     */
    protected $propSimpleArrayStrict;

    /**
     * @var \Generated\Shared\Transfer\DummyItemTransfer|null
     */
    protected $propDummyItem;

    /**
     * @var \Generated\Shared\Transfer\DummyItemTransfer|null
     */
    protected $propDummyItemStrict;

    /**
     * @var \ArrayObject|\Generated\Shared\Transfer\DummyItemTransfer[]
     */
    protected $propDummyItemCollection;

    /**
     * @var \ArrayObject|\Generated\Shared\Transfer\DummyItemTransfer[]
     */
    protected $propDummyItemCollectionStrict;

    /**
     * @var string[]
     */
    protected $propTypedArray = [];

    /**
     * @var string[]
     */
    protected $propTypedArrayStrict = [];

    /**
     * @var string[]
     */
    protected $propTypedArrayAssoc = [];

    /**
     * @var string[]
     */
    protected $propTypedArrayAssocStrict = [];

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
        'prop_simple_array' => 'propSimpleArray',
        'propSimpleArray' => 'propSimpleArray',
        'PropSimpleArray' => 'propSimpleArray',
        'prop_simple_array_strict' => 'propSimpleArrayStrict',
        'propSimpleArrayStrict' => 'propSimpleArrayStrict',
        'PropSimpleArrayStrict' => 'propSimpleArrayStrict',
        'prop_dummy_item' => 'propDummyItem',
        'propDummyItem' => 'propDummyItem',
        'PropDummyItem' => 'propDummyItem',
        'prop_dummy_item_strict' => 'propDummyItemStrict',
        'propDummyItemStrict' => 'propDummyItemStrict',
        'PropDummyItemStrict' => 'propDummyItemStrict',
        'prop_dummy_item_collection' => 'propDummyItemCollection',
        'propDummyItemCollection' => 'propDummyItemCollection',
        'PropDummyItemCollection' => 'propDummyItemCollection',
        'prop_dummy_item_collection_strict' => 'propDummyItemCollectionStrict',
        'propDummyItemCollectionStrict' => 'propDummyItemCollectionStrict',
        'PropDummyItemCollectionStrict' => 'propDummyItemCollectionStrict',
        'prop_typed_array' => 'propTypedArray',
        'propTypedArray' => 'propTypedArray',
        'PropTypedArray' => 'propTypedArray',
        'prop_typed_array_strict' => 'propTypedArrayStrict',
        'propTypedArrayStrict' => 'propTypedArrayStrict',
        'PropTypedArrayStrict' => 'propTypedArrayStrict',
        'prop_typed_array_assoc' => 'propTypedArrayAssoc',
        'propTypedArrayAssoc' => 'propTypedArrayAssoc',
        'PropTypedArrayAssoc' => 'propTypedArrayAssoc',
        'prop_typed_array_assoc_strict' => 'propTypedArrayAssocStrict',
        'propTypedArrayAssocStrict' => 'propTypedArrayAssocStrict',
        'PropTypedArrayAssocStrict' => 'propTypedArrayAssocStrict',
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
        ],
        self::PROP_SCALAR_STRICT => [
            'type' => 'int',
            'type_shim' => null,
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
            'type_shim' => null,
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
            'type_shim' => null,
            'name_underscore' => 'prop_decimal_strict',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => true,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
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
        ],
        self::PROP_SIMPLE_ARRAY_STRICT => [
            'type' => 'array',
            'type_shim' => null,
            'name_underscore' => 'prop_simple_array_strict',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
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
        ],
        self::PROP_DUMMY_ITEM_STRICT => [
            'type' => 'Generated\Shared\Transfer\DummyItemTransfer',
            'type_shim' => null,
            'name_underscore' => 'prop_dummy_item_strict',
            'is_collection' => false,
            'is_transfer' => true,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
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
        ],
        self::PROP_DUMMY_ITEM_COLLECTION_STRICT => [
            'type' => 'Generated\Shared\Transfer\DummyItemTransfer',
            'type_shim' => null,
            'name_underscore' => 'prop_dummy_item_collection_strict',
            'is_collection' => true,
            'is_transfer' => true,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
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
        ],
        self::PROP_TYPED_ARRAY_STRICT => [
            'type' => 'string[]',
            'type_shim' => null,
            'name_underscore' => 'prop_typed_array_strict',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
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
        ],
        self::PROP_TYPED_ARRAY_ASSOC_STRICT => [
            'type' => 'string[]',
            'type_shim' => null,
            'name_underscore' => 'prop_typed_array_assoc_strict',
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
     * @return int
     */
    public function getPropScalarOrFail()
    {
        if ($this->propScalar === null) {
            $this->throwNullValueException(static::PROP_SCALAR);
        }

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
     * @return int
     */
    public function getPropScalarStrictOrFail(): int 
    {
        if ($this->propScalarStrict === null) {
            $this->throwNullValueException(static::PROP_SCALAR_STRICT);
        }

        return $this->propScalarStrict;
    }

    /**
     * @module Test
     *
     * @return $this
     */
    public function requirePropScalarStrict()
    {
        $this->assertPropertyIsSet(self::PROP_SCALAR_STRICT);

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
    public function getPropDecimal()
    {
        return $this->propDecimal;
    }

    /**
     * @module Test
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function getPropDecimalOrFail()
    {
        if ($this->propDecimal === null) {
            $this->throwNullValueException(static::PROP_DECIMAL);
        }

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
     * @return \Spryker\DecimalObject\Decimal
     */
    public function getPropDecimalStrictOrFail(): Decimal 
    {
        if ($this->propDecimalStrict === null) {
            $this->throwNullValueException(static::PROP_DECIMAL_STRICT);
        }

        return $this->propDecimalStrict;
    }

    /**
     * @module Test
     *
     * @return $this
     */
    public function requirePropDecimalStrict()
    {
        $this->assertPropertyIsSet(self::PROP_DECIMAL_STRICT);

        return $this;
    }

    /**
     * @module Test
     *
     * @param array|null $propSimpleArray
     *
     * @return $this
     */
    public function setPropSimpleArray(array $propSimpleArray = null)
    {
        if ($propSimpleArray === null) {
            $propSimpleArray = [];
        }

        $this->propSimpleArray = $propSimpleArray;
        $this->modifiedProperties[self::PROP_SIMPLE_ARRAY] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return array
     */
    public function getPropSimpleArray()
    {
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
     * @param array|null $propSimpleArrayStrict
     *
     * @return $this
     */
    public function setPropSimpleArrayStrict(?array $propSimpleArrayStrict = null)
    {
        $this->propSimpleArrayStrict = $propSimpleArrayStrict;
        $this->modifiedProperties[self::PROP_SIMPLE_ARRAY_STRICT] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return array|null
     */
    public function getPropSimpleArrayStrict(): ?array 
    {
        return $this->propSimpleArrayStrict;
    }

    /**
     * @module Test
     *
     * @return array
     */
    public function getPropSimpleArrayStrictOrFail(): array 
    {
        if ($this->propSimpleArrayStrict === null) {
            $this->throwNullValueException(static::PROP_SIMPLE_ARRAY_STRICT);
        }

        return $this->propSimpleArrayStrict;
    }

    /**
     * @module Test
     *
     * @param mixed $propSimpleArrayStrict
     *
     * @return $this
     */
    public function addPropSimpleArrayStrict($propSimpleArrayStrict)
    {
        $this->propSimpleArrayStrict[] = $propSimpleArrayStrict;
        $this->modifiedProperties[self::PROP_SIMPLE_ARRAY_STRICT] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return $this
     */
    public function requirePropSimpleArrayStrict()
    {
        $this->assertPropertyIsSet(self::PROP_SIMPLE_ARRAY_STRICT);

        return $this;
    }

    /**
     * @module Test
     *
     * @param \Generated\Shared\Transfer\DummyItemTransfer|null $propDummyItem
     *
     * @return $this
     */
    public function setPropDummyItem(DummyItemTransfer $propDummyItem = null)
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
    public function getPropDummyItem()
    {
        return $this->propDummyItem;
    }

    /**
     * @module Test
     *
     * @return \Generated\Shared\Transfer\DummyItemTransfer
     */
    public function getPropDummyItemOrFail()
    {
        if ($this->propDummyItem === null) {
            $this->throwNullValueException(static::PROP_DUMMY_ITEM);
        }

        return $this->propDummyItem;
    }

    /**
     * @module Test
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
     * @param \Generated\Shared\Transfer\DummyItemTransfer|null $propDummyItemStrict
     *
     * @return $this
     */
    public function setPropDummyItemStrict(?DummyItemTransfer $propDummyItemStrict = null)
    {
        $this->propDummyItemStrict = $propDummyItemStrict;
        $this->modifiedProperties[self::PROP_DUMMY_ITEM_STRICT] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return \Generated\Shared\Transfer\DummyItemTransfer|null
     */
    public function getPropDummyItemStrict(): ?DummyItemTransfer 
    {
        return $this->propDummyItemStrict;
    }

    /**
     * @module Test
     *
     * @return \Generated\Shared\Transfer\DummyItemTransfer
     */
    public function getPropDummyItemStrictOrFail(): DummyItemTransfer 
    {
        if ($this->propDummyItemStrict === null) {
            $this->throwNullValueException(static::PROP_DUMMY_ITEM_STRICT);
        }

        return $this->propDummyItemStrict;
    }

    /**
     * @module Test
     *
     * @return $this
     */
    public function requirePropDummyItemStrict()
    {
        $this->assertPropertyIsSet(self::PROP_DUMMY_ITEM_STRICT);

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
        $this->propDummyItemCollection = $propDummyItemCollection;
        $this->modifiedProperties[self::PROP_DUMMY_ITEM_COLLECTION] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\DummyItemTransfer[]
     */
    public function getPropDummyItemCollection()
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
     * @param \ArrayObject|\Generated\Shared\Transfer\DummyItemTransfer[] $propDummyItemCollectionStrict
     *
     * @return $this
     */
    public function setPropDummyItemCollectionStrict(ArrayObject $propDummyItemCollectionStrict)
    {
        $this->propDummyItemCollectionStrict = new ArrayObject();

        foreach ($propDummyItemCollectionStrict as $collectionItem) {
            $this->addPropDummyItemCollectionStrict($collectionItem);
        }

        $this->modifiedProperties[self::PROP_DUMMY_ITEM_COLLECTION_STRICT] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\DummyItemTransfer[]
     */
    public function getPropDummyItemCollectionStrict(): ArrayObject 
    {
        return $this->propDummyItemCollectionStrict;
    }

    /**
     * @module Test
     *
     * @param \Generated\Shared\Transfer\DummyItemTransfer $propDummyItemCollectionStrict
     *
     * @return $this
     */
    public function addPropDummyItemCollectionStrict(DummyItemTransfer $propDummyItemCollectionStrict)
    {
        $this->propDummyItemCollectionStrict[] = $propDummyItemCollectionStrict;
        $this->modifiedProperties[self::PROP_DUMMY_ITEM_COLLECTION_STRICT] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return $this
     */
    public function requirePropDummyItemCollectionStrict()
    {
        $this->assertCollectionPropertyIsSet(self::PROP_DUMMY_ITEM_COLLECTION_STRICT);

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

        $this->propTypedArray = $propTypedArray;
        $this->modifiedProperties[self::PROP_TYPED_ARRAY] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return string[]
     */
    public function getPropTypedArray()
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
    public function addPropTypedArray($propTypedArray)
    {
        $this->propTypedArray[] = $propTypedArray;
        $this->modifiedProperties[self::PROP_TYPED_ARRAY] = true;

        return $this;
    }

    /**
     * @module Test
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
     * @param string[]|null $propTypedArrayStrict
     *
     * @return $this
     */
    public function setPropTypedArrayStrict(array $propTypedArrayStrict = null)
    {
        if ($propTypedArrayStrict === null) {
            $propTypedArrayStrict = [];
        }

        $this->propTypedArrayStrict = [];

        foreach ($propTypedArrayStrict as $collectionItem) {
            $this->addPropTypedArrayStrict($collectionItem);
        }

        $this->modifiedProperties[self::PROP_TYPED_ARRAY_STRICT] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return string[]
     */
    public function getPropTypedArrayStrict(): array 
    {
        return $this->propTypedArrayStrict;
    }

    /**
     * @module Test
     *
     * @param string $propTypedArrayStrict
     *
     * @return $this
     */
    public function addPropTypedArrayStrict(string $propTypedArrayStrict)
    {
        $this->propTypedArrayStrict[] = $propTypedArrayStrict;
        $this->modifiedProperties[self::PROP_TYPED_ARRAY_STRICT] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return $this
     */
    public function requirePropTypedArrayStrict()
    {
        $this->assertPropertyIsSet(self::PROP_TYPED_ARRAY_STRICT);

        return $this;
    }

    /**
     * @module Test
     *
     * @param string[] $propTypedArrayAssoc
     *
     * @return $this
     */
    public function setPropTypedArrayAssoc($propTypedArrayAssoc)
    {
        if ($propTypedArrayAssoc === null) {
            $propTypedArrayAssoc = [];
        }

        $this->propTypedArrayAssoc = $propTypedArrayAssoc;
        $this->modifiedProperties[self::PROP_TYPED_ARRAY_ASSOC] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return string[]
     */
    public function getPropTypedArrayAssoc()
    {
        return $this->propTypedArrayAssoc;
    }

    /**
     * @module Test
     *
     * @param string|int $propTypedArrayAssocKey
     * @param string $propTypedArrayAssocValue
     *
     * @return $this
     */
    public function addPropTypedArrayAssoc($propTypedArrayAssocKey, $propTypedArrayAssocValue)
    {
        $this->propTypedArrayAssoc[$propTypedArrayAssocKey] = $propTypedArrayAssocValue;
        $this->modifiedProperties[self::PROP_TYPED_ARRAY_ASSOC] = true;

        return $this;
    }

    /**
     * @module Test
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
     * @param string[]|null $propTypedArrayAssocStrict
     *
     * @return $this
     */
    public function setPropTypedArrayAssocStrict(array $propTypedArrayAssocStrict = null)
    {
        if ($propTypedArrayAssocStrict === null) {
            $propTypedArrayAssocStrict = [];
        }

        $this->propTypedArrayAssocStrict = [];

        foreach ($propTypedArrayAssocStrict as $key => $collectionItem) {
            $this->addPropTypedArrayAssocStrictSingular($key, $collectionItem);
        }

        $this->modifiedProperties[self::PROP_TYPED_ARRAY_ASSOC_STRICT] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return string[]
     */
    public function getPropTypedArrayAssocStrict(): array 
    {
        return $this->propTypedArrayAssocStrict;
    }

    /**
     * @module Test
     *
     * @param string|int $propTypedArrayAssocStrictSingularKey
     * @param string $propTypedArrayAssocStrictSingularValue
     *
     * @return $this
     */
    public function addPropTypedArrayAssocStrictSingular($propTypedArrayAssocStrictSingularKey, string $propTypedArrayAssocStrictSingularValue)
    {
        $this->propTypedArrayAssocStrict[$propTypedArrayAssocStrictSingularKey] = $propTypedArrayAssocStrictSingularValue;
        $this->modifiedProperties[self::PROP_TYPED_ARRAY_ASSOC_STRICT] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return $this
     */
    public function requirePropTypedArrayAssocStrict()
    {
        $this->assertPropertyIsSet(self::PROP_TYPED_ARRAY_ASSOC_STRICT);

        return $this;
    }

    /**
     * @module Test
     *
     * @param string|int $key
     *
     * @return \Generated\Shared\Transfer\string
     */
    public function getPropTypedArrayAssocStrictSingular($key): string
    {
        return $this->propTypedArrayAssocStrict[$key];
    }


    /**
     * @param array $data
     * @param bool $ignoreMissingProperty
     * @return PartiallyStrictTransfer
     */
    public function fromArray(array $data, $ignoreMissingProperty = false)
    {
        foreach ($data as $property => $value) {
            $normalizedPropertyName = $this->transferPropertyNameMap[$property] ?? null;

            switch ($normalizedPropertyName) {
                case 'propScalar':
                case 'propScalarStrict':
                case 'propSimpleArray':
                case 'propSimpleArrayStrict':
                case 'propTypedArray':
                case 'propTypedArrayStrict':
                case 'propTypedArrayAssoc':
                case 'propTypedArrayAssocStrict':
                    $this->$normalizedPropertyName = $value;
                    $this->modifiedProperties[$normalizedPropertyName] = true;
                    break;
                case 'propDummyItem':
                case 'propDummyItemStrict':
                    if (is_array($value)) {
                        $type = $this->transferMetadata[$normalizedPropertyName]['type'];
                        /** @var \Spryker\Shared\Kernel\Transfer\TransferInterface $transferObject */
                        $value = (new $type())->fromArray($value, $ignoreMissingProperty);
                    }
                    $this->$normalizedPropertyName = $value;
                    $this->modifiedProperties[$normalizedPropertyName] = true;

                    break;
                case 'propDummyItemCollection':
                case 'propDummyItemCollectionStrict':
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
                case 'propSimpleArray':
                case 'propSimpleArrayStrict':
                case 'propTypedArray':
                case 'propTypedArrayStrict':
                case 'propTypedArrayAssoc':
                case 'propTypedArrayAssocStrict':
                case 'propDecimal':
                case 'propDecimalStrict':
                    $values[$arrayKey] = $value;
                    break;
                case 'propDummyItem':
                case 'propDummyItemStrict':
                    $values[$arrayKey] = $value instanceof AbstractTransfer ? $value->modifiedToArray(true, true) : $value;
                    break;
                case 'propDummyItemCollection':
                case 'propDummyItemCollectionStrict':
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
                case 'propSimpleArray':
                case 'propSimpleArrayStrict':
                case 'propTypedArray':
                case 'propTypedArrayStrict':
                case 'propTypedArrayAssoc':
                case 'propTypedArrayAssocStrict':
                case 'propDecimal':
                case 'propDecimalStrict':
                    $values[$arrayKey] = $value;
                    break;
                case 'propDummyItem':
                case 'propDummyItemStrict':
                    $values[$arrayKey] = $value instanceof AbstractTransfer ? $value->modifiedToArray(true, false) : $value;
                    break;
                case 'propDummyItemCollection':
                case 'propDummyItemCollectionStrict':
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
        $this->propDummyItemCollectionStrict = $this->propDummyItemCollectionStrict ?: new ArrayObject();
    }

    /**
    * @return array
    */
    public function toArrayNotRecursiveCamelCased()
    {
        return [
            'propScalar' => $this->propScalar,
            'propScalarStrict' => $this->propScalarStrict,
            'propSimpleArray' => $this->propSimpleArray,
            'propSimpleArrayStrict' => $this->propSimpleArrayStrict,
            'propTypedArray' => $this->propTypedArray,
            'propTypedArrayStrict' => $this->propTypedArrayStrict,
            'propTypedArrayAssoc' => $this->propTypedArrayAssoc,
            'propTypedArrayAssocStrict' => $this->propTypedArrayAssocStrict,
            'propDummyItem' => $this->propDummyItem,
            'propDummyItemStrict' => $this->propDummyItemStrict,
            'propDummyItemCollection' => $this->propDummyItemCollection,
            'propDummyItemCollectionStrict' => $this->propDummyItemCollectionStrict,
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
            'prop_simple_array' => $this->propSimpleArray,
            'prop_simple_array_strict' => $this->propSimpleArrayStrict,
            'prop_typed_array' => $this->propTypedArray,
            'prop_typed_array_strict' => $this->propTypedArrayStrict,
            'prop_typed_array_assoc' => $this->propTypedArrayAssoc,
            'prop_typed_array_assoc_strict' => $this->propTypedArrayAssocStrict,
            'prop_dummy_item' => $this->propDummyItem,
            'prop_dummy_item_strict' => $this->propDummyItemStrict,
            'prop_dummy_item_collection' => $this->propDummyItemCollection,
            'prop_dummy_item_collection_strict' => $this->propDummyItemCollectionStrict,
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
            'prop_simple_array' => $this->propSimpleArray instanceof AbstractTransfer ? $this->propSimpleArray->toArray(true, false) : $this->propSimpleArray,
            'prop_simple_array_strict' => $this->propSimpleArrayStrict instanceof AbstractTransfer ? $this->propSimpleArrayStrict->toArray(true, false) : $this->propSimpleArrayStrict,
            'prop_typed_array' => $this->propTypedArray instanceof AbstractTransfer ? $this->propTypedArray->toArray(true, false) : $this->propTypedArray,
            'prop_typed_array_strict' => $this->propTypedArrayStrict instanceof AbstractTransfer ? $this->propTypedArrayStrict->toArray(true, false) : $this->propTypedArrayStrict,
            'prop_typed_array_assoc' => $this->propTypedArrayAssoc instanceof AbstractTransfer ? $this->propTypedArrayAssoc->toArray(true, false) : $this->propTypedArrayAssoc,
            'prop_typed_array_assoc_strict' => $this->propTypedArrayAssocStrict instanceof AbstractTransfer ? $this->propTypedArrayAssocStrict->toArray(true, false) : $this->propTypedArrayAssocStrict,
            'prop_dummy_item' => $this->propDummyItem instanceof AbstractTransfer ? $this->propDummyItem->toArray(true, false) : $this->propDummyItem,
            'prop_dummy_item_strict' => $this->propDummyItemStrict instanceof AbstractTransfer ? $this->propDummyItemStrict->toArray(true, false) : $this->propDummyItemStrict,
            'prop_dummy_item_collection' => $this->propDummyItemCollection instanceof AbstractTransfer ? $this->propDummyItemCollection->toArray(true, false) : $this->addValuesToCollection($this->propDummyItemCollection, true, false),
            'prop_dummy_item_collection_strict' => $this->propDummyItemCollectionStrict instanceof AbstractTransfer ? $this->propDummyItemCollectionStrict->toArray(true, false) : $this->addValuesToCollection($this->propDummyItemCollectionStrict, true, false),
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
            'propSimpleArray' => $this->propSimpleArray instanceof AbstractTransfer ? $this->propSimpleArray->toArray(true, true) : $this->propSimpleArray,
            'propSimpleArrayStrict' => $this->propSimpleArrayStrict instanceof AbstractTransfer ? $this->propSimpleArrayStrict->toArray(true, true) : $this->propSimpleArrayStrict,
            'propTypedArray' => $this->propTypedArray instanceof AbstractTransfer ? $this->propTypedArray->toArray(true, true) : $this->propTypedArray,
            'propTypedArrayStrict' => $this->propTypedArrayStrict instanceof AbstractTransfer ? $this->propTypedArrayStrict->toArray(true, true) : $this->propTypedArrayStrict,
            'propTypedArrayAssoc' => $this->propTypedArrayAssoc instanceof AbstractTransfer ? $this->propTypedArrayAssoc->toArray(true, true) : $this->propTypedArrayAssoc,
            'propTypedArrayAssocStrict' => $this->propTypedArrayAssocStrict instanceof AbstractTransfer ? $this->propTypedArrayAssocStrict->toArray(true, true) : $this->propTypedArrayAssocStrict,
            'propDummyItem' => $this->propDummyItem instanceof AbstractTransfer ? $this->propDummyItem->toArray(true, true) : $this->propDummyItem,
            'propDummyItemStrict' => $this->propDummyItemStrict instanceof AbstractTransfer ? $this->propDummyItemStrict->toArray(true, true) : $this->propDummyItemStrict,
            'propDummyItemCollection' => $this->propDummyItemCollection instanceof AbstractTransfer ? $this->propDummyItemCollection->toArray(true, true) : $this->addValuesToCollection($this->propDummyItemCollection, true, true),
            'propDummyItemCollectionStrict' => $this->propDummyItemCollectionStrict instanceof AbstractTransfer ? $this->propDummyItemCollectionStrict->toArray(true, true) : $this->addValuesToCollection($this->propDummyItemCollectionStrict, true, true),
            'propDecimal' => $this->propDecimal,
            'propDecimalStrict' => $this->propDecimalStrict,
        ];
    }
}
