<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Generated\Shared\Transfer;

use SprykerEngine\Shared\Transfer\AbstractTransfer;
use Generated\Shared\Test\CatFaceInterface as TestCatFaceInterface;
use Generated\Shared\Transfer\OrderItemTransfer;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 */
class CatFaceTransfer extends AbstractTransfer implements TestCatFaceInterface
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var OrderItemTransfer
     */
    protected $item;

    /**
     * @var \ArrayObject
     */
    protected $items;

    public function __construct()
    {
        $this->items = new \ArrayObject();
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->addModifiedProperty('name');

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param OrderItemTransfer $item
     *
     * @return $this
     */
    public function setItem(OrderItemTransfer $item)
    {
        $this->item = $item;
        $this->addModifiedProperty('item');

        return $this;
    }

    /**
     * @return OrderItemTransfer
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param \ArrayObject $items
     *
     * @return $this
     */
    public function setItems(\ArrayObject $items)
    {
        $this->items = $items;
        $this->addModifiedProperty('items');

        return $this;
    }

    /**
     * @return OrderItemTransfer[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param OrderItemTransfer $item
     *
     * @return $this
     */
    public function addItem(OrderItemTransfer $item)
    {
        $this->items[] = $item;
        $this->addModifiedProperty('items');

        return $this;
    }

}
