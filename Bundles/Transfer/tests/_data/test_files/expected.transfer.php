<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Generated\Shared\Transfer;

use ArrayObject;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 */
class CatFaceTransfer extends AbstractTransfer
{
    public const NAME = 'name';

    public const ITEM = 'item';

    public const ITEMS = 'items';

    public const TYPED_ARRAY = 'typedArray';

    public const TYPED_ASSOCIATIVE_STRING_ARRAY = 'typedAssociativeStringArray';

    public const TYPED_ASSOCIATIVE_COLLECTION = 'typedAssociativeCollection';

    public const TYPED_NOT_ASSOCIATIVE_STRING_ARRAY = 'typedNotAssociativeStringArray';

    public const TYPED_NOT_ASSOCIATIVE_ARRAY = 'typedNotAssociativeArray';

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected $item;

    /**
     * @var \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    protected $items;

    /**
     * @var string[]
     */
    protected $typedArray = [];

    /**
     * @var string[]
     */
    protected $typedAssociativeStringArray = [];

    /**
     * @var \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    protected $typedAssociativeCollection;

    /**
     * @var string[]
     */
    protected $typedNotAssociativeStringArray = [];

    /**
     * @var array
     */
    protected $typedNotAssociativeArray = [];

    /**
     * @var array
     */
    protected $transferPropertyNameMap = [
        'name' => 'name',
        'Name' => 'name',
        'item' => 'item',
        'Item' => 'item',
        'items' => 'items',
        'Items' => 'items',
        'typed_array' => 'typedArray',
        'typedArray' => 'typedArray',
        'TypedArray' => 'typedArray',
        'typed_associative_string_array' => 'typedAssociativeStringArray',
        'typedAssociativeStringArray' => 'typedAssociativeStringArray',
        'TypedAssociativeStringArray' => 'typedAssociativeStringArray',
        'typed_associative_collection' => 'typedAssociativeCollection',
        'typedAssociativeCollection' => 'typedAssociativeCollection',
        'TypedAssociativeCollection' => 'typedAssociativeCollection',
        'typed_not_associative_string_array' => 'typedNotAssociativeStringArray',
        'typedNotAssociativeStringArray' => 'typedNotAssociativeStringArray',
        'TypedNotAssociativeStringArray' => 'typedNotAssociativeStringArray',
        'typed_not_associative_array' => 'typedNotAssociativeArray',
        'typedNotAssociativeArray' => 'typedNotAssociativeArray',
        'TypedNotAssociativeArray' => 'typedNotAssociativeArray',
    ];

    /**
     * @var array
     */
    protected $transferMetadata = [
        self::NAME => [
            'type' => 'string',
            'name_underscore' => 'name',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
        ],
        self::ITEM => [
            'type' => 'Generated\Shared\Transfer\ItemTransfer',
            'name_underscore' => 'item',
            'is_collection' => false,
            'is_transfer' => true,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
        ],
        self::ITEMS => [
            'type' => 'Generated\Shared\Transfer\ItemTransfer',
            'name_underscore' => 'items',
            'is_collection' => true,
            'is_transfer' => true,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
        ],
        self::TYPED_ARRAY => [
            'type' => 'string[]',
            'name_underscore' => 'typed_array',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
        ],
        self::TYPED_ASSOCIATIVE_STRING_ARRAY => [
            'type' => 'string[]',
            'name_underscore' => 'typed_associative_string_array',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => true,
            'is_nullable' => false,
        ],
        self::TYPED_ASSOCIATIVE_COLLECTION => [
            'type' => 'Generated\Shared\Transfer\ItemTransfer',
            'name_underscore' => 'typed_associative_collection',
            'is_collection' => true,
            'is_transfer' => true,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => true,
            'is_nullable' => false,
        ],
        self::TYPED_NOT_ASSOCIATIVE_STRING_ARRAY => [
            'type' => 'string[]',
            'name_underscore' => 'typed_not_associative_string_array',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
        ],
        self::TYPED_NOT_ASSOCIATIVE_ARRAY => [
            'type' => 'array',
            'name_underscore' => 'typed_not_associative_array',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
        ],
    ];

