<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Dependency;

use SprykerFeature\Shared\Cart\Transfer\ItemCollectionInterface;
use SprykerFeature\Shared\Cart\Transfer\ItemInterface;

interface ItemExpanderPluginInterface
{
    /**
     * @param ItemCollectionInterface|ItemInterface[] $items
     *
     * @return ItemCollectionInterface|ItemInterface[]
     */
    public function expandItems(ItemCollectionInterface $items);
}
