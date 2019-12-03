<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Persistence;

use Generated\Shared\Transfer\ProductOfferStockCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;
use Spryker\DecimalObject\Decimal;

interface ProductOfferStockRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferStockRequestTransfer $productOfferStockRequestTransfer
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function getProductOfferStockForRequest(ProductOfferStockRequestTransfer $productOfferStockRequestTransfer): Decimal;

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStockCriteriaFilterTransfer $productOfferStockCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStockTransfer|null
     */
    public function findOne(
        ProductOfferStockCriteriaFilterTransfer $productOfferStockCriteriaFilterTransfer
    ): ?ProductOfferStockTransfer;
}
