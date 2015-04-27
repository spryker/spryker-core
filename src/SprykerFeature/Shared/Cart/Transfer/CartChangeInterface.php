<?php

namespace SprykerFeature\Shared\Cart\Transfer;

interface CartChangeInterface
{
    /**
     * @return CartInterface
     */
    public function getCart();

    /**
     * @param CartInterface $cart
     */
    public function setCart(CartInterface $cart);

    /**
     * @return ItemCollectionInterface
     */
    public function getChangedItems();

    /**
     * @param ItemCollectionInterface $changedItems
     */
    public function setChangedItems(ItemCollectionInterface $changedItems);
}