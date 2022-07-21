<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferWishlist\Business;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer;
use Generated\Shared\Transfer\WishlistPreUpdateItemCheckResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantProductOfferWishlist\Business\MerchantProductOfferWishlistBusinessFactory getFactory()
 */
class MerchantProductOfferWishlistFacade extends AbstractFacade implements MerchantProductOfferWishlistFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\MerchantProductOfferWishlist\Business\MerchantProductOfferWishlistFacade::validateWishlistItemProductOfferBeforeCreation()} instead.
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer
     */
    public function checkWishlistItemProductOfferRelation(
        WishlistItemTransfer $wishlistItemTransfer
    ): WishlistPreAddItemCheckResponseTransfer {
        return $this->getFactory()->createWishlistItemRelationChecker()->checkWishlistItemProductOfferRelation($wishlistItemTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer
     */
    public function validateWishlistItemProductOfferBeforeCreation(
        WishlistItemTransfer $wishlistItemTransfer
    ): WishlistPreAddItemCheckResponseTransfer {
        return $this->getFactory()
            ->createWishlistItemProductOfferValidator()
            ->validateWishlistItemProductOfferBeforeCreation($wishlistItemTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\MerchantProductOfferWishlist\Business\MerchantProductOfferWishlistFacade::validateWishlistItemProductOfferBeforeUpdate()} instead.
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreUpdateItemCheckResponseTransfer
     */
    public function checkUpdateWishlistItemProductOfferRelation(
        WishlistItemTransfer $wishlistItemTransfer
    ): WishlistPreUpdateItemCheckResponseTransfer {
        return $this->getFactory()
            ->createWishlistItemRelationChecker()
            ->checkUpdateWishlistItemProductOfferRelation($wishlistItemTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreUpdateItemCheckResponseTransfer
     */
    public function validateWishlistItemProductOfferBeforeUpdate(
        WishlistItemTransfer $wishlistItemTransfer
    ): WishlistPreUpdateItemCheckResponseTransfer {
        return $this->getFactory()
            ->createWishlistItemProductOfferValidator()
            ->validateWishlistItemProductOfferBeforeUpdate($wishlistItemTransfer);
    }
}
