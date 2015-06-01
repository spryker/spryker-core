<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Business\StorageProvider;

use Generated\Shared\Cart\CartInterface;
use Generated\Shared\Cart\CartItemsInterface;

interface StorageProviderInterface
{
    /**
     * @param CartInterface $cart
     * @param CartItemsInterface $addedItems
     *
     * @return CartInterface
     */
    public function addItems(CartInterface $cart, CartItemsInterface $addedItems);

    /**
     * @param CartInterface $cart
     * @param CartItemsInterface $removedItems
     *
     * @return CartInterface
     */
    public function removeItems(CartInterface $cart, CartItemsInterface $removedItems);

    /**
     * @param CartInterface $cart
     * @param CartItemsInterface $increasedItems
     *
     * @return CartInterface
     */
    public function increaseItems(CartInterface $cart, CartItemsInterface $increasedItems);

    /**
     * @param CartInterface $cart
     * @param CartItemsInterface $decreasedItems
     *
     * @return CartInterface
     */
    public function decreaseItems(CartInterface $cart, CartItemsInterface $decreasedItems);
}
