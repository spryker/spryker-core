<?php

namespace SprykerFeature\Shared\Cart\Transfer;


use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

class CartChange extends AbstractTransfer implements CartChangeInterface
{
    /**
     * @var Cart
     */
    protected $cart;

    /**
     * @var ItemCollectionInterface
     */
    protected $changedItems;

    /**
     * @return CartInterface
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * @param CartInterface $cart
     */
    public function setCart(CartInterface $cart)
    {
        $this->cart = $cart;
    }

    /**
     * @return ItemCollectionInterface
     */
    public function getChangedItems()
    {
        return $this->changedItems;
    }

    /**
     * @param ItemCollectionInterface $changedItems
     */
    public function setChangedItems(ItemCollectionInterface $changedItems)
    {
        $this->changedItems = $changedItems;
    }
}
