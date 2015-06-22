<?php

namespace SprykerFeature\Client\Cart;

use Generated\Shared\Cart\CartInterface;

interface CartClientInterface
{
    /**
     * @return CartInterface
     */
    public function getCart();

    /**
     * @return CartInterface
     */
    public function clearCart();

    /**
     * @return int
     */
    public function getItemCount();

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return CartInterface
     */
    public function addItem($sku, $quantity = 1);

    /**
     * @param string $sku
     *
     * @return CartInterface
     */
    public function removeItem($sku);

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return CartInterface
     */
    public function decreaseItemQuantity($sku, $quantity = 1);

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return CartInterface
     */
    public function increaseItemQuantity($sku, $quantity = 1);

    /**
     * @return CartInterface
     */
    public function recalculate();
}
