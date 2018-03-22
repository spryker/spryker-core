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
    const NAME = 'name';

    const ITEM = 'item';

    const ITEMS = 'items';

    const TYPED_ARRAY = 'typedArray';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var \Generated\Shared\Transfer\ItemTransfer
     */
    protected $item;

    /**
     * @var \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    protected $items;

    /**
     * @var string[]
     */
    protected $typedArray;

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
        ],
        self::ITEM => [
            'type' => 'Generated\Shared\Transfer\ItemTransfer',
            'name_underscore' => 'item',
            'is_collection' => false,
            'is_transfer' => true,
        ],
        self::ITEMS => [
            'type' => 'Generated\Shared\Transfer\ItemTransfer',
            'name_underscore' => 'items',
            'is_collection' => true,
            'is_transfer' => true,
        ],
        self::TYPED_ARRAY => [
            'type' => 'string[]',
            'name_underscore' => 'typed_array',
            'is_collection' => false,
            'is_transfer' => false,
        ],
    ];

    /**
     * @module Test
     *
     * @param string $name
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
     * @return string
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
     * @return \Generated\Shared\Transfer\ItemTransfer
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
}
