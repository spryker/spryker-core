<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSwitcher\Communication\Plugin\Wishlist;

use Generated\Shared\Transfer\WishlistItemValidationResponseTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\WishlistExtension\Dependency\Plugin\WishlistItemsValidatorPluginInterface;

/**
 * @method \Spryker\Zed\MerchantSwitcher\Business\MerchantSwitcherFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantSwitcher\MerchantSwitcherConfig getConfig()
 */
class SingleMerchantWishlistItemsValidatorPlugin extends AbstractPlugin implements WishlistItemsValidatorPluginInterface
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
     * @return \Generated\Shared\Transfer\WishlistItemValidationResponseTransfer
     */
    public function validateItems(WishlistTransfer $wishlistTransfer): WishlistItemValidationResponseTransfer
    {
        $validationResponseTransfer = $this->getFacade()
            ->validateWishlistItems($wishlistTransfer);

        return (new WishlistItemValidationResponseTransfer())
            ->setMessages($validationResponseTransfer->getErrors())
            ->setIsSuccess($validationResponseTransfer->getIsSuccessful());
    }
}
