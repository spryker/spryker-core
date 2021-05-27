<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSwitcher\Communication\Plugin\Wishlist;

use Generated\Shared\Transfer\MerchantSwitchRequestTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\WishlistExtension\Dependency\Plugin\WishlistReloadItemsPluginInterface;

/**
 * @method \Spryker\Zed\MerchantSwitcher\Business\MerchantSwitcherFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantSwitcher\MerchantSwitcherConfig getConfig()
 * @method \Spryker\Zed\MerchantSwitcher\Communication\MerchantSwitcherCommunicationFactory getFactory()
 */
class SingleMerchantWishlistReloadItemsPlugin extends AbstractPlugin implements WishlistReloadItemsPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return bool
     */
    public function isApplicable(WishlistTransfer $wishlistTransfer): bool
    {
        return $this->getConfig()->isMerchantSwitcherEnabled() && $wishlistTransfer->getMerchantReference() !== null;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistTransfer $wishlistTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function reloadItems(WishlistTransfer $wishlistTransfer): WishlistTransfer
    {
        $merchantSwitchRequestTransfer = (new MerchantSwitchRequestTransfer())
            ->setWishlist($wishlistTransfer)
            ->setMerchantReference($wishlistTransfer->getMerchantReference());

        return $this->getFacade()
            ->switchMerchantInWishlistItems($merchantSwitchRequestTransfer)
            ->getWishlist();
    }
}
