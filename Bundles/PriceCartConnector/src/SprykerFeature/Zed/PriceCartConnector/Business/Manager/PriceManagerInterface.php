<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PriceCartConnector\Business\Manager;

use Generated\Shared\PriceCartConnector\CartItemsInterface;

interface PriceManagerInterface
{
    /**
     * @param CartItemsInterface $items
     *
     * @return CartItemsInterface
     */
    public function addGrossPriceToItems(CartItemsInterface $items);
}
