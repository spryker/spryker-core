<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Business\ProductOfferStock;

use Generated\Shared\Transfer\ProductOfferStockCriteriaFilterTransfer;

interface ProductOfferStockReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferStockCriteriaFilterTransfer $productOfferStockCriteriaFilterTransfer
     *
     * @return bool
     */
    public function isProductOfferNeverOutOfStock(
        ProductOfferStockCriteriaFilterTransfer $productOfferStockCriteriaFilterTransfer
    ): bool;
}
