<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ItemGrouperWishlistConnector\Communication\Plugin;

use Generated\Shared\Transfer\GroupableContainerTransfer;
use Generated\Shared\Wishlist\ItemInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\ItemGrouperWishlistConnector\Business\ItemGrouperWishlistConnectorFacade;
use SprykerFeature\Zed\Wishlist\Dependency\PreSavePluginInterface;

/**
 * @method ItemGrouperWishlistConnectorFacade getFacade()
 */
class PreSaveItemGroupingPlugin extends AbstractPlugin implements PreSavePluginInterface
{

    /**
     * @param ItemInterface[] $items
     */
    public function trigger(\ArrayObject $items)
    {
        $groupAbleItems = new GroupableContainerTransfer();
        $groupAbleItems->setItems($items);
        $groupedItems = $this->getFacade()->groupOrderItems($groupAbleItems);

        if (count($groupedItems->getItems()) > 0) {
            $items->exchangeArray((array) $groupedItems->getItems());
        }
    }

}
