<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Business;

use Generated\Shared\Transfer\ProductOfferStockCriteriaFilterTransfer;

interface ProductOfferStockFacadeInterface
{
    /**
     * Specification:
     * - Returns is product offer is never out of stock by ProductOfferStockCriteriaFilterTransfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferStockCriteriaFilterTransfer $productOfferStockCriteriaTransfer
     *
     * @return bool
     */
    public function isProductOfferNeverOutOfStock(ProductOfferStockCriteriaFilterTransfer $productOfferStockCriteriaTransfer): bool;
}
