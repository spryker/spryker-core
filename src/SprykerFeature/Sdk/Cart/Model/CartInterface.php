<?php
namespace SprykerFeature\Sdk\Cart\Model;

use SprykerFeature\Shared\Cart\Transfer\CartInterface as CartTransferInterface;

interface CartInterface
{
    /**
     * @return CartTransferInterface
     */
    public function getCart();

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return CartTransferInterface
     */
    public function addToCart($sku, $quantity = 1);

    /**
     * @param string $sku
     *
     * @return CartTransferInterface
     */
    public function removeFromCart($sku);

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return CartTransferInterface
     */
    public function decreaseItemQuantity($sku, $quantity = 1);

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return CartTransferInterface
     */
    public function increaseItemQuantity($sku, $quantity = 1);

    /**
     * @return CartTransferInterface
     */
    public function recalculate();
}