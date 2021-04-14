<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferWishlist\Business;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantProductOfferWishlist\Business\MerchantProductOfferWishlistBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantProductOfferWishlist\Persistence\MerchantProductOfferWishlistRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantProductOfferWishlist\Persistence\MerchantProductOfferWishlistEntityManagerInterface getEntityManager()
 */
class MerchantProductOfferWishlistFacade extends AbstractFacade implements MerchantProductOfferWishlistFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer
     */
    public function checkWishlistItemProductOfferRelation(WishlistItemTransfer $wishlistItemTransfer): WishlistPreAddItemCheckResponseTransfer
    {
        return $this->getFactory()->createWishlistItemRelationChecker()->checkWishlistItemProductOfferRelation($wishlistItemTransfer);
    }
}
