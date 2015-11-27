<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Generated\Shared\Transfer;

use SprykerEngine\Shared\Transfer\AbstractTransfer;
use SprykerEngine\Shared\Transfer\Exception\RequiredTransferPropertyException;
use Generated\Shared\Transfer\ItemTransfer;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 */
class CatFaceTransfer extends AbstractTransfer
{

    const NAME = 'name';

    const ITEM = 'item';

    const ITEMS = 'items';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var ItemTransfer
     */
    protected $item;

    /**
     * @var \ArrayObject|ItemTransfer[]
     */
    protected $items;

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
    ];

    public function __construct()
    {
        $this->items = new \ArrayObject();
    }

    /**
     * @param string $name
     *
     * @bundle Test
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->addModifiedProperty(self::NAME);

        return $this;
    }

    /**
     * @bundle Test
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @throws RequiredTransferPropertyException
     *
     * @bundle Test
     *
     * @return self
     */
    public function requireName()
    {
        $this->assertPropertyIsSet(self::NAME);

        return $this;
    }

    /**
     * @param ItemTransfer $item
     *
     * @bundle Test
     *
     * @return self
     */
    public function setItem(ItemTransfer $item)
    {
        $this->item = $item;
        $this->addModifiedProperty(self::ITEM);

        return $this;
    }

    /**
     * @bundle Test
     *
     * @return ItemTransfer
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @throws RequiredTransferPropertyException
     *
     * @bundle Test
     *
     * @return self
     */
    public function requireItem()
    {
        $this->assertPropertyIsSet(self::ITEM);

        return $this;
    }

    /**
     * @param \ArrayObject|ItemTransfer[] $items
     *
     * @bundle Test
     *
     * @return self
     */
    public function setItems(\ArrayObject $items)
    {
        $this->items = $items;
        $this->addModifiedProperty(self::ITEMS);

        return $this;
    }

    /**
     * @bundle Test
     *
     * @return ItemTransfer[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param ItemTransfer $item
     *
     * @bundle Test
     *
     * @return self
     */
    public function addItem(ItemTransfer $item)
    {
        $this->items[] = $item;
        $this->addModifiedProperty(self::ITEMS);

        return $this;
    }

    /**
     * @throws RequiredTransferPropertyException
     *
     * @bundle Test
     *
     * @return self
     */
    public function requireItems()
    {
        $this->assertCollectionPropertyIsSet(self::ITEMS);

        return $this;
    }

}