    /**
     * @module Test
     *
     * @param string|null $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->modifiedProperties[self::NAME] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @module Test
     *
     * @return $this
     */
    public function requireName()
    {
        $this->assertPropertyIsSet(self::NAME);

        return $this;
    }

    /**
     * @module Test
     *
     * @param \Generated\Shared\Transfer\ItemTransfer|null $item
     *
     * @return $this
     */
    public function setItem(ItemTransfer $item = null)
    {
        $this->item = $item;
        $this->modifiedProperties[self::ITEM] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @module Test
     *
     * @return $this
     */
    public function requireItem()
    {
        $this->assertPropertyIsSet(self::ITEM);

        return $this;
    }

    /**
     * @module Test
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return $this
     */
    public function setItems(ArrayObject $items)
    {
        $this->items = $items;
        $this->modifiedProperties[self::ITEMS] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @module Test
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $item
     *
     * @return $this
     */
    public function addItem(ItemTransfer $item)
    {
        $this->items[] = $item;
        $this->modifiedProperties[self::ITEMS] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return $this
     */
    public function requireItems()
    {
        $this->assertCollectionPropertyIsSet(self::ITEMS);

        return $this;
    }

    /**
     * @module Test
     *
     * @param string[]|null $typedArray
     *
     * @return $this
     */
    public function setTypedArray(array $typedArray = null)
    {
        if ($typedArray === null) {
            $typedArray = [];
        }

        $this->typedArray = $typedArray;
        $this->modifiedProperties[self::TYPED_ARRAY] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return string[]
     */
    public function getTypedArray()
    {
        return $this->typedArray;
    }

    /**
     * @module Test
     *
     * @param string $typedArray
     *
     * @return $this
     */
    public function addTypedArray($typedArray)
    {
        $this->typedArray[] = $typedArray;
        $this->modifiedProperties[self::TYPED_ARRAY] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return $this
     */
    public function requireTypedArray()
    {
        $this->assertPropertyIsSet(self::TYPED_ARRAY);

        return $this;
    }

    /**
     * @module Test
     *
     * @param string[] $typedAssociativeStringArray
     *
     * @return $this
     */
    public function setTypedAssociativeStringArray($typedAssociativeStringArray)
    {
        $this->typedAssociativeStringArray = $typedAssociativeStringArray;
        $this->modifiedProperties[self::TYPED_ASSOCIATIVE_STRING_ARRAY] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return string[]
     */
    public function getTypedAssociativeStringArray()
    {
        return $this->typedAssociativeStringArray;
    }

    /**
     * @module Test
     *
     * @param string|int $typedAssociativeStringArrayKey
     * @param string $typedAssociativeStringArrayValue
     *
     * @return $this
     */
    public function addTypedAssociativeStringArray($typedAssociativeStringArrayKey, $typedAssociativeStringArrayValue)
    {
        $this->typedAssociativeStringArray[$typedAssociativeStringArrayKey] = $typedAssociativeStringArrayValue;
        $this->modifiedProperties[self::TYPED_ASSOCIATIVE_STRING_ARRAY] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return $this
     */
    public function requireTypedAssociativeStringArray()
    {
        $this->assertPropertyIsSet(self::TYPED_ASSOCIATIVE_STRING_ARRAY);

        return $this;
    }

    /**
     * @module Test
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $typedAssociativeCollection
     *
     * @return $this
     */
    public function setTypedAssociativeCollection(ArrayObject $typedAssociativeCollection)
    {
        $this->typedAssociativeCollection = $typedAssociativeCollection;
        $this->modifiedProperties[self::TYPED_ASSOCIATIVE_COLLECTION] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getTypedAssociativeCollection()
    {
        return $this->typedAssociativeCollection;
    }

    /**
     * @module Test
     *
     * @param string|int $typedAssociativeCollectionKey
     * @param \Generated\Shared\Transfer\ItemTransfer $typedAssociativeCollectionValue
     *
     * @return $this
     */
    public function addTypedAssociativeCollection($typedAssociativeCollectionKey, ItemTransfer $typedAssociativeCollectionValue)
    {
        $this->typedAssociativeCollection[$typedAssociativeCollectionKey] = $typedAssociativeCollectionValue;
        $this->modifiedProperties[self::TYPED_ASSOCIATIVE_COLLECTION] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return $this
     */
    public function requireTypedAssociativeCollection()
    {
        $this->assertCollectionPropertyIsSet(self::TYPED_ASSOCIATIVE_COLLECTION);

        return $this;
    }

    /**
     * @module Test
     *
     * @param string[] $typedNotAssociativeStringArray
     *
     * @return $this
     */
    public function setTypedNotAssociativeStringArray($typedNotAssociativeStringArray)
    {
        $this->typedNotAssociativeStringArray = $typedNotAssociativeStringArray;
        $this->modifiedProperties[self::TYPED_NOT_ASSOCIATIVE_STRING_ARRAY] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return string[]
     */
    public function getTypedNotAssociativeStringArray()
    {
        return $this->typedNotAssociativeStringArray;
    }

    /**
     * @module Test
     *
     * @param string $typedNotAssociativeStringArray
     *
     * @return $this
     */
    public function addTypedNotAssociativeStringArray($typedNotAssociativeStringArray)
    {
        $this->typedNotAssociativeStringArray[] = $typedNotAssociativeStringArray;
        $this->modifiedProperties[self::TYPED_NOT_ASSOCIATIVE_STRING_ARRAY] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return $this
     */
    public function requireTypedNotAssociativeStringArray()
    {
        $this->assertPropertyIsSet(self::TYPED_NOT_ASSOCIATIVE_STRING_ARRAY);

        return $this;
    }

    /**
     * @module Test
     *
     * @param array $typedNotAssociativeArray
     *
     * @return $this
     */
    public function setTypedNotAssociativeArray($typedNotAssociativeArray)
    {
        $this->typedNotAssociativeArray = $typedNotAssociativeArray;
        $this->modifiedProperties[self::TYPED_NOT_ASSOCIATIVE_ARRAY] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return array
     */
    public function getTypedNotAssociativeArray()
    {
        return $this->typedNotAssociativeArray;
    }

    /**
     * @module Test
     *
     * @param mixed $typedNotAssociativeArray
     *
     * @return $this
     */
    public function addTypedNotAssociativeArray($typedNotAssociativeArray)
    {
        $this->typedNotAssociativeArray[] = $typedNotAssociativeArray;
        $this->modifiedProperties[self::TYPED_NOT_ASSOCIATIVE_ARRAY] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return $this
     */
    public function requireTypedNotAssociativeArray()
    {
        $this->assertPropertyIsSet(self::TYPED_NOT_ASSOCIATIVE_ARRAY);

        return $this;
    }

    /**
     * @param array $data
     * @param bool $ignoreMissingProperty
     * @return CatFaceTransfer
     */
    public function fromArray(array $data, $ignoreMissingProperty = false)
    {
        foreach ($data as $property => $value) {
            $property = $this->transferPropertyNameMap[$property] ?? null;

            switch ($property) {
                case 'name':
                case 'typedArray':
                case 'typedAssociativeStringArray':
                case 'typedNotAssociativeStringArray':
                case 'typedNotAssociativeArray':
                    $this->$property = $value;
                    $this->modifiedProperties[$property] = true;
                    break;
                case 'item':
                    if (is_array($value)) {
                        $type = $this->transferMetadata[$property]['type'];
                        /** @var \Spryker\Shared\Kernel\Transfer\TransferInterface $transferObject */
                        $value = (new $type())->fromArray($value, $ignoreMissingProperty);
                    }
                    $this->$property = $value;
                    $this->modifiedProperties[$property] = true;

                    break;
                case 'items':
                case 'typedAssociativeCollection':
                    $elementType = $this->transferMetadata[$property]['type'];
                    $this->$property = $this->processArrayObject($elementType, $value, $ignoreMissingProperty);
                    $this->modifiedProperties[$property] = true;
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
            switch ($property) {
                case 'name':
                case 'typedArray':
                case 'typedAssociativeStringArray':
                case 'typedNotAssociativeStringArray':
                case 'typedNotAssociativeArray':
                    $values[$arrayKey] = $value;
                    break;
                case 'item':
                    $values[$arrayKey] = $value instanceof AbstractTransfer ? $value->modifiedToArray(true, true) : $value;
                    break;
                case 'items':
                case 'typedAssociativeCollection':
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
            switch ($property) {
                case 'name':
                case 'typedArray':
                case 'typedAssociativeStringArray':
                case 'typedNotAssociativeStringArray':
                case 'typedNotAssociativeArray':
                    $values[$arrayKey] = $value;
                    break;
                case 'item':
                    $values[$arrayKey] = $value instanceof AbstractTransfer ? $value->modifiedToArray(true, false) : $value;
                    break;
                case 'items':
                case 'typedAssociativeCollection':
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
        $this->items = $this->items ?: new ArrayObject();
        $this->typedAssociativeCollection = $this->typedAssociativeCollection ?: new ArrayObject();
    }

    /**
    * @return array
    */
    public function toArrayNotRecursiveCamelCased()
    {
        return [
            'name' => $this->name,
            'typedArray' => $this->typedArray,
            'typedAssociativeStringArray' => $this->typedAssociativeStringArray,
            'typedNotAssociativeStringArray' => $this->typedNotAssociativeStringArray,
            'typedNotAssociativeArray' => $this->typedNotAssociativeArray,
            'item' => $this->item,
            'items' => $this->items,
            'typedAssociativeCollection' => $this->typedAssociativeCollection,
        ];
    }

    /**
    * @return array
    */
    public function toArrayNotRecursiveNotCamelCased()
    {
        return [
            'name' => $this->name,
            'typed_array' => $this->typedArray,
            'typed_associative_string_array' => $this->typedAssociativeStringArray,
            'typed_not_associative_string_array' => $this->typedNotAssociativeStringArray,
            'typed_not_associative_array' => $this->typedNotAssociativeArray,
            'item' => $this->item,
            'items' => $this->items,
            'typed_associative_collection' => $this->typedAssociativeCollection,
        ];
    }

    /**
    * @return array
    */
    public function toArrayRecursiveNotCamelCased()
    {
        return [
            'name' => $this->name,
            'typed_array' => $this->typedArray,
            'typed_associative_string_array' => $this->typedAssociativeStringArray,
            'typed_not_associative_string_array' => $this->typedNotAssociativeStringArray,
            'typed_not_associative_array' => $this->typedNotAssociativeArray,
            'item' => $this->item instanceof AbstractTransfer ? $this->item->toArray(true, false) : $this->item,
            'items' => $this->addValuesToCollection($this->items, true, false),
            'typed_associative_collection' => $this->addValuesToCollection($this->typedAssociativeCollection, true, false),
        ];
    }

    /**
    * @return array
    */
    public function toArrayRecursiveCamelCased()
    {
        return [
            'name' => $this->name,
            'typedArray' => $this->typedArray,
            'typedAssociativeStringArray' => $this->typedAssociativeStringArray,
            'typedNotAssociativeStringArray' => $this->typedNotAssociativeStringArray,
            'typedNotAssociativeArray' => $this->typedNotAssociativeArray,
            'item' => $this->item instanceof AbstractTransfer ? $this->item->toArray(true, true) : $this->item,
            'items' => $this->addValuesToCollection($this->items, true, true),
            'typedAssociativeCollection' => $this->addValuesToCollection($this->typedAssociativeCollection, true, true),
        ];
    }
}
