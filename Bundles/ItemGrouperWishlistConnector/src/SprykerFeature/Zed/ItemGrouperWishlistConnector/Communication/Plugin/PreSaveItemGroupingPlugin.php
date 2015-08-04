<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ItemGrouperWishlistConnector\Communication\Plugin;

use Generated\Shared\Transfer\GroupableContainerTransfer;
use Generated\Shared\Wishlist\WishlistChangeInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\ItemGrouperWishlistConnector\Business\ItemGrouperWishlistConnectorFacade;
use SprykerFeature\Zed\Wishlist\Dependency\PreSavePluginInterface;

/**
 * @method ItemGrouperWishlistConnectorFacade getFacade()
 */
class PreSaveItemGroupingPlugin extends AbstractPlugin implements PreSavePluginInterface
{
    /**
     * @param WishlistChangeInterface $wishlist
     */
    public function trigger(WishlistChangeInterface $wishlist)
    {
        $groupAbleItems = new GroupableContainerTransfer();
        $groupAbleItems->setItems($wishlist->getItems());
        $groupedWishlistItems = $this->getFacade()->groupOrderItems($groupAbleItems);
        $wishlist->setItems($groupedWishlistItems->getItems());
    }
}
