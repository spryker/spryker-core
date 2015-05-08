<?php

namespace Generated\Shared\Transfer;

use SprykerEngine\Shared\Transfer\AbstractTransfer;

use Generated\Shared\Transfer\OrderItem;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 */
class Transfer extends AbstractTransfer
{

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var OrderItem $item
     */
    protected $item;

    /**
     * @var \ArrayObject $items
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
     * @param OrderItem $item
     *
     * @return $this
     */
    public function setItem(OrderItem $item)
    {
        $this->item = $item;
        $this->addModifiedProperty('item');

        return $this;
    }

    /**
     * @return OrderItem
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
     * @return OrderItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
    * @param OrderItem $item
    *
    * @return $this
    */
    public function addItem(OrderItem $item)
    {
        $this->items[] = $item;
        $this->addModifiedProperty('items');

        return $this;
    }


}
