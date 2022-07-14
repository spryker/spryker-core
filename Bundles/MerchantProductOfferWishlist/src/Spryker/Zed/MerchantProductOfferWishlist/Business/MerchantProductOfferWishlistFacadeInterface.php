<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferWishlist\Business;

use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer;
use Generated\Shared\Transfer\WishlistPreUpdateItemCheckResponseTransfer;

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
     * @deprecated use {@link \Spryker\Zed\MerchantProductOfferWishlist\Business\MerchantProductOfferWishlistFacadeInterface::validateWishlistItemProductOfferBeforeCreation()} instead.
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer
     */
    public function checkWishlistItemProductOfferRelation(
        WishlistItemTransfer $wishlistItemTransfer
    ): WishlistPreAddItemCheckResponseTransfer;

    /**
     * Specification:
     * - Requires `WishlistItem.sku` and `WishlistItem.merchantReference` transfer properties to be set if `WishlistItem.productOfferReference` is set.
     * - Checks that product offer belongs to the item with specified SKU.
     * - Checks that product offer belongs to the specified merchant.
     * - Finds an active and approved product offer by `WishlistItem.sku` and `WishlistItem.productOfferReference` transfer properties.
     * - Finds an active and approved merchant by `ProductOffer.merchantReference` transfer property.
     * - Returns `WishlistPreAddItemCheckResponseTransfer.success=true` if the corresponding product offer and merchant found.
     * - Returns `WishlistPreAddItemCheckResponseTransfer.success=false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreAddItemCheckResponseTransfer
     */
    public function validateWishlistItemProductOfferBeforeCreation(
        WishlistItemTransfer $wishlistItemTransfer
    ): WishlistPreAddItemCheckResponseTransfer;

    /**
     * Specification:
     * - Gets product offer collection by `WishlistItem.sku` transfer property.
     * - Checks if product offer exists in collection by `WishlistItem.productOfferReference` transfer object.
     * - Returns `WishlistPreUpdateItemCheckResponseTransfer.success=true` if product offer found.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\MerchantProductOfferWishlist\Business\MerchantProductOfferWishlistFacadeInterface::validateWishlistItemProductOfferBeforeUpdate()} instead.
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreUpdateItemCheckResponseTransfer
     */
    public function checkUpdateWishlistItemProductOfferRelation(
        WishlistItemTransfer $wishlistItemTransfer
    ): WishlistPreUpdateItemCheckResponseTransfer;

    /**
     * Specification:
     * - Requires `WishlistItem.sku` and `WishlistItem.merchantReference` transfer properties to be set if `WishlistItem.productOfferReference` is set.
     * - Checks that product offer belongs to the item with specified SKU.
     * - Checks that product offer belongs to the specified merchant.
     * - Finds an active and approved product offer by `WishlistItem.sku` and `WishlistItem.productOfferReference` transfer properties.
     * - Finds an active and approved merchant by `ProductOffer.merchantReference` transfer property.
     * - Returns `WishlistPreUpdateItemCheckResponseTransfer.success=true` if the corresponding product offer and merchant found.
     * - Returns `WishlistPreUpdateItemCheckResponseTransfer.success=false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistPreUpdateItemCheckResponseTransfer
     */
    public function validateWishlistItemProductOfferBeforeUpdate(
        WishlistItemTransfer $wishlistItemTransfer
    ): WishlistPreUpdateItemCheckResponseTransfer;
}
