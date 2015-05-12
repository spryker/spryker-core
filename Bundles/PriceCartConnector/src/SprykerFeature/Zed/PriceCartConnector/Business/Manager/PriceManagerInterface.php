<?php

namespace SprykerFeature\Zed\PriceCartConnector\Business\Manager;

use SprykerFeature\Shared\Cart2\Transfer\ItemCollectionInterface;
use SprykerFeature\Shared\Cart2\Transfer\ItemInterface;

interface PriceManagerInterface
{
    /**
     * @param ItemCollectionInterface|ItemInterface[] $items
     *
     * @return ItemCollectionInterface|ItemInterface[]
     */
    public function addGrossPriceToItems(ItemCollectionInterface $items);
}
