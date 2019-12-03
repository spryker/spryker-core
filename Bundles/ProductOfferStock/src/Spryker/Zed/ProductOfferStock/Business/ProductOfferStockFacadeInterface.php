<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Business;

use Generated\Shared\Transfer\ProductOfferStockCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;
use Spryker\DecimalObject\Decimal;

interface ProductOfferStockFacadeInterface
{
    /**
     * Specification:
     * - Returns is product offer is never out of stock by fkProductOffer in ProductOfferStockCriteriaFilterTransfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStockCriteriaFilterTransfer $productOfferStockCriteriaTransfer
     *
     * @return bool
     */
    public function isProductOfferNeverOutOfStock(
        ProductOfferStockCriteriaFilterTransfer $productOfferStockCriteriaTransfer
    ): bool;

    /**
     * Specification:
     * - Returns product offer stock for provided request criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStockRequestTransfer $productOfferStockRequestTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function calculateProductOfferStockForRequest(ProductOfferStockRequestTransfer $productOfferStockRequestTransfer): Decimal;
}
