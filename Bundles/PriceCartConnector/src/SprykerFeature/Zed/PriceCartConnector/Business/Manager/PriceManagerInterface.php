<?php

namespace SprykerFeature\Zed\PriceCartConnector\Business\Manager;

use Generated\Shared\Cart\CartItemsInterface;
use SprykerFeature\Shared\Cart2\Transfer\ItemInterface;

interface PriceManagerInterface
{
    /**
     * @param CartItemsInterface|ItemInterface[] $items
     *
     * @return CartItemsInterface|ItemInterface[]
     */
    public function addGrossPriceToItems(CartItemsInterface $items);
}
