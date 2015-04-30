<?php

namespace SprykerFeature\Zed\Cart\Business\StorageProvider;

use SprykerFeature\Shared\Cart\Transfer\CartInterface;
use SprykerFeature\Shared\Cart\Transfer\ItemCollectionInterface;

interface StorageProviderInterface
{
    /**
     * @param CartInterface $cart
     * @param ItemCollectionInterface $addedItems
     *
     * @return CartInterface
     */
    public function addItems(CartInterface $cart, ItemCollectionInterface $addedItems);

    /**
     * @param CartInterface $cart
     * @param ItemCollectionInterface $removedItems
     *
     * @return CartInterface
     */
    public function removeItems(CartInterface $cart, ItemCollectionInterface $removedItems);

    /**
     * @param CartInterface $cart
     * @param ItemCollectionInterface $increasedItems
     *
     * @return CartInterface
     */
    public function increaseItems(CartInterface $cart, ItemCollectionInterface $increasedItems);

    /**
     * @param CartInterface $cart
     * @param ItemCollectionInterface $decreasedItems
     *
     * @return CartInterface
     */
    public function decreaseItems(CartInterface $cart, ItemCollectionInterface $decreasedItems);
}
