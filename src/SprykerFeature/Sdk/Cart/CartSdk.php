<?php

namespace SprykerFeature\Sdk\Cart;

use SprykerFeature\Shared\Cart\Transfer\CartItem;
use SprykerFeature\Shared\Sales\Transfer\OrderItemCollection;
use SprykerEngine\Sdk\Kernel\AbstractSdk;

/**
 * Class CartSdk
 * @package SprykerFeature\Sdk\Cart
 */
class CartSdk extends AbstractSdk
{
    /**
     * @param $sku
     * @param int $quantity
     * @param null $uniqueIdentifier
     * @param array $productOptions
     * @return CartItem
     */
    public function createCartItem($sku, $quantity = 1, $uniqueIdentifier = null, array $productOptions = [])
    {
        return $this->getDependencyContainer()->createCartItemCreator()->createCartItem(
            $sku,
            $quantity,
            $uniqueIdentifier,
            $productOptions
        );
    }

    /**
     * @param OrderItemCollection $cartItems
     * @return array
     */
    public function getProductDataForCartItems(OrderItemCollection $cartItems)
    {
        return $this->getDependencyContainer()->createCatalogHelper()->getProductDataForCartItems($cartItems);
    }
}
