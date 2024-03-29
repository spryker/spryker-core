<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;

interface PriceProductOfferFacadeInterface
{
    /**
     * Specification:
     * - Persists Product prices using the PriceProduct facade.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function saveProductOfferPrices(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer;

    /**
     * Specification:
     * - Persists Price Product Offer entities.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function savePriceProductOfferRelation(PriceProductTransfer $priceProductTransfer): PriceProductTransfer;

    /**
     * Specification:
     * - Expands provided ProductOfferTransfer with PriceProduct transfers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function expandProductOfferWithPrices(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer;

    /**
     * Specification:
     * - Validates product offer prices collection.
     * - Checks if there are duplicated prices for store-currency-gross-net combinations.
     * - Checks that currency assigned to a store per prices.
     * - Executes `PriceProductOfferValidatorPluginInterface` plugin stack.
     * - Returns ValidationResponseTransfer transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfers
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validateProductOfferPrices(PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfers): ValidationResponseTransfer;

    /**
     * Specification:
     * - Deletes entities from `spy_price_product_offer` by PriceProductOfferCollectionTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
     *
     * @return void
     */
    public function deleteProductOfferPrices(PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer): void;

    /**
     * Specification:
     * - Retrieves and returns count of entities from `spy_price_product_offer` over PriceProductOfferCriteriaTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
     *
     * @return int
     */
    public function count(PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer): int;

    /**
     * Specification:
     * - Retrieves collection of PriceProductTransfer by PriceProductOfferCriteriaTransfer.
     * - Executes `PriceProductOfferExpanderPluginInterface` plugin stack.
     * - Executes `PriceProductOfferExtractorPluginInterface` plugin stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function getProductOfferPrices(
        PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
    ): ArrayObject;

    /**
     * Specification:
     * - Expands provided `WishlistItem` transfer object with `PriceProduct` transfer objects.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandWishlistItemWithPrices(WishlistItemTransfer $wishlistItemTransfer): WishlistItemTransfer;

    /**
     * Specification:
     * - Fetches a collection of price product offers from the Persistence.
     * - Uses `PriceProductOfferCriteriaTransfer.pagination.limit` and `PriceProductOfferCriteriaTransfer.pagination.offset` to paginate results with limit and offset.
     * - Returns `PriceProductOfferCollectionTransfer` filled with found price product offers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer
     */
    public function getPriceProductOfferCollection(PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer): PriceProductOfferCollectionTransfer;
}
