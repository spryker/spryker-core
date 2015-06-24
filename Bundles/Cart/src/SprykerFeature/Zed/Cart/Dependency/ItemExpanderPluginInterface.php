<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Dependency;

use SprykerFeature\Shared\Cart\Transfer\ItemCollectionInterface;
use Generated\Shared\Cart\CartItemsInterface;

interface ItemExpanderPluginInterface
{
    /**
     * @param CartItemsInterface $items
     *
     * @return CartItemsInterface
     */
    public function expandItems(CartItemsInterface $items);
}
