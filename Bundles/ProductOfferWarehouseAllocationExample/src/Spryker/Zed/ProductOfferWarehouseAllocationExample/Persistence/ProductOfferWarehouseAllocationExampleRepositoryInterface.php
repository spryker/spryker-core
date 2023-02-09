<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferWarehouseAllocationExample\Persistence;

use Generated\Shared\Transfer\ProductOfferWarehouseCriteriaTransfer;
use Generated\Shared\Transfer\StockTransfer;

interface ProductOfferWarehouseAllocationExampleRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferWarehouseCriteriaTransfer $productOfferWarehouseCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer|null
     */
    public function findProductOfferWarehouse(
        ProductOfferWarehouseCriteriaTransfer $productOfferWarehouseCriteriaTransfer
    ): ?StockTransfer;
}
