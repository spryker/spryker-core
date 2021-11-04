<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationWishlist;

use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\WishlistItemCollectionTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer;

interface ProductConfigurationWishlistClientInterface
{
    /**
     * Specification:
     * - Expands `WishlistItem` with product configuration.
     * - If `has_product_configuration_attached=1` param is provided, expands `WishlistItem` with `ProductConfigurationInstance` stored in `WishlistItem`.
     * - Tries to expand `WishlistItem` with `ProductConfigurationInstance` by SKU otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     * @param array<string, mixed> $params
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandWithProductConfiguration(WishlistItemTransfer $wishlistItemTransfer, array $params): WishlistItemTransfer;

    /**
     * Specification:
     * - Requires `WishlistMoveToCartRequestCollectionTransfer::requests::wishlistItem` to be set.
     * - Expands `WishlistMoveToCartRequestCollectionTransfer` with not valid product configuration items.
     * - Returns expanded `WishlistMoveToCartRequestCollectionTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionDiffTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer
     */
    public function expandWishlistMoveToCartRequestCollection(
        WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer,
        QuoteTransfer $quoteTransfer,
        WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionDiffTransfer
    ): WishlistMoveToCartRequestCollectionTransfer;

    /**
     * Specification:
     * - Requires `WishlistMoveToCartRequestCollectionTransfer::requests::wishlistItem` to be set.
     * - Expands `WishlistItemCollectionTransfer` with successfully added wishlist items to a cart.
     * - Returns expanded `WishlistItemCollectionTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer
     * @param \Generated\Shared\Transfer\WishlistMoveToCartRequestCollectionTransfer $failedWishlistMoveToCartRequestCollectionTransfer
     * @param \Generated\Shared\Transfer\WishlistItemCollectionTransfer $wishlistItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemCollectionTransfer
     */
    public function expandWishlistItemCollection(
        WishlistMoveToCartRequestCollectionTransfer $wishlistMoveToCartRequestCollectionTransfer,
        WishlistMoveToCartRequestCollectionTransfer $failedWishlistMoveToCartRequestCollectionTransfer,
        WishlistItemCollectionTransfer $wishlistItemCollectionTransfer
    ): WishlistItemCollectionTransfer;

    /**
     * Specification:
     * - Requires `ProductConfiguratorRequestTransfer::productConfiguratorRequestData` to be set.
     * - Requires `ProductConfiguratorRequestTransfer::productConfiguratorRequestData::idWishlistItem` to be set.
     * - Requires `ProductConfiguratorRequestTransfer::productConfiguratorRequestData::sku` to be set.
     * - Finds product configuration instance for given wishlist item.
     * - Maps product configuration instance data to `ProductConfiguratorRequestTransfer`.
     * - Sends product configurator access token request.
     * - Returns `ProductConfiguratorRedirectTransfer::isSuccessful` equal to `true` when redirect URL was successfully resolved.
     * - Returns `ProductConfiguratorRedirectTransfer::isSuccessful` equal to `false` otherwise.
     * - Returns `ProductConfiguratorRedirectTransfer::messages` with errors if any exist.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    public function resolveProductConfiguratorAccessTokenRedirect(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRedirectTransfer;

    /**
     * Specification:
     * - Requires `ProductConfiguratorRequestTransfer::productConfiguratorRequestData` to be set.
     * - Requires `ProductConfiguratorRequestTransfer::productConfiguratorRequestData::idWishlistItem` to be set.
     * - Requires `ProductConfiguratorRequestTransfer::productConfiguratorRequestData::sku` to be set.
     * - Maps raw product configurator checksum response.
     * - Validates product configurator checksum response.
     * - Updates wishlist item product configuration.
     * - Returns `ProductConfiguratorResponseProcessorResponseTransfer::isSuccessful` equal to `true` when response was processed.
     * - Returns `ProductConfiguratorResponseProcessorResponseTransfer::isSuccessful` equal to `false` when something went wrong.
     * - Returns `ProductConfiguratorResponseProcessorResponseTransfer::messages` containing error messages, if any was added.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     * @param array $configuratorResponseData
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function processProductConfiguratorCheckSumResponse(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseProcessorResponseTransfer;

    /**
     * Specification:
     * - Expands collection of product price transfers with product configuration prices taken from `ProductViewTransfer`.
     * - Expects `ProductViewTransfer::productConfigurationInstance::prices` to be provided.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function expandPriceProductsWithProductConfigurationPrices(
        array $priceProductTransfers,
        ProductViewTransfer $productViewTransfer
    ): array;
}
