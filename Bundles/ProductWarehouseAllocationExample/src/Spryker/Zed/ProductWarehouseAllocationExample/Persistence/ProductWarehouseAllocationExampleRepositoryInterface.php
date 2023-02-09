<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductWarehouseAllocationExample\Persistence;

use Generated\Shared\Transfer\ProductWarehouseCriteriaTransfer;
use Generated\Shared\Transfer\StockTransfer;

interface ProductWarehouseAllocationExampleRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductWarehouseCriteriaTransfer $productWarehouseCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer|null
     */
    public function findProductWarehouse(ProductWarehouseCriteriaTransfer $productWarehouseCriteriaTransfer): ?StockTransfer;
}
