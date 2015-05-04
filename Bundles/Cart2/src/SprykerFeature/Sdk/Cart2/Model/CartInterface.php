<?php
namespace SprykerFeature\Sdk\Cart2\Model;

use Generated\Shared\Transfer\Cart2CartInterface as CartTransferInterfaceTransfer;

interface CartInterface
{
    /**
     * @param string $sku
     *
     * @return CartTransferInterface
     */
    public function addToCart($sku);

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
