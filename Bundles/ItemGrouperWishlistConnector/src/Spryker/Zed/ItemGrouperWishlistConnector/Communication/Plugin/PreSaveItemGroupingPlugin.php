<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ItemGrouperWishlistConnector\Communication\Plugin;

use Generated\Shared\Transfer\GroupableContainerTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Wishlist\Dependency\PreSavePluginInterface;

/**
 * @method \Spryker\Zed\ItemGrouperWishlistConnector\Business\ItemGrouperWishlistConnectorFacade getFacade()
 * @method \Spryker\Zed\ItemGrouperWishlistConnector\Communication\ItemGrouperWishlistConnectorCommunicationFactory getFactory()
 */
class PreSaveItemGroupingPlugin extends AbstractPlugin implements PreSavePluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemsCollection
     *
     * @return void
     */
    public function trigger(\ArrayObject $itemsCollection)
    {
        $groupAbleContainerTransfer = new GroupableContainerTransfer();
        $groupAbleContainerTransfer->setItems($itemsCollection);
        $groupAbleContainerTransfer = $this->getFacade()->groupOrderItems($groupAbleContainerTransfer);

        if (count($groupAbleContainerTransfer->getItems()) > 0) {
            $itemsCollection->exchangeArray((array)$groupAbleContainerTransfer->getItems());
        }
    }

}
