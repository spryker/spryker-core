<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Dependency;

use SprykerFeature\Shared\Cart\Transfer\ItemCollectionInterface;
use Generated\Shared\Cart\CartItemTransfer;
use Generated\Shared\Cart\CartItemsInterface;

interface ItemExpanderPluginInterface
{
    /**
     * @param CartItemsInterface|CartItemTransfer[] $items
     *
     * @return CartItemsInterface|CartItemTransfer[]
     */
    public function expandItems(CartItemsInterface $items);
}
