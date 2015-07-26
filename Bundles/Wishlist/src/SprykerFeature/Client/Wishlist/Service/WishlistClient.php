<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Wishlist\Service;

use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Transfer\WishlistChangeTransfer;
use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistInterface;
use Generated\Shared\Wishlist\WishlistItemInterface;
use SprykerEngine\Client\Kernel\Service\AbstractClient;
use SprykerFeature\Client\Wishlist\Service\WishlistDependencyContainer;

/**
 * @method WishlistDependencyContainer getDependencyContainer()
 */
class WishlistClient extends AbstractClient implements WishlistClientInterface
{

    /**
     * @param WishlistItemInterface $wishlistItemTransfer
     */
    public function removeItem(WishlistItemInterface $wishlistItemTransfer)
    {
        $this->getDependencyContainer()
            ->createRemoveAction()
            ->setTransferObject($wishlistItemTransfer)
            ->execute();
    }

    /**
     * @param WishlistItemInterface $wishlistItemTransfer
     */
    public function saveItem(WishlistItemInterface $wishlistItemTransfer)
    {
        $changeTransfer = (new WishlistChangeTransfer())
            ->addItem($wishlistItemTransfer);

        $this->getDependencyContainer()
            ->createSaveAction()
            ->setTransferObject($changeTransfer)
            ->execute();
    }

    /**
     * @return WishlistInterface
     */
    public function getWishlist()
    {
        return $this->getDependencyContainer()
            ->createGetAction()
            ->execute()
            ->getResponse();
    }

    /**
     * @return WishlistInterface
     */
    public function mergeWishlist()
    {
        return $this->getDependencyContainer()
            ->createMergeAction()
            ->execute()
            ->getResponse();

    }



}
