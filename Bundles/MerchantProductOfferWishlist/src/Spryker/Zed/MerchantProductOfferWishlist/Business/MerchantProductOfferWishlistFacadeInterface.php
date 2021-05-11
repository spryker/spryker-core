<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferWishlist\Business;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer;

interface MerchantProductOfferWishlistFacadeInterface
{
    /**
     * Specification:
     * - Gets product offer collection by `WishlistItem.sku` transfer property.
     * - Checks if product offer exists in collection by `WishlistItem.productOfferReference` transfer object.
     * - Returns `WishlistPreAddItemCheckResponseTransfer.success=true` if product offer found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer
     */
    public function checkWishlistItemProductOfferRelation(WishlistItemTransfer $wishlistItemTransfer): WishlistPreAddItemCheckResponseTransfer;
}
