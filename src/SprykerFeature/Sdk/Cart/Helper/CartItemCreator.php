<?php

namespace SprykerFeature\Sdk\Cart\Helper;

use SprykerFeature\Shared\Cart\Transfer\CartItem;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;

class CartItemCreator
{
    /**
     * @var LocatorLocatorInterface
     */
    protected $locator;

    /**
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(LocatorLocatorInterface $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @param string $sku
     * @param string $uniqueIdentifier
     * @param array $productOptions
     * @param int $quantity
     * @return CartItem
     */
    public function createCartItem($sku, $quantity = 1, $uniqueIdentifier = null, array $productOptions = [])
    {
        $newCartItem = new \Generated\Shared\Transfer\CartCartItemTransfer();
        $newCartItem->setSku($sku);
        $newCartItem->setUniqueIdentifier($uniqueIdentifier);
        $newCartItem->setOptions($productOptions);
        $newCartItem->setQuantity($quantity);

        return $newCartItem;
    }

}
