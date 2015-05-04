<?php
namespace SprykerFeature\Zed\PriceCartConnector\Business\Manager;

use Generated\Shared\Transfer\CartItemCollectionInterfaceTransfer;
use Generated\Shared\Transfer\CartItemInterfaceTransfer;

interface PriceManagerInterface
{
    /**
     * @param ItemCollectionInterface|ItemInterface[] $items
     *
     * @return ItemCollectionInterface|ItemInterface[]
     */
    public function addGrossPriceToItems(ItemCollectionInterface $items);
}
