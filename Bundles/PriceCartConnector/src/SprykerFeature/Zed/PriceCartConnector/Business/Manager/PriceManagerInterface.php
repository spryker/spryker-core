<?php

namespace SprykerFeature\Zed\PriceCartConnector\Business\Manager;

use Generated\Shared\PriceCartConnector\CartItemsInterface;

interface PriceManagerInterface
{
    /**
     * @param CartItemsInterface|CartItemsInterface[] $items
     *
     * @return CartItemsInterface|CartItemsInterface[]
     */
    public function addGrossPriceToItems(CartItemsInterface $items);
}
