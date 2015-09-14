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
     * @param ItemInterface[] $itemsCollection
     */
    public function trigger(\ArrayObject $itemsCollection)
    {
        $groupAbleContainerTransfer = new GroupableContainerTransfer();
        $groupAbleContainerTransfer->setItems($itemsCollection);
        $groupAbleContainerTransfer = $this->getFacade()->groupOrderItems($groupAbleContainerTransfer);

        if (count($groupAbleContainerTransfer->getItems()) > 0) {
            $itemsCollection->exchangeArray((array) $groupAbleContainerTransfer->getItems());
        }
    }

}
