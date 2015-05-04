<?php

namespace SprykerFeature\Zed\Cart\Dependency;

use Generated\Shared\Transfer\CartItemCollectionInterfaceTransfer;
use Generated\Shared\Transfer\CartItemInterfaceTransfer;

interface ItemExpanderPluginInterface
{
    /**
     * @param ItemCollectionInterface|ItemInterface[] $items
     *
     * @return ItemCollectionInterface|ItemInterface[]
     */
    public function expandItems(ItemCollectionInterface $items);
}
