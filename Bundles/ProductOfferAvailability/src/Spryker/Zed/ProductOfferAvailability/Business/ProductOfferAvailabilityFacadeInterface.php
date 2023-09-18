<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailability\Business;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer;

interface ProductOfferAvailabilityFacadeInterface
{
    /**
     * Specification:
     * - Calculates product offer availability by product offer store, product offer stock and concrete product reserved amount.
     * - Expects `ProductOfferAvailabilityRequestTransfer.sku` to be provided.
     * - Expects `ProductOfferAvailabilityRequestTransfer.productOfferReference` to be provided.
     * - Expects `ProductOfferAvailabilityRequestTransfer.store.idStore` to be provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    public function findProductConcreteAvailability(
        ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
    ): ?ProductConcreteAvailabilityTransfer;

    /**
     * Specification:
     * - Expects `OrderTransfer.store` to be set.
     * - Iterares over `OrderTransfer.items`.
     * - Does nothing if `ItemTransfer.productOfferReference` is not set.
     * - Does nothing if `ProductOfferStock` is not found by provided `ItemTransfer.productOfferReference`.
     * - Checks OMS reservation to calculate stock availabiliy.
     * - Sets `Item.merchantStockAddresses` with collection of `MerchantStockAddress` split by quantity to ship from each stock.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderItemsWithMerchantStockAddressSplitByStockAvailability(OrderTransfer $orderTransfer): OrderTransfer;

    /**
     * Specification:
     * - Expects `CalculableObjectTransfer.store` to be set.
     * - Expects `CalculableObjectTransfer.store.name` to be set.
     * - Iterares over `CalculableObjectTransfer.items`.
     * - Does nothing if `ItemTransfer.productOfferReference` is not set.
     * - Does nothing if `ProductOfferStock` is not found by provided `ItemTransfer.productOfferReference`.
     * - Checks OMS reservation to calculate stock availabiliy.
     * - Sets `Item.merchantStockAddresses` with collection of `MerchantStockAddress` split by quantity to ship from each stock.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function expandCalculableObjectItemsWithMerchantStockAddressSplitByStockAvailability(
        CalculableObjectTransfer $quoteTransfer
    ): CalculableObjectTransfer;
}
