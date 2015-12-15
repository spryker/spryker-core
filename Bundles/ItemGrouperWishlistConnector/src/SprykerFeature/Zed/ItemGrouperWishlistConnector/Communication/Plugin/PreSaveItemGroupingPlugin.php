<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ItemGrouperWishlistConnector\Communication\Plugin;

use Generated\Shared\Transfer\GroupableContainerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ItemGrouperWishlistConnector\Business\ItemGrouperWishlistConnectorFacade;
use Spryker\Zed\Wishlist\Dependency\PreSavePluginInterface;

/**
 * @method ItemGrouperWishlistConnectorFacade getFacade()
 */
class PreSaveItemGroupingPlugin extends AbstractPlugin implements PreSavePluginInterface
{

    /**
     * @param ItemTransfer[] $itemsCollection
     *
     * @return void
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
